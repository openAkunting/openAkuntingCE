import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core'; 
import { ConfigService } from 'src/app/service/config.service';
import { LanguageService } from 'src/app/service/language.service';
import { environment } from 'src/environments/environment'; 
import { ActivatedRoute, Router } from '@angular/router';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap'; 
import { JournalByAccountComponent } from 'src/app/generalLedger/journal-by-account/journal-by-account.component';

@Component({
  selector: 'app-trail-balance',
  templateUrl: './trail-balance.component.html',
  styleUrls: ['./trail-balance.component.css']
})
export class TrailBalanceComponent implements OnInit {
  items : any = [];
  params : any = [];
  balance : any = [];
  constructor(
    private http: HttpClient,
    private configService: ConfigService, 
    public lang: LanguageService,
    public router: Router,
    public activeRouter: ActivatedRoute,  
    private modalService: NgbModal
  ) { 
  }

  ngOnInit(): void {
    this.params = this.activeRouter.snapshot.queryParams;
    console.log(this.params);
    this.httpGet();
  }


  httpGet(){
    const body = {
      startDate : this.params['startDate'],
      endDate : this.params['endDate'],
      //typeTransaction : 'journal',
    //  branchId : '',
    }
    this.http.get<any>(environment.api+"TrialBalance/index",{
      headers:this.configService.headers(),
      params : body
    }).subscribe(
      data=>{
        this.balance = data['balance'];
        this.items = data['items'];
        console.log(data);
      },
      error=>{
        console.log(error);
      }
    )
  }

  detail(x:any){
    console.log(x);
    const modalRef = this.modalService.open(JournalByAccountComponent, {size:'xl'});
		modalRef.componentInstance.id = x.id;
    modalRef.componentInstance.title = 'Trial Balance';
    
  }

}
