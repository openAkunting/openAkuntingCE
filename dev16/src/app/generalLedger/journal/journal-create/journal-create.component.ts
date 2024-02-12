import { HttpClient } from '@angular/common/http';
import { Component, HostListener, Input, OnInit } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { LanguageService } from 'src/app/service/language.service';
import { environment } from 'src/environments/environment';

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
  submit: boolean = false;
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
    this.items = [
      {
        accountId: "",
        description: "",
        debit: 0,
        credit: 0,
      },
      {
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
        this.account = data['account'];
        this.outlet = data['outlet'];
        console.log(data);
      },
      error => {
        console.log(error);
      }
    )
  }
  addrow() {
    const temp = {
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
    }
    this.http.post<any>(environment.api + "journal/onSubmit",body, {
      headers: this.configService.headers(),
    }).subscribe(
      data => { 
        console.log(data);
      },
      error => {
        console.log(error);
      }
    )
     
  }

}
