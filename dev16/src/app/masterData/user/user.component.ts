import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { environment } from 'src/environments/environment';

export class Modal {
  constructor( 
    public name: string,
    public normalBalance: string,
    public position: string,
    public status: string,
  ) { }
}
@Component({
  selector: 'app-user',
  templateUrl: './user.component.html',
  styleUrls: ['./user.component.css']
})
export class UserComponent implements OnInit {
  note: string = "";
  disable: boolean = true;
  items: any = [];
  itemsOrigin: any = [];
  item: any;
  model: any = new Modal(  "", "", "", "");
  currencyOptions: any = { prefix: '', thousands: '.', decimal: ',', precision: 0, }
  constructor(
    private http: HttpClient,
    private configService: ConfigService,
    private modalService: NgbModal,
    private router : Router
  ) { }
  ngOnInit() {
    this.httpGet();
  }

  httpGet() {
    this.model = new Modal( "", "", "", "");
    this.http.get<any>(environment.api + "user/index", {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        console.log(data);
        this.items = JSON.parse(JSON.stringify(data['items']));
        this.itemsOrigin = JSON.parse(JSON.stringify(data['items']));
      },
      error => {
        console.log(error);
      }
    )
  }
  goToDetail(x:any){
    this.router.navigate(['md/ud'],{queryParams:{id:x.id}});
  }


  open(content: any) {
    this.modalService.open(content);
  }
 
}
