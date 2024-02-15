import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { NgbModal, NgbModalConfig } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { LanguageService } from 'src/app/service/language.service';
import { environment } from 'src/environments/environment';
import { JournalCreateComponent } from './journal-create/journal-create.component';
import { JournalDetailComponent } from './journal-detail/journal-detail.component';
import {   ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-journal',
  templateUrl: './journal.component.html',
  styleUrls: ['./journal.component.css']
})
export class JournalComponent implements OnInit {
  items: any = [];

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
  controller:string = "Journal";
  ngOnInit(): void { 
    console.log(this.activeRouter.snapshot.data['controller']); 
    this.controller = this.activeRouter.snapshot.data['controller'];
  
    this.httpGet();
  }

  httpGet() {
    this.http.get<any>(environment.api + this.controller+"/index").subscribe(
      data => {
        this.items = data['items'];
        console.log(data);
      },
      error => {
        console.log(error);
      }
    )
  }
  open() {
    const modalRef = this.modalService.open(JournalCreateComponent, { size: 'xl' });
    // modalRef.result.then(
    //   (result) => { 
    //     console.log(' ');
    //   },
    //   (reason) => {
    //     console.log('CLOSE 1001');

    //   },
    // );
    modalRef.componentInstance.controller = this.controller; 
   
    modalRef.componentInstance.newItemEvent.subscribe(() => {
      this.httpGet();
    });
  }

  detail(item: any) {
    
    const modalRef = this.modalService.open(JournalDetailComponent, { size: 'xl' });

    modalRef.componentInstance.id = item.id;
    modalRef.componentInstance.newItemEvent.subscribe(() => {
      this.httpGet();
    });
  }
}
