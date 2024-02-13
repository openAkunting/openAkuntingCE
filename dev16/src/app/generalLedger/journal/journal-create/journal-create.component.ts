import { HttpClient } from '@angular/common/http';
import { Component, EventEmitter, HostListener, Input, OnInit, Output } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { LanguageService } from 'src/app/service/language.service';
import { environment } from 'src/environments/environment';
export class Model {
  constructor( 
    public journalDate: any,
    public note: string, 
    public ref: string, 
    
  ) {  }
}
@Component({
  selector: 'app-journal-create',
  templateUrl: './journal-create.component.html',
  styleUrls: ['./journal-create.component.css']
})
export class JournalCreateComponent implements OnInit {
  @HostListener('window:keydown', ['$event'])
  onKeyPress($event: KeyboardEvent) {
    if (($event.ctrlKey || $event.metaKey) && $event.keyCode == 67) {
      this.calculation(); console.log('CTRL + C 2');
    }

    if (($event.ctrlKey || $event.metaKey) && $event.keyCode == 86) {
      this.calculation();
      console.log('CTRL +  V 1');
    }
  }
  @Output() newItemEvent = new EventEmitter<string>();
  @Input() name: any;
  account: any;
  outlet: any;
  items: any = [];
  currencyOptions: any = { prefix: '', thousands: '.', decimal: ',', precision: 0, }
  summary: any = {
    totalCredit: 0,
    totalDebit: 0,
    balance: 0
  }
  templateId : string = "";
  nameOfTemplate : string = "";
  submit: boolean = false;
  model: any = [];
  selectTemplate: any = [];
  selectAccount: any = [];
  selectOutlet: any = [];
  
  constructor(
    public activeModal: NgbActiveModal,
    private http: HttpClient,
    private configService: ConfigService,
    public lang: LanguageService
  ) { }


  ngOnInit(): void {
    this.newItem();
    this.httpGet();
  }
  newItem() {
    const curDate = new Date();
    this.model = new Model({"year": curDate.getFullYear(), "month": curDate.getMonth()+1, "day": curDate.getDate()},"","");
    this.items = [
      {
        outletId : '',
        accountId: "",
        description: "",
        debit: 0,
        credit: 0,
      },
      {
        outletId : '',
        accountId: "",
        description: "",
        debit: 0,
        credit: 0,
      }
    ];
  }

  httpGet() {
    this.http.get<any>(environment.api + "journal/selectItems", {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        this.selectAccount = data['account'];
        this.selectOutlet = data['outlet'];
        this.selectTemplate = data['template'];
        console.log(data);
      },
      error => {
        console.log(error);
      }
    )
  }
  addrow() {
    const temp = {
      outletId : "",
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
  keyPress(item: any, type: string) {
    console.log(item[type], type);
    if (type == 'debit') {
      item.credit = "0";
    }
    else if (type == 'credit') {
      item.debit = "0";
    }
    this.calculation();
  }

  calculation() {
    console.log("calculation();")
    this.summary.credit = 0;
    this.summary.debit = 0;

    for (let i = 0; i < this.items.length; i++) {
      console.log(this.items[i].debit);
      this.summary.credit += parseFloat(this.items[i].credit);
      this.summary.debit += parseFloat(this.items[i].debit);
    }
    this.summary.balance = this.summary.credit - this.summary.debit;

    this.submit = true;
    for (let i = 0; i < this.items.length; i++) {
      console.log(this.items[i]['accountId']);
      if (this.items[i]['accountId'] == '') {
        this.submit = false;
        i += 1000;
      }
    }
  }
  onSaveAsTemplate(){
    const body = {
      items : this.items,
      model :this.model,
      nameOfTemplate :this.nameOfTemplate,
    }
    this.http.post<any>(environment.api + "journal/onSaveAsTemplate",body, {
      headers: this.configService.headers(),
    }).subscribe(
      data => { 
        console.log(data);
        this.httpGet();
      },
      error => {
        console.log(error);
      }
    )
  }
  onSubmit() {
    const body = {
      items : this.items,
      model :this.model,
    }
    this.http.post<any>(environment.api + "journal/onSubmit",body, {
      headers: this.configService.headers(),
    }).subscribe(
      data => { 
        console.log(data);
        this.newItemEvent.emit();
        this.activeModal.close();
      },
      error => {
        console.log(error);
      }
    )
     
  }

  loadTemplate(){
    this.http.get<any>(environment.api + "journal/loadTemplate", {
      headers: this.configService.headers(),
      params : {
        templateId : this.templateId
      }
    }).subscribe(
      data => {
        console.log(data);
        this.model['note'] = data['template']['note'];
        this.model['ref'] =  data['template']['ref'];  
        this.items = [];
        data['journal_template'].forEach((el: any) => {
          const temp = 
            {
              outletId : el.outletId,
              accountId: el.accountId,
              description: el.description,
              debit: el.debit,
              credit: el.credit,
            };
          this.items.push(temp);
        });
        this.calculation();
      },
      error => {
        console.log(error);
      }
    )
  }
}
