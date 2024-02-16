import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { NgbModal, NgbModalConfig } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { LanguageService } from 'src/app/service/language.service';
import { environment } from 'src/environments/environment'; 
import {   ActivatedRoute, Router } from '@angular/router';
@Component({
  selector: 'app-journal-list-report',
  templateUrl: './journal-list-report.component.html',
  styleUrls: ['./journal-list-report.component.css']
})
export class JournalListReportComponent implements OnInit {
  items : any = [];
  params : any = [];
  constructor(
    private http: HttpClient,
    private configService: ConfigService,
    private modalService: NgbModal,
    public lang: LanguageService,
    public router: Router,
    public activeRouter: ActivatedRoute, 
    config: NgbModalConfig,
  ) {
    config.backdrop = 'static';
    config.keyboard = false;
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

}
