import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { environment } from 'src/environments/environment';

export class Modal {
  constructor(
    public id: number,
    public name: string,
    public normalBalance: string,
    public position: string,
    public status: string,
  ) { }
}
@Component({
  selector: 'app-account-type',
  templateUrl: './account-type.component.html',
  styleUrls: ['./account-type.component.css']
})
export class AccountTypeComponent implements OnInit {
  note: string = "";
  disable: boolean = true;
  items: any = [];
  itemsOrigin: any = [];
  item: any;
  model: any = new Modal(0, "", "", "", "");
  currencyOptions: any = { prefix: '', thousands: '.', decimal: ',', precision: 0, }
  constructor(
    private http: HttpClient,
    private configService: ConfigService,
    private modalService: NgbModal
  ) { }
  ngOnInit() {
    this.httpGet();
  }

  httpGet() {
    this.model = new Modal(0, "", "", "", "");
    this.http.get<any>(environment.api + "account/accountType", {
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
    this.http.post<any>(environment.api + "account/accountTypeUpdate", body, {
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
      this.http.post<any>(environment.api + "account/accountTypeDelete", body, {
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
      model: this.model
    }
    this.http.post<any>(environment.api + "account/accountTypeInsert", body, {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        this.note = 'Save';
        this.httpGet();
        this.modalService.dismissAll();
        console.log(data);
      },
      error => {
        console.log(error);
        this.note = error['error']['message'];
      }
    )
  } 
}
