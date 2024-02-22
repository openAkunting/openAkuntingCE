import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core'; 
import { ConfigService } from 'src/app/service/config.service';
import { LanguageService } from 'src/app/service/language.service';
import { environment } from 'src/environments/environment'; 
import {   ActivatedRoute, Router } from '@angular/router';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { JournalDetailComponent } from 'src/app/generalLedger/journal/journal-detail/journal-detail.component';

@Component({
  selector: 'app-journal-list-report',
  templateUrl: './journal-list-report.component.html',
  styleUrls: ['./journal-list-report.component.css']
})
export class JournalListReportComponent implements OnInit {
  items : any = [];
  params : any = [];
  controller : string = "Journal";
  constructor(
    private http: HttpClient,
    private configService: ConfigService, 
    public lang: LanguageService,
    public router: Router,
    public activeRouter: ActivatedRoute,  
    private modalService: NgbModal,
  ) { 
  }

  ngOnInit(): void {
    this.params = this.activeRouter.snapshot.queryParams;
    console.log(this.params);
    this.httpGet();
  }


  httpGet(){
    const body = {
      startDate : this.params['startDate'],
      endDate : this.params['endDate'],
      //typeTransaction : 'journal',
    //  branchId : '',
    }
    this.http.get<any>(environment.api+"journalReport",{
      headers:this.configService.headers(),
      params : body
    }).subscribe(
      data=>{
        this.items = data['data'];
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

}
