import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { ConfigService } from 'src/app/service/config.service';
import { LanguageService } from 'src/app/service/language.service';
import { environment } from 'src/environments/environment';
import { ActivatedRoute, Router } from '@angular/router';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';

export class Ranges {
  constructor(
    public startDate: any,
    public endDate: any,
  ) { }
}

export class NewInvoice {
  constructor(
    public supplierId: string,
    public invoiceDate: any,
    public due: any,
    public amount: string,
    public gnrNo: string,
    public poNo: string,
    public creditAccountId: string,

  ) { }
}

@Component({
  selector: 'app-ap-invoice',
  templateUrl: './ap-invoice.component.html',
  styleUrls: ['./ap-invoice.component.css']
})
export class ApInvoiceComponent implements OnInit {
  items: any = [];
  params: any = [];
  balance: any = [];
  range: any = new Ranges([], []);
  warning: string = "";
  selectSupplier: any = [];
  selectAccount: any = [];
  selectCashBank: any = [];
  newInvoice: any = new NewInvoice("", [], [], "", "", "", "");
  currencyOptions: any = { prefix: '', thousands: '.', decimal: ',', precision: 0, }
  constructor(
    private http: HttpClient,
    private configService: ConfigService,
    public lang: LanguageService,
    public router: Router,
    public activeRouter: ActivatedRoute,
    private modalService: NgbModal
  ) {
  }
  ngOnInit() {
    this.params = this.activeRouter.snapshot.queryParams;
    console.log(this.params);
    const date = new Date();
    this.range.startDate = {
      year: date.getFullYear(),
      month: date.getMonth() + 1,
      day: date.getDate(),
    };
    this.range.endDate = {
      year: date.getFullYear(),
      month: date.getMonth() + 1,
      day: date.getDate(),
    };
    this.httpGet();
  }
  httpGet() {
    this.http.get<any>(environment.api + "ApInvoice/index", {
      headers: this.configService.headers(),
      params: {
        startDate: this.range.startDate['year'] + "-" + this.range.startDate['month'].toString().padStart(2, '0') + "-" + this.range.startDate['day'],
        endDate: this.range.endDate['year'] + "-" + this.range.endDate['month'].toString().padStart(2, '0') + "-" + this.range.endDate['day'],
      }
    }).subscribe(
      data => {
        this.items = data['items'];
        this.selectSupplier = data['selectSupplier'];

        console.log(data);
        this.httpGetAccount();
      },
      error => {
        console.log(error);
      }
    )
  }

  httpGetAccount() {
    this.http.get<any>(environment.api + "account/coa", {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        this.selectAccount = data['account'];
        this.selectCashBank = data['cashBank'];

        console.log(data);
      },
      error => {
        console.log(error);
      }
    )
  }


  detail(x: any) {
    console.log(x);
    this.router.navigate(['ap/invoice/detail/'], { queryParams: { id: x.id } });
  }

  onCheckRange() {

    const startDate = this.range.startDate['year'] + "-" + this.range.startDate['month'].toString().padStart(2, '0') + "-" + this.range.startDate['day'];
    const endDate = this.range.endDate['year'] + "-" + this.range.endDate['month'].toString().padStart(2, '0') + "-" + this.range.endDate['day'];

    const startDateInt = parseInt(this.range.startDate['year'] + this.range.startDate['month'].toString().padStart(2, '0') + this.range.startDate['day']);
    const endDateInt = parseInt(this.range.endDate['year'] + this.range.endDate['month'].toString().padStart(2, '0') + this.range.endDate['day']);

    // Parse string tanggal ke objek Date
    const startDateObj = new Date(startDate);
    const endDateObj = new Date(endDate);

    // Hitung perbedaan waktu dalam milidetik
    const timeDiff = endDateObj.getTime() - startDateObj.getTime();

    // Konversi milidetik ke hari
    const daysDiff = Math.floor(timeDiff / (1000 * 60 * 60 * 24));


    if (startDateInt > endDateInt) {
      this.range.endDate = this.range.startDate;
    }
    if (daysDiff > 100) {
      this.warning = "Date range must be less than 100 days";
    }
    console.log("onCheckRange", this.range, startDate, startDateObj, daysDiff);
  }

  filterDate() {

  }

  onInsertNewInvoice() {
 
    this.newInvoice['invoiceDate'] = `${this.newInvoice['invoiceDate']['year']}-${String(this.newInvoice['invoiceDate']['month']).padStart(2, '0')}-${String(this.newInvoice['invoiceDate']['day']).padStart(2, '0')}`;
    this.newInvoice['due'] = `${this.newInvoice['due']['year']}-${String(this.newInvoice['due']['month']).padStart(2, '0')}-${String(this.newInvoice['due']['day']).padStart(2, '0')}`;
    
    const body = {
      data: this.newInvoice,
    }
  
    this.http.post<any>(environment.api + "ApInvoice/onInsertNewInvoice", body, {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        console.log(data);
        if (data['error'] == false) {
          this.httpGet();
        }
        this.newInvoice.amount = "";

      },
      error => {
        console.log(error);
      }
    )
  }

  apAccount() {
    const index = this.selectSupplier.findIndex((item: { [x: string]: any; }) => item['id'] === this.newInvoice['supplierId']);

    if (index !== -1) {
      return this.selectSupplier[index]['creditAccountId'] + " : " + this.selectSupplier[index]['creditAccountName'];
    } else {
      return '';
    }
  }
}
