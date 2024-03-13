import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core'; 
import { ConfigService } from 'src/app/service/config.service';
import { LanguageService } from 'src/app/service/language.service';
import { environment } from 'src/environments/environment'; 
import {   ActivatedRoute, Router } from '@angular/router';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { JournalDetailComponent } from 'src/app/generalLedger/journal/journal-detail/journal-detail.component';
import { DateRanges } from 'src/app/masterData/global-class';
import { FunctionService } from 'src/app/service/function.service';

@Component({
  selector: 'app-journal-list-report',
  templateUrl: './journal-list-report.component.html',
  styleUrls: ['./journal-list-report.component.css']
})
export class JournalListReportComponent implements OnInit {
  items : any = [];
  params : any = [];
  controller : string = "Journal";
  range: any = new DateRanges([], []);
  constructor(
    private http: HttpClient,
    private configService: ConfigService, 
    public lang: LanguageService,
    public router: Router,
    public functionService: FunctionService,
    
    public activeRouter: ActivatedRoute,  
    private modalService: NgbModal,
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
    this.http.get<any>(environment.api+"journalReport",{
      headers:this.configService.headers(),
      params: {
        startDate: this.range.startDate['year'] + "-" + this.range.startDate['month'].toString().padStart(2, '0') + "-" + this.range.startDate['day'],
        endDate: this.range.endDate['year'] + "-" + this.range.endDate['month'].toString().padStart(2, '0') + "-" + this.range.endDate['day'],
      }
    }).subscribe(
      data=>{
       
        if(data['error']==true){
          alert(data['note']);
        }else{
          this.items = data['data'];
        }
        console.log(data);
      },
      error=>{
        console.log(error);
      }
    )
  }


  detail(item: any) { 
    const modalRef = this.modalService.open(JournalDetailComponent, { size: 'xl' });
    modalRef.componentInstance.controller = this.controller; 
    modalRef.componentInstance.id = item.id;
    modalRef.componentInstance.newItemEvent.subscribe(() => {
      this.httpGet();
    });
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
