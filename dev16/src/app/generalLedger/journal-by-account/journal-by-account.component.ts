import { HttpClient } from '@angular/common/http';
import { Component, Input, OnInit } from '@angular/core';
import { NgbActiveModal, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-journal-by-account',
  templateUrl: './journal-by-account.component.html',
  styleUrls: ['./journal-by-account.component.css']
})
export class JournalByAccountComponent  implements OnInit {
  @Input() id: any;
  @Input() title: any;
  @Input() startDate: any;
  @Input() endDate: any;
  
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
    let params  = {
        id : this.id,
        startDate: this.startDate['year'] + "-" + this.startDate['month'].toString().padStart(2, '0') + "-" + this.startDate['day'],
        endDate: this.endDate['year'] + "-" + this.endDate['month'].toString().padStart(2, '0') + "-" + this.endDate['day'],
    } 
  
    
    this.http.get<any>(environment.api + "journal/searchById",{
      headers: this.configService.headers(),
      params : params
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

