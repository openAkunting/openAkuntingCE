import { HttpClient } from '@angular/common/http';
import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
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
  selector: 'app-journal-detail',
  templateUrl: './journal-detail.component.html',
  styleUrls: ['./journal-detail.component.css']
})
export class JournalDetailComponent implements OnInit {
  @Output() newItemEvent = new EventEmitter<string>();
  @Input() id: any;
  @Input() controller: any;
  
  account: any;
  outlet: any;
  items: any = [];
  disable : boolean = true;
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
  journalHeader : any = [];
  typeJournal : string = 'single';
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
    this.model = new Model(
      {"year": curDate.getFullYear(), "month": curDate.getMonth()+1, "day": curDate.getDate()},"",""); 
  }
   
  httpGet() {
    this.http.get<any>(environment.api + this.controller+"/detail", {
      headers: this.configService.headers(),
      params: {
        id : this.id 
      }
    }).subscribe(
      data => {
        const parts = data['header']['journalDate'].split('-'); 
        // Membuat objek JSON dengan bagian-bagian yang diekstrak
        const journalDate  = {
            "year": parseInt(parts[0]),
            "month": parseInt(parts[1]),
            "day": parseInt(parts[2])
        };

        this.selectAccount = data['account'];
        this.selectOutlet = data['outlet']; 
     

        data['items'].forEach((el: any) => {
          
          this.items.push({
            outletId : el['outletId'],
            accountId:  el['accountId'],
            description:  el['description'],
            debit:  el['debit'],
            credit:  el['credit'],
          },)
        });

        this.model['ref'] = data['header']['ref'];
        this.model['journalDate'] = journalDate;
        this.model['note'] = data['header']['note'];
        this.journalHeader = data['header'];
        console.log(data);
        this.calculation();
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
  onSubmit() {
    const body = {
      items : this.items,
      model :this.model,
      typeJournal : this.typeJournal,
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
  

}
