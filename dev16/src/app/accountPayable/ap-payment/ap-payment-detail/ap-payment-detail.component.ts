import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { ConfigService } from 'src/app/service/config.service';
import { LanguageService } from 'src/app/service/language.service';
import { environment } from 'src/environments/environment';
import { ActivatedRoute, Router } from '@angular/router';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
 
 
export class NewPaymentDetail {
  constructor(
    public invoiceId : string,
    public amount: string,
    public adjustment: string,
    public adjustmentAccountId: string,
   
    public paymentDate: any,
  ) { }
}
@Component({
  selector: 'app-ap-payment-detail',
  templateUrl: './ap-payment-detail.component.html',
  styleUrls: ['./ap-payment-detail.component.css']
})
export class ApPaymentDetailComponent implements OnInit {
  items: any = [];
  item: any = [];
  detail : any = [];
  id: any = [];
  itemDetails: any = [];
  itemPayments: any = [];
  selectAccount : any = [];
  active = 1; 
  warning: string = "";
  selectSupplier: any = [];
  newPaymentDetail: any = new NewPaymentDetail("","", "","",""); 
  account : any = [];
  currencyOptions: any = { prefix: '', thousands: '.', decimal: ',', precision: 0, }
  addRowDetail: boolean = false;
  addRowPayment: boolean = false;
  tabs : string = '';
  selectInvoice : any = [];
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
    this.id = this.activeRouter.snapshot.queryParams['id'];
    this.httpGetAccountCashBank();
    this.httpGet();
  }

  updateLink(id:string){
    this.id = id;
    this.router.navigate(['ap/invoice/detail'], {queryParams:{id:id}}).then( () => {
      this.httpGet();
    })
  }

  httpGetAccountCashBank() {
    this.http.get<any>(environment.api + "account/cashBank", {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        this.selectAccount= data['items'];  
        console.log(data);
      },
      error => {
        console.log(error);
      }
    )
  }
  httpGet() {
    this.http.get<any>(environment.api + "ApPayment/detail", {
      headers: this.configService.headers(),
      params: {
        id: this.id,
      }
    }).subscribe(
      data => {
        this.item = data['item'];
        this.itemDetails = data['itemDetails'];
     //   this.itemPayments = data['itemPayments'];

        this.selectInvoice = data['selectInvoice'];
        console.log(data);
      },
      error => {
        console.log(error);
      }
    )
  }



  onInsertNewInvoiceDetail() {
    const body = {
      data: this.newPaymentDetail,
      id: this.id,
    }
    this.http.post<any>(environment.api + "ApPayment/onInsertNewInvoiceDetail", body, {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        console.log(data);
        if (data['error'] == false) {
          this.httpGet();
        }
        this.newPaymentDetail.amount = "0";
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
      id: this.id,
      data: this.itemDetails,
    }
    if (confirm("Delete checkbox item detail ? ")) {

      this.http.post<any>(environment.api + "ApPayment/onDeleteDetail", body, {
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

