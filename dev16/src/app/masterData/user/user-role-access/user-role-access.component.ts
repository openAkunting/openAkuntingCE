import { HttpClient } from '@angular/common/http';
import { Component, Input, OnInit } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-user-role-access',
  templateUrl: './user-role-access.component.html',
  styleUrls: ['./user-role-access.component.css']
})
export class UserRoleAccessComponent implements OnInit {
  @Input() id: any;
  items: any = [];
  itemsOrigin: any = [];
  constructor(
    public activeModal: NgbActiveModal,
    private http: HttpClient,
    private configService: ConfigService,
  ) { }

  ngOnInit() {
    this.httpGet();
  }

  httpGet() {

    this.http.get<any>(environment.api + "user/userRoleAccess", {
      headers: this.configService.headers(),
      params: {
        id: this.id
      }
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

  onCheckBox(x: any, status: string, name: string) {
    if (status == '0') {
      x[name] = '1';
    }
    else if (status == '1') {
      x[name] = '0';
    }
  }

  update() {
    const body = {
      items: this.items,
      id: this.id,
    }
    this.http.post<any>(environment.api + "user/userRoleAccessUpdate", body, {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        console.log(data);
      },
      error => {
        console.log(error);
      }
    )
  }
  reload() { 
    const body = { 
      id: this.id,
    }
    this.http.post<any>(environment.api + "user/userRoleAccessReload", body, {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        console.log(data);
        this.httpGet();
      },
      error => {
        console.log(error);
      }
    )
  }
}
