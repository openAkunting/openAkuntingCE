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
  newInvoiceDetail: any = [];
  newInvoicePayment: any = new NewInvoicePayment("", "");

  currencyOptions: any = { prefix: '', thousands: '.', decimal: ',', precision: 0, }
 
  selectAccount: any = [];
  selectCashBank: any = [];
  addRowPayment: boolean = false;
  tabs : string = '';
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

  updateLink(id:string){
    this.invoiceId = id;
    this.router.navigate(['ap/invoice/detail'], {queryParams:{id:id}}).then( () => {
      this.httpGet();
    })
  }

  addRowDetail(){
    this.newInvoiceDetail.push({
      gnr : "",
      po : "",
      accountId : "",
      amount : "",
    });
  }

  httpGetInvoice() {
    this.http.get<any>(environment.api + "ApInvoice/index", {
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
    this.http.get<any>(environment.api + "ApInvoice/detail", {
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



  onInsertNewInvoiceDetail() {
    const body = {
      data: this.newInvoiceDetail,
      invoiceId: this.invoiceId,
    }
    this.http.post<any>(environment.api + "ApInvoice/onInsertNewInvoiceDetail", body, {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        console.log(data);
        if (data['error'] == false) {
          this.httpGet();
          this.newInvoiceDetail = [];
        } 

      },
      error => {
        console.log(error);
      }
    )
  }

  checkBoxAllDetail: boolean = false;
  checkBoxAllPayment: boolean = false;
  
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

  onCheckBoxAllPayment() {
    if (this.checkBoxAllPayment == false) {
      this.checkBoxAllPayment = true;

    }
    else if (this.checkBoxAllPayment == true) {
      this.checkBoxAllPayment = false;
    }

    for (let i = 0; i < this.itemPayments.length; i++) {
      this.itemPayments[i]['checkBox'] = this.checkBoxAllPayment;
    }
  }

  onCheckBoxPayment(i: number, item: any) {
    console.log(i, item);
    if (item['checkBox'] == true) {
      this.itemPayments[i]['checkBox'] = '';
    }
    else if (item['checkBox'] != true) {
      this.itemPayments[i]['checkBox'] = true;
    }

    this.checkBoxAllPayment = false;
  }


  onDeleteDetail() {
    const body = {
      invoiceId: this.invoiceId,
      data: this.itemDetails,
    }
    if (confirm("Delete checkbox item detail ? ")) {

      this.http.post<any>(environment.api + "ApInvoice/onDeleteDetail", body, {
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

      this.http.post<any>(environment.api + "ApInvoice/onDeletePayment", body, {
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

  onInsertNewInvoicePayment() {
    const body = {
      data: this.newInvoicePayment,
      invoiceId: this.invoiceId,
    }
    this.http.post<any>(environment.api + "ApInvoice/onInsertNewInvoicePayment", body, {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        console.log(data);
        if (data['error'] == false) {
          this.httpGet();
        }
        this.newInvoicePayment.amount = "0";
      },
      error => {
        console.log(error);
      }
    )
  }

}
