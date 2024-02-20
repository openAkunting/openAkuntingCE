import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { NgbModal, NgbModalConfig } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { LanguageService } from 'src/app/service/language.service';
import { environment } from 'src/environments/environment';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-ledger',
  templateUrl: './ledger.component.html',
  styleUrls: ['./ledger.component.css']
})
export class LedgerComponent implements OnInit {
  items: any = [];
  controller: string = "Journal";

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
 
  ngOnInit() {
    this.httpGet();
  }

  httpGet() {
    this.http.get<any>(environment.api + "Ledger/index").subscribe(
      data => {
        this.items = data['items'];
        console.log(data);
      },
      error => {
        console.log(error);
      }
    )
  }
}
