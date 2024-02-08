import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { ConfigService } from 'src/app/service/config.service';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-account',
  templateUrl: './account.component.html',
  styleUrls: ['./account.component.css']
})
export class AccountComponent implements OnInit {
  note: string = "";
  disable: boolean = true;
  items: any = [];
  itemsOrigin: any = [];
  constructor(
    private http: HttpClient,
    private configService: ConfigService,
  ) { }
  ngOnInit() {
    this.httpGet();
  }

  httpGet() {
    this.http.get<any>(environment.api + "account/index", {
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

  fnUpdate() {
    const body = {
      items: this.items,
    }
    this.http.post<any>(environment.api + "account/masterAccountUpdateAll", body, {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        this.itemsOrigin = JSON.parse(JSON.stringify(this.items));
        console.log(data);
      },
      error => {
        console.log(error);
      }
    )
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
      this.http.post<any>(environment.api + "account/masterAccountDelete", body, {
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

  open(){
    
  }
}
