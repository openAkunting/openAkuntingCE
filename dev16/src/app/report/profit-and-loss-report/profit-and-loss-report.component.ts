import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core'; 
import { ConfigService } from 'src/app/service/config.service';
import { LanguageService } from 'src/app/service/language.service';
import { environment } from 'src/environments/environment'; 
import { ActivatedRoute, Router } from '@angular/router';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { JournalByAccountComponent } from 'src/app/generalLedger/journal-by-account/journal-by-account.component';
import { FunctionService } from 'src/app/service/function.service';
import { DateRanges } from 'src/app/masterData/global-class'; 

@Component({
  selector: 'app-profit-and-loss-report',
  templateUrl: './profit-and-loss-report.component.html',
  styleUrls: ['./profit-and-loss-report.component.css']
})
export class ProfitAndLossReportComponent implements OnInit {
  items : any = [];
  params : any = [];
  total : any = [];
  range: any = new DateRanges([], []);

  constructor(
    private http: HttpClient,
    private configService: ConfigService, 
    public lang: LanguageService,
    public router: Router,
    public activeRouter: ActivatedRoute,  
    private modalService: NgbModal,
    public functionService: FunctionService,
  ) { 
  }

  ngOnInit(): void {
    this.params = this.activeRouter.snapshot.queryParams;
    console.log(this.params);
    const date = new Date();
    let  [year, month, day] = this.params['startDate'].split("-").map(Number);
    this.range.startDate = {
      year:year,
      month: month,
      day: day,
    };
    [year, month, day] = this.params['endDate'].split("-").map(Number);
    this.range.endDate = {
      year:year,
      month: month,
      day: day,
    };
    this.httpGet();
  }


  httpGet(){
    
    this.http.get<any>(environment.api+"Reports/ProfitAndLoss",{
      headers:this.configService.headers(),
      params: {
        startDate: this.range.startDate['year'] + "-" + this.range.startDate['month'].toString().padStart(2, '0') + "-" + this.range.startDate['day'],
        endDate: this.range.endDate['year'] + "-" + this.range.endDate['month'].toString().padStart(2, '0') + "-" + this.range.endDate['day'],
      }
    }).subscribe(
      data=>{
        this.items = data['data'];
        this.total = data['total'];
        console.log(data);
      },
      error=>{
        console.log(error);
      }
    )
  }


  addLevel(account:any){
    let tab = "";
    for(let i = 0; i < account['level'] ; i++){
      tab += "&nbsp;";
    } 
    tab += "<code>"+account['id']+"</code>"+' : ';

    return tab+account['name'] ;
  }
  detail(x:any){
    console.log(x);
    const modalRef = this.modalService.open(JournalByAccountComponent, {size:'xl'});
		modalRef.componentInstance.id = x.id;
    modalRef.componentInstance.startDate= this.range.startDate;
    modalRef.componentInstance.endDate= this.range.endDate;
    
    modalRef.componentInstance.title = 'Profit And Loss '+this.params['startDate']+" "+this.params['endDate'];
    
  }

  onCheckRange(){
    const dayDeff : number = this.functionService.onCheckRange(this.range.startDate, this.range.endDate)['daysDiff'];
    if (dayDeff < 0) {
      this.range.endDate = this.range.startDate;
    }
    
  }

  filterDate(){
    this.httpGet();
  }
}
