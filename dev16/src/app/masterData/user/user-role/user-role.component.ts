import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { environment } from 'src/environments/environment';
import { UserRoleAccessComponent } from '../user-role-access/user-role-access.component';
export class Modal {
  constructor( 
    public name: string,  
  ) { }
}
@Component({
  selector: 'app-user-role',
  templateUrl: './user-role.component.html',
  styleUrls: ['./user-role.component.css']
})
export class UserRoleComponent implements OnInit {
  note: string = "";
  disable: boolean = true;
  items: any = [];
  itemsOrigin: any = [];
  item: any;
  model: any = new Modal(  "", ); 
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
    this.model = new Modal( "",);
    this.http.get<any>(environment.api + "user/userRole", {
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


  open(x:any) {
    const modalRef = this.modalService.open(UserRoleAccessComponent, {size:'lg'});
		modalRef.componentInstance.id = x.id;
  }
 
}
