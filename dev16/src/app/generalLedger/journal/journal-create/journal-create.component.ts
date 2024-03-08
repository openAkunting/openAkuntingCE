import { HttpClient } from '@angular/common/http';
import { Component, EventEmitter, HostListener, Input, OnInit, Output } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { LanguageService } from 'src/app/service/language.service';
import { environment } from 'src/environments/environment';
export class Model {
  constructor(
    public journalDate: any,
    public note: string,
    public ref: string,

    public startPeriod: any,
    public endPeriod: any,
    public dateOfJournal: number,
    public recurringPerMonth: number,

  ) { }
}

export class CashBank {
  constructor(
    public accountId: string,
    public position: string,
  ) { }
}
@Component({
  selector: 'app-journal-create',
  templateUrl: './journal-create.component.html',
  styleUrls: ['./journal-create.component.css']
})
export class JournalCreateComponent implements OnInit {
  @HostListener('window:keydown', ['$event'])
  onKeyPress($event: KeyboardEvent) {
    // case 67: break;  //Keyboard.C
    // case 86: break;  //Keyboard.V
    // case 88: break;  //Keyboard.X
    if (($event.ctrlKey || $event.metaKey) && ($event.keyCode == 86 || $event.keyCode == 88)) {
      var self = this;
      setTimeout(function () {
        self.calculation();
      }, 100);
      console.log('CTRL +  V 1');
    }
  }
  @Output() newItemEvent = new EventEmitter<string>();
  @Input() controller: any;
  typeOfJournal: string = "journal";
  account: any;
  outlet: any;
  items: any = [];
  currencyOptions: any = { prefix: '', thousands: '.', decimal: ',', precision: 0, }
  summary: any = {
    totalCredit: 0,
    totalDebit: 0,
    balance: 0
  }
  templateId: string = "";
  nameOfTemplate: string = "";
  submit: boolean = false;
  model: any = [];
  selectTemplate: any = [];
  selectAccount: any = [];
  selectOutlet: any = [];
  recurringOfJournal: string = 'oneTime';
  cashbank: any = new CashBank("", "debit");
  selectAccountCashBank: any = [];
  constructor(
    public activeModal: NgbActiveModal,
    private http: HttpClient,
    private configService: ConfigService,
    public lang: LanguageService,
  ) { }


  ngOnInit(): void {
    this.newItem();
    this.httpGet();
  }

  newItem() {
    const curDate = new Date();
    this.model = new Model(
      { "year": curDate.getFullYear(), "month": curDate.getMonth() + 1, "day": curDate.getDate() }, "", "",
      { "year": curDate.getFullYear(), "month": curDate.getMonth() + 1, "day": curDate.getDate() },
      { "year": curDate.getFullYear(), "month": curDate.getMonth() + 1, "day": curDate.getDate() }, 1, 1
    );
    this.items = [
      {
        outletId: '',
        accountId: "",
        selectAccount: [],
        description: "",
        debit: 0,
        credit: 0,
      },
      {
        outletId: '',
        accountId: "",
        selectAccount: [],
        description: "",
        debit: 0,
        credit: 0,
      }
    ];
  }

  httpGet() {
    this.http.get<any>(environment.api + "Journal/selectItems", {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        this.selectAccount = data['account'];
        this.selectOutlet = data['outlet'];
        this.selectTemplate = data['template'];
        this.selectAccountCashBank = data['accountCashBank'];
        //console.log(data);
      },
      error => {
        console.log(error);
      }
    )
  }

  addrow() {
    const temp = {
      outletId: "",
      accountId: "",
      description: "",
      debit: 0,
      credit: 0,
    }
    this.items.push(temp);
    this.calculation();
  }

  removeRow(index: number) {
    this.items.splice(index, 1);
    this.calculation();
  }

  onSelectOutlet(outletId: string, index: number) {
    this.calculation();
    this.http.get<any>(environment.api + "journal/onSelectOutlet", {
      headers: this.configService.headers(),
      params: {
        outletId: outletId
      }
    }).subscribe(
      data => {
        this.items[index]['selectAccount'] = data['items'];
      },
      error => {
        console.log(error);
      }
    )
  }

  keyPress(item: any, type: string) {
    if (type == 'debit') {
      item.credit = "0";
    }
    else if (type == 'credit') {
      item.debit = "0";
    }
    this.calculation();
  }

  calculation() {
    this.summary.credit = 0;
    this.summary.debit = 0;

    for (let i = 0; i < this.items.length; i++) {

      this.summary.credit += parseFloat(this.items[i].credit);
      this.summary.debit += parseFloat(this.items[i].debit);
    }
    this.summary.balance = this.summary.credit - this.summary.debit;

    this.submit = true;
    for (let i = 0; i < this.items.length; i++) {
      if (this.items[i]['accountId'] == '') {
        this.submit = false;
        i += 1000;
      }
    }

    if (this.typeOfJournal == 'cashbank') {
      this.submit = true;
      this.summary.balance = 0;
    }
  }

  fnClearCredit() {
   
    if (this.typeOfJournal == 'cashbank') {
      for (let i = 0; i < this.items.length; i++) {
        this.items[i].credit = 0;
      }
    }
    this.calculation();
  }


  onSubmit() {

    let items = [];
    items = this.items.map((item: any) => {
      const newItem = { ...item };
      delete newItem.selectAccount;
      return newItem;
    });
    const body = {
      items: items,
      model: this.model,
      recurringOfJournal: this.recurringOfJournal,
      templateId: this.templateId,
      typeOfJournal: this.typeOfJournal,
      cashbank: this.cashbank,
    }
    this.http.post<any>(environment.api + "Journal/onSubmit", body, {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        console.log(data);
        this.newItemEvent.emit();
        // this.activeModal.close();
      },
      error => {
        console.log(error);
      }
    )

  }

  loadTemplate() {
    this.http.get<any>(environment.api + "Template/loadTemplate", {
      headers: this.configService.headers(),
      params: {
        templateId: this.templateId
      }
    }).subscribe(
      data => {
        // console.log(data);
        this.nameOfTemplate = data['template']['name'];
        this.model['note'] = data['template']['note'];
        this.model['ref'] = data['template']['ref'];
        this.cashbank = new CashBank(data['cashbank']['accountId'], data['cashbank']['position']);
        this.items = [];
        data['journal_template'].forEach((el: any) => {
          const temp =
          {
            outletId: el.outletId,
            accountId: el.accountId,
            selectAccount: [{
              id: '0',
              name: 'Loading...',
              coa: [
                {
                  id: el['accountId'],
                  name: "loading...",
                  status: "1"
                }
              ]
            }],
            description: el.description,
            debit: el.debit,
            credit: el.credit,
          };
          this.items.push(temp);
        });
        for (let i = 0; i < this.items.length; i++) {
          this.onSelectOutlet(this.items[i]['outletId'], i);
        }
        this.fnClearCredit();
      },
      error => {
        console.log(error);
      }
    )
  }

  onChangeTemplate() {
    
    this.http.get<any>(environment.api + "Template/onChangeTemplate", {
      headers: this.configService.headers(),
      params: {
        tableName: this.typeOfJournal,
      }
    }).subscribe(
      data => {
        this.selectTemplate = data['template'];

        console.log(data);
      },
      error => {
        console.log(error);
      }
    )
  }
  onSaveAsTemplate() {
    const body = {
      items: this.items,
      model: this.model,
      nameOfTemplate: this.nameOfTemplate,
      tableName: this.typeOfJournal,
      cashbank: this.cashbank
    }
    let error = false;
    if (this.typeOfJournal == 'cashbank') {
        if(this.cashbank.accountId == "" ){
          error = true;
        }
    }  

    if (error == false) { 
      this.http.post<any>(environment.api + "Template/onSaveAsTemplate", body, {
        headers: this.configService.headers(),
      }).subscribe(
        data => {
          this.onChangeTemplate();
        },
        error => {
          console.log(error);
        }
      )
    }else{
      alert("ERROR !");
    }
  }

}
