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

export class NewPayment {
  constructor(
    public supplierId: string,
    public accountId: string, 
    public paymentDate: any,
    public dueDate: any,
    public amount: string,  
    public memo : string,
  ) { }
}
@Component({
  selector: 'app-ap-payment',
  templateUrl: './ap-payment.component.html',
  styleUrls: ['./ap-payment.component.css']
})
export class ApPaymentComponent implements OnInit { 
  invoice : any = [];
  payment : any = [];
  
  params : any = [];
  balance : any = [];
  range: any = new Ranges([], []);
  warning: string = "";
  selectSupplier : any = [];
  selectAccount : any = [];
  
  newPayment : any = new NewPayment("","",[],"","","");
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
  ngOnInit(){
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
  httpGet(){
    this.http.get<any>(environment.api+"ApPayment/index",{
      headers:this.configService.headers(),
      params: {
        startDate: this.range.startDate['year'] + "-" + this.range.startDate['month'].toString().padStart(2, '0') + "-" + this.range.startDate['day'],
        endDate: this.range.endDate['year'] + "-" + this.range.endDate['month'].toString().padStart(2, '0') + "-" + this.range.endDate['day'],
      }
    }).subscribe(
      data=>{ 
        this.payment = data['payment']; 
        
        this.selectSupplier = data['selectSupplier'];
       // this.selectAccount = data['selectAccount'];
        
        console.log(data);
      },
      error=>{
        console.log(error);
      }
    )
  }

  accountName(id:string){ 

    const index = this.selectSupplier.findIndex((item: { [x: string]: string; }) => item['id'] === id);

    if (index !== -1) {
      this.newPayment['accountId']  = this.selectSupplier[index]['accountId'];
      return this.selectSupplier[index]['accountId']+" : "+this.selectSupplier[index]['account'];
    } else {
      return null;
    } 
  }
  detail(x :any){
    console.log(x);
    this.router.navigate(['ap/payment/detail'], {queryParams:{ id : x.id}});
  }

  onCheckRange() {
  
    const startDate = this.range.startDate['year'] +"-"+ this.range.startDate['month'].toString().padStart(2, '0') +"-"+ this.range.startDate['day'];
    const endDate = this.range.endDate['year'] +"-"+ this.range.endDate['month'].toString().padStart(2, '0') +"-"+ this.range.endDate['day'];
 
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
    if(daysDiff > 100){
      this.warning = "Date range must be less than 100 days";
    }
    console.log("onCheckRange", this.range, startDate,startDateObj, daysDiff);
  }

  filterDate(){
    
  }

  onInsertNewPayment(){ 
    const body = { 
      data: this.newPayment, 
    }
    this.http.post<any>(environment.api + "ApPayment/onInsertNewPayment", body, {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        console.log(data); 
        if(data['error'] == false){
          this.httpGet();
        }
        this.newPayment.amount = "";

      },
      error => {
        console.log(error);
      }
    )
  }  
}
