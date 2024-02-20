import { HttpClient } from '@angular/common/http';
import { Component, Input, OnInit } from '@angular/core';
import { NgbActiveModal, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-trail-balance-detail',
  templateUrl: './trail-balance-detail.component.html',
  styleUrls: ['./trail-balance-detail.component.css']
})
export class TrailBalanceDetailComponent implements OnInit {
  @Input() id: any;
  items: any = [];
  constructor(
    public activeModal: NgbActiveModal,
    private http: HttpClient,
    private configService: ConfigService,
  ) { }
  ngOnInit()  {
    this.httpGet();
  }
  httpGet() {
    this.http.get<any>(environment.api + "journal/index").subscribe(
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
