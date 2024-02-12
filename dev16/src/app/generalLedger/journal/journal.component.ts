import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { NgbModal, NgbModalConfig } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { LanguageService } from 'src/app/service/language.service';
import { environment } from 'src/environments/environment'; 
import { JournalCreateComponent } from './journal-create/journal-create.component';

@Component({
  selector: 'app-journal',
  templateUrl: './journal.component.html',
  styleUrls: ['./journal.component.css']
})
export class JournalComponent implements OnInit {
  items:any = [];
   
  constructor(
    private http: HttpClient,
    private configService: ConfigService,
    private modalService: NgbModal, 
    public lang: LanguageService,
    config: NgbModalConfig,
  ) { 
    config.backdrop = 'static';
		config.keyboard = false;
  }

  ngOnInit(): void {
    this.httpGet();
  }

  httpGet(){
    this.http.get<any>(environment.api+"journal/index").subscribe(
      data=>{
        this.items = data['items'];
        console.log(data);
      },
      error=>{
        console.log(error);
      }
    )
  }
  open(){
    const modalRef = this.modalService.open(JournalCreateComponent, {size:'xl'});
		modalRef.componentInstance.name = 'World';
  }
}
