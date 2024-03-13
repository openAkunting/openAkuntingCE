import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { NgbModal, NgbModalConfig } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { LanguageService } from 'src/app/service/language.service';
import { environment } from 'src/environments/environment';
import { ActivatedRoute, Router } from '@angular/router';
import { DateRanges } from 'src/app/masterData/global-class';
import { FunctionService } from 'src/app/service/function.service';
import { JournalDetailComponent } from '../journal/journal-detail/journal-detail.component';

@Component({
  selector: 'app-ledger',
  templateUrl: './ledger.component.html',
  styleUrls: ['./ledger.component.css']
})
export class LedgerComponent implements OnInit {
  items: any = [];
  params : any = [];
  controller: string = "Journal";
  range: any = new DateRanges([], []);
  constructor(
    private http: HttpClient,
    private configService: ConfigService,
    private modalService: NgbModal,
    public lang: LanguageService,
    public router: Router,
    public activeRouter: ActivatedRoute,
    public functionService: FunctionService,
    
    config: NgbModalConfig,
  ) {
    config.backdrop = 'static';
    config.keyboard = false;
  }
 
  ngOnInit() {
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

  httpGet() {
    this.http.get<any>(environment.api + "Ledger/index",{
      headers:this.configService.headers(),
      params: {
        startDate: this.range.startDate['year'] + "-" + this.range.startDate['month'].toString().padStart(2, '0') + "-" + this.range.startDate['day'],
        endDate: this.range.endDate['year'] + "-" + this.range.endDate['month'].toString().padStart(2, '0') + "-" + this.range.endDate['day'],
      }
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
  filterDate() {
    this.httpGet();
  }
  onCheckRange(){
    const dayDeff : number = this.functionService.onCheckRange(this.range.startDate, this.range.endDate)['daysDiff'];
    if (dayDeff < 0) {
      this.range.endDate = this.range.startDate;
    }
    
  }


  
  detail(item: any) { 
    console.log(item);
    const modalRef = this.modalService.open(JournalDetailComponent, { size: 'xl' });
    modalRef.componentInstance.controller = this.controller;
    modalRef.componentInstance.id = item.journalId;
    modalRef.componentInstance.newItemEvent.subscribe(() => {
      this.httpGet();
    });
  }
}
