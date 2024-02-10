import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { environment } from 'src/environments/environment';

 
export class Modal {
  constructor( 
    public userRuleId: string,
    public email: string,
    public name: string,
    public sa: string, 
    public status: string,
  ) { }
}

@Component({
  selector: 'app-user-detail',
  templateUrl: './user-detail.component.html',
  styleUrls: ['./user-detail.component.css']
})
export class UserDetailComponent implements OnInit {
  note: string = "";
  disable: boolean = true;
  items: any = [];
  itemsOrigin: any = [];
  item: any;
  model: any = new Modal(  "", "", "", "","");
  userRole: any = [];
  constructor(
    private http: HttpClient,
    private configService: ConfigService,
    private modalService: NgbModal,
    private router : Router,
    private activeRoute : ActivatedRoute,
  ) { }
  ngOnInit() {
    this.httpGet();
  }

  httpGet() {
   
    this.http.get<any>(environment.api + "user/detail", {
      headers: this.configService.headers(),
      params : { 
        id : this.activeRoute.snapshot.queryParams['id'],
      }
    }).subscribe(
      data => {
        console.log(data);
        this.items = data['items'];
        this.userRole = data['userRole'];
        this.model = new Modal(
          data['items']['userRuleId'], 
          data['items']['email'], 
          data['items']['name'],
          data['items']['sa'],
          data['items']['status'],
          
        );
      },
      error => {
        console.log(error);
      }
    )
  }
  goToDetail(x:any){
    this.router.navigate(['md/ud'],{queryParams:{id:x.id}});
  }
  onCheckBox(x: any, status: string, name: string) {
    if (this.disable == false) {
      if (status == '0') {
        x[name] = '1';
      }
      else if (status == '1') {
        x[name] = '0';
      }
    }
  }

  fnRollback() {
    this.items = JSON.parse(JSON.stringify(this.itemsOrigin));
    this.disable = true;
  }

  

  onCheck(x: any, check: boolean) {
    x.checkBox = check;
    console.log(x.checkBox);
  }

  fnDelete() {
    if (confirm("Delete this check ?")) {
      const body = {
        items: this.items,
      }
      this.http.post<any>(environment.api + "user/userTypeDelete", body, {
        headers: this.configService.headers(),
      }).subscribe(
        data => {
          this.httpGet();
          console.log(data);
        },
        error => {
          console.log(error);
        }
      )
    }
  } 

  open(content: any) {
    this.modalService.open(content);
  }

  onSubmit() {
    this.note = 'Loading';
    const body = { 
      model: this.model,
      id : this.activeRoute.snapshot.queryParams['id'],
    }
    this.http.post<any>(environment.api + "user/userDetailUpdate", body, {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        this.note = 'Save';
        this.httpGet(); 
        console.log(data);
      },
      error => {
        console.log(error);
        this.note = error['error']['message'];
      }
    )
  }   
}
