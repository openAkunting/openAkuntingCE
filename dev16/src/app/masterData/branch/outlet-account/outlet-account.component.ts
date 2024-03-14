import { HttpClient } from '@angular/common/http';
import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { LanguageService } from 'src/app/service/language.service';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-outlet-account',
  templateUrl: './outlet-account.component.html',
  styleUrls: ['./outlet-account.component.css']
})
export class OutletAccountComponent implements OnInit {
  @Output() newItemEvent = new EventEmitter<string>();
  @Input() outletId: any;
  @Input() branchId: any;
  branch : string = "";
  outlet : string = "";
  items: any = [];
  chartOfAccount: any = [];
  constructor(
    public activeModal: NgbActiveModal,
    private http: HttpClient,
    private configService: ConfigService,
    public lang: LanguageService
  ) { }

  ngOnInit(): void {
   
    this.httpGet();
  }

  httpGet() {
    this.http.get<any>(environment.api + "outlet/coa", {
      headers: this.configService.headers(),
      params : {
        outletId : this.outletId, 
      }
    }).subscribe(
      data => {
        console.log(data);
        this.items = data['items'];
        this.branch = data['branch'];
        this.outlet = data['outlet'];
        
      },
      error => {
        console.log(error);
      }
    )
  }

  onCheckBox(a: number, i: number, status: string) {
    this.items[a]['account'][i]['status'] = status;
  }

  onCheckBoxAll(a: number, status: string) {
    console.log(this.items[a]['account'].length);
    this.items[a]['checkBoxAll'] = status;
    for (let i = 0; i < this.items[a]['account'].length; i++) {
      this.items[a]['account'][i]['status'] = status;
    }
  }

  onSaveCoa(){
    const body = {
      items : this.items,
      outletId : this.outletId,
      branchId : this.branchId
    }
    this.http.post<any>(environment.api + "outlet/onSaveCoa",body, {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        console.log(data); 
        this.newItemEvent.emit();
      },
      error => {
        console.log(error);
      }
    )
  }
}
