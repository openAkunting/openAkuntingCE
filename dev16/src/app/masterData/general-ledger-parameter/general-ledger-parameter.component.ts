import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { ConfigService } from 'src/app/service/config.service';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-general-ledger-parameter',
  templateUrl: './general-ledger-parameter.component.html',
  styleUrls: ['./general-ledger-parameter.component.css']
})
export class GeneralLedgerParameterComponent implements OnInit {
  note: string = "";
  disable : boolean = true;
  items : any = [];
  itemsOrigin : any = [];
  constructor(
    private http: HttpClient, 
    private configService: ConfigService,
  ) { }
  ngOnInit() {
    this.httpGet();
  }

  httpGet(){
    this.http.get<any>(environment.api+"GeneralLedgerParameter/index",{
      headers : this.configService.headers(),
    }).subscribe(
      data => {
        console.log(data);
        this.items = JSON.parse(JSON.stringify(data['items']));
        this.itemsOrigin = JSON.parse(JSON.stringify(data['items']));
      },
      error =>{
        console.log(error);
      }
    )
  }

  fnRollback(){
    this.items =  JSON.parse(JSON.stringify( this.itemsOrigin));
    this.disable = true;
  }

  fnUpdate(){
    const body = {
      items : this.items,
    }
    console.log(JSON.stringify(body));
    this.http.post<any>(environment.api+"GeneralLedgerParameter/update",body,{
      headers : this.configService.headers(),
    }).subscribe(
      data => {
        this.httpGet();
        console.log(data); 
        this.disable = true;
      },
      error =>{
        console.log(error);
      }
    )
  }
}
