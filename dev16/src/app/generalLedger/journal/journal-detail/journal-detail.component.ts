import { HttpClient } from '@angular/common/http';
import { AfterViewInit, Component, EventEmitter, HostListener, Input, OnInit, Output } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { LanguageService } from 'src/app/service/language.service';
import { environment } from 'src/environments/environment';
declare var $: any;

export class Model {
  constructor(
    public journalDate: any,
    public note: string,
    public ref: string,
  ) { }
}

export class CashBank {
  constructor(
    public id : string,
    public accountId: string,
    public position: string,
  ) { }
}
@Component({
  selector: 'app-journal-detail',
  templateUrl: './journal-detail.component.html',
  styleUrls: ['./journal-detail.component.css']
})
export class JournalDetailComponent implements OnInit, AfterViewInit {
  @HostListener('window:keydown', ['$event'])
  onKeyPress($event: KeyboardEvent) {
    console.log($event.keyCode);
    if($event.keyCode == 27){
      this.activeModal.dismiss();
    }
    // if (($event.ctrlKey || $event.metaKey) && $event.keyCode == 67) {
    //   this.calculation(); console.log('CTRL + C 2');
    // }
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
  @Input() id: any;
 // @Input() controller: any;
  
  account: any;
  outlet: any;
  items: any = [];
  disable: boolean = true;
  currencyOptions: any = { prefix: '', thousands: '.', decimal: ',', precision: 0, }
  summary: any = {
    totalCredit: 0,
    totalDebit: 0,
    balance: 0,
    summary : 0,
  }
  templateId: string = "";
  nameOfTemplate: string = "";
  submit: boolean = false;
  model: any = [];
  selectTemplate: any = [];
  selectAccount: any = [];
  selectOutlet: any = [];
  journalHeader: any = [];
 // typeJournal: string = 'single';
  typeOfJournal: string = "journal";
  cashbank: any = new CashBank("","", "debit");
  selectAccountCashBank: any = [];

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
  ngAfterViewInit(): void {
    this.jqSortable();
  }

  newItem() {
    const curDate = new Date();
    this.model = new Model(
      { "year": curDate.getFullYear(), "month": curDate.getMonth() + 1, "day": curDate.getDate() }, "", "");
  }

  httpGet() {
    this.http.get<any>(environment.api +  "journal/detail", {
      headers: this.configService.headers(),
      params: {
        id: this.id
      }
    }).subscribe(
      data => {
        console.log(data);
        this.selectAccountCashBank = data['accountCashBank'];
        this.typeOfJournal = data['typeOfJournal'];
        const parts = data['header']['journalDate'].split('-');
        // Membuat objek JSON dengan bagian-bagian yang diekstrak
        const journalDate = {
          "year": parseInt(parts[0]),
          "month": parseInt(parts[1]),
          "day": parseInt(parts[2])
        };

        this.selectAccount = data['account'];
        this.selectOutlet = data['outlet'];
 
        data['items'].forEach((el: any) => { 
          this.items.push({
            id: el['id'],
            outletId: el['outletId'],
            accountId: el['accountId'],
            selectAccount: [{
              id : '0',
              name : 'Loading...',
              coa : [
                {
                  id :  el['accountId'],
                  name : "loading...",
                  status : "1"
                }
              ]
            }],
            description: el['description'],
            debit: el['debit'],
            credit: el['credit'],
            amount : el['amount'],
          },)
        });

        this.model['ref'] = data['header']['ref'];
        this.model['journalDate'] = journalDate;
        this.model['note'] = data['header']['note'];
        this.journalHeader = data['header'];
        this.cashbank = new CashBank(data['cashbank']['id'], data['cashbank']['accountId'], data['cashbank']['position']);
        console.log(this.items);
        this.calculation(); 
        for(let i = 0; i < this.items.length; i++){ 
          this.onSelectOutlet(this.items[i]['outletId'], i); 
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  addrow() {
    const body = {
      id: this.id,
      controller: "journal",
    }
    this.http.post<any>(environment.api + "journal/addRow", body, {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        console.log(data);
        const temp = {
          id: data['item']['id'],
          outletId: "",
          accountId: "",
          selectAccount : [],
          description: "",
          debit: 0,
          credit: 0,
        }
        this.items.push(temp);
      },
      error => {
        console.log(error);
      }
    )

    this.calculation();
  }

  removeRow(index: number) {
   
    if(confirm("Delete this item ?") ){
      this.items.splice(index, 1);
      this.calculation();
      this.onUpdate();
    }
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
    console.log(this.items);
  }

  calculation() {

    this.summary.credit = 0;
    this.summary.debit = 0;
    this.summary.amount = 0;
    for (let i = 0; i < this.items.length; i++) {

      this.summary.credit += parseFloat(this.items[i].credit);
      this.summary.debit += parseFloat(this.items[i].debit);
    }
    this.summary.balance = this.summary.credit - this.summary.debit;

    this.submit = true;
    for (let i = 0; i < this.items.length; i++) {  
     this.summary.amount += parseFloat(this.items[i]['amount']);
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

  onUpdate() {
    const body = {
      items: this.items,
      model: this.model,
      journalId: this.id,
      cashbank : this.cashbank,
      typeOfJournal : this.typeOfJournal,
    }
    this.http.post<any>(environment.api + "journal/onUpdate", body, {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        console.log(data);
        this.newItemEvent.emit();
      //  this.activeModal.close();
      },
      error => {
        console.log(error);
      }
    )

  }

  

  editable(status: boolean) {
    this.disable = status;
  }

  onSorting(order: any) {
    const body = {
      order: order,
      journalId: this.id
    }
    this.http.post<any>(environment.api + "journal/onSorting", body, {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        console.log(data);
        this.newItemEvent.emit();
      },
      error => {
        console.log(error);
      }
    )
  }

  jqSortable() {
    var self = this;
    $(function () {
      $(".sortable").sortable({
        placeholder: "ui-state-highlight",
        handle: ".handle",
        update: function (event: any, ui: any) {

          const order: any[] = [];
          $(".handle").each((index: number, element: any) => {
            const itemId = $(element).data("id");
            order.push(itemId);
          });

          const body = {
            order: order,
          }
          console.log(body);
          self.onSorting(order)
        }
      });

    });
  }
}
