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

export class NewInvoiceDetail {
  constructor(
    public gnrNo: string,
    public poNo: any,
    public amount: string,
  ) { }
}

export class NewInvoicePayment {
  constructor(
    public amount: string,
    public paymentDate: any, 
  ) { }
}

@Component({
  selector: 'app-ap-invoice-detail',
  templateUrl: './ap-invoice-detail.component.html',
  styleUrls: ['./ap-invoice-detail.component.css']
})
export class ApInvoiceDetailComponent implements OnInit {
  items: any = [];
  item: any = [];
  invoiceId: any = [];
  itemDetails: any = [];
  itemPayments: any = [];
  active = 1;
  range: any = new Ranges([], []);
  warning: string = "";
  selectSupplier: any = [];
  newInvoiceDetail: any = new NewInvoiceDetail("", "", "");
  newInvoicePayment: any = new NewInvoicePayment("", "");
  
  currencyOptions: any = { prefix: '', thousands: '.', decimal: ',', precision: 0, }
  addRowDetail: boolean = false;
  addRowPayment: boolean = false;

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
    this.invoiceId = this.activeRouter.snapshot.queryParams['id'];
    this.httpGetInvoice();
    this.httpGet();
  }

  httpGetInvoice() {
    this.http.get<any>(environment.api + "ap/index", {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        this.items = data['items'];

        console.log(data);
      },
      error => {
        console.log(error);
      }
    )
  }
  httpGet() {
    this.http.get<any>(environment.api + "ap/detail", {
      headers: this.configService.headers(),
      params: {
        invoiceId: this.invoiceId,
      }
    }).subscribe(
      data => {
        this.item = data['item'];
        this.itemDetails = data['itemDetails'];
        this.itemPayments = data['itemPayments'];

        this.selectSupplier = data['selectSupplier'];
        console.log(data);
      },
      error => {
        console.log(error);
      }
    )
  }



  onInsertNewInvoiceDetail() {
    const body = {
      data: this.newInvoiceDetail,
      invoiceId: this.invoiceId,
    }
    this.http.post<any>(environment.api + "ap/onInsertNewInvoiceDetail", body, {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        console.log(data);
        if (data['error'] == false) {
          this.httpGet();
        }
        this.newInvoiceDetail.amount = "0";
      },
      error => {
        console.log(error);
      }
    )
  }

  checkBoxAllDetail: boolean = false;
  onCheckBoxAllDetail() {
    if (this.checkBoxAllDetail == false) {
      this.checkBoxAllDetail = true;

    }
    else if (this.checkBoxAllDetail == true) {
      this.checkBoxAllDetail = false;
    }

    for (let i = 0; i < this.itemDetails.length; i++) {
      this.itemDetails[i]['checkBox'] = this.checkBoxAllDetail;
    }
  }

  onCheckBoxDetail(i: number, item: any) {
    console.log(i, item);
    if (item['checkBox'] == true) {
      this.itemDetails[i]['checkBox'] = '';
    }
    else if (item['checkBox'] != true) {
      this.itemDetails[i]['checkBox'] = true;
    }

    this.checkBoxAllDetail = false;
  }

  onDeleteDetail() {
    const body = {
      invoiceId: this.invoiceId,
      data: this.itemDetails,
    }
    if (confirm("Delete checkbox item detail ? ")) {

      this.http.post<any>(environment.api + "ap/onDeleteDetail", body, {
        headers: this.configService.headers(),
      }).subscribe(
        data => {
          console.log(data);
          if (data['error'] == false) {
            this.httpGet();
          }
        },
        error => {
          console.log(error);
        }
      )
    }
  }
  onDeletePayment() {
    const body = {
      invoiceId: this.invoiceId,
      data: this.itemPayments,
    }
    if (confirm("Delete checkbox item detail ? ")) {

      this.http.post<any>(environment.api + "ap/onDeletePayment", body, {
        headers: this.configService.headers(),
      }).subscribe(
        data => {
          console.log(data);
          if (data['error'] == false) {
            this.httpGet();
          }
        },
        error => {
          console.log(error);
        }
      )
    }
  }



}
