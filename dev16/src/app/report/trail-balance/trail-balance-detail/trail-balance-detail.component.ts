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
    console.log(this.id);
  }
  httpGet() {
    this.http.get<any>(environment.api + "journal/searchById",{
      headers: this.configService.headers(),
      params : {
        id : this.id,
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
}
