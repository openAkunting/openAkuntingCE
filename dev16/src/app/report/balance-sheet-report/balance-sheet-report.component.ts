import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core'; 
import { ConfigService } from 'src/app/service/config.service';
import { LanguageService } from 'src/app/service/language.service';
import { environment } from 'src/environments/environment'; 
import { ActivatedRoute, Router } from '@angular/router';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { JournalByAccountComponent } from 'src/app/generalLedger/journal-by-account/journal-by-account.component';

@Component({
  selector: 'app-balance-sheet-report',
  templateUrl: './balance-sheet-report.component.html',
  styleUrls: ['./balance-sheet-report.component.css']
})
export class BalanceSheetReportComponent implements OnInit {
  items : any = [];
  params : any = [];
  total : any = [];
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
    this.http.get<any>(environment.api+"Reports/BalanceSheet",{
      headers:this.configService.headers(),
      params : body
    }).subscribe(
      data=>{
        this.items = data['data'];
        this.total = data['total'];
        console.log(data);
      },
      error=>{
        console.log(error);
      }
    )
  }


  addLevel(account:any){
    let tab = "";
    for(let i = 0; i < account['level'] ; i++){
      tab += "&nbsp;";
    } 
    tab += "<code>"+account['id']+"</code>"+' : ';

    return tab+account['name'] ;
  }
  detail(x:any){
    console.log(x);
    const modalRef = this.modalService.open(JournalByAccountComponent, {size:'xl'});
		modalRef.componentInstance.id = x.id;
    modalRef.componentInstance.title = 'Profit And Loss '+this.params['startDate']+" "+this.params['endDate'];
    
  }
}
