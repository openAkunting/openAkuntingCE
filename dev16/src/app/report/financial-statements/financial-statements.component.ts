import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core'; 
import { ConfigService } from 'src/app/service/config.service';
import { LanguageService } from 'src/app/service/language.service';
import { environment } from 'src/environments/environment'; 
import { ActivatedRoute, Router } from '@angular/router';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { JournalByAccountComponent } from 'src/app/generalLedger/journal-by-account/journal-by-account.component';
import { FunctionService } from 'src/app/service/function.service';
import { DateRanges } from 'src/app/masterData/global-class'; 

@Component({
  selector: 'app-financial-statements',
  templateUrl: './financial-statements.component.html',
  styleUrls: ['./financial-statements.component.css']
})
export class FinancialStatementsComponent implements OnInit {
  items : any = [];
  params : any = [];
  total : any = [];
  range: any = new DateRanges([], []);
  listMonth : any = [];
  totalObj : any = [];
  width : number = 0;
  controller: string = "";
  constructor(
    private http: HttpClient,
    private configService: ConfigService, 
    public lang: LanguageService,
    public router: Router,
    public activeRouter: ActivatedRoute,  
    private modalService: NgbModal, 
    public functionService: FunctionService,
  ) { 
  }

  ngOnInit(): void {
    this.params = this.activeRouter.snapshot.queryParams;
    this.controller = this.activeRouter.snapshot.data['controller'];
    console.log(  this.controller);
    const date = new Date();
    let  [year, month, day] = this.params['startDate'].split("-").map(Number);
    this.range.startDate = {
      year:year,
      month: month,
      day: day,
    };
    [year, month, day] = this.params['endDate'].split("-").map(Number);
    this.range.endDate = {
      year:year,
      month: month,
      day: day,
    };
    this.httpGet();
  }


  httpGet(){
    this.width = 340;
   
    this.http.get<any>(environment.api+"Reports/"+this.controller,{
      headers:this.configService.headers(),
      params: {
        startDate: this.range.startDate['year'] + "-" + this.range.startDate['month'].toString().padStart(2, '0') + "-" + this.range.startDate['day'],
        endDate: this.range.endDate['year'] + "-" + this.range.endDate['month'].toString().padStart(2, '0') + "-" + this.range.endDate['day'],
      }
    }).subscribe(
      data=>{
        this.items = data['data'];
        this.total = data['total'];
        this.width += data['total'].length * 130;
        this.listMonth = data['listMonth'];
        this.totalObj = data['total'];
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

  getLevel(id: string, data: any, level: number = 0): number {
    for (const item of data) {
      if (item.id === id) {
        if (item.parentId == null || item.parentId == '0') {
          return level; // Jika ID merupakan root
        } else {
          return this.getLevel(item.parentId, data, level + 1); // Rekursif untuk mencari parent
        }
      }
    }
    return -1; // Jika ID tidak ditemukan
  }

  
  detail(id:string, item:any){
    console.log(item);
    const modalRef = this.modalService.open(JournalByAccountComponent, {size:'xl'});
		modalRef.componentInstance.id = id;
    modalRef.componentInstance.startDate= item.startDate;
    modalRef.componentInstance.endDate= item.endDate;
    
    modalRef.componentInstance.title = 'Balance Sheet '+this.params['startDate']+" "+this.params['endDate'];
    
  }
 

  onCheckRange(){
    const dayDeff : number = this.functionService.onCheckRange(this.range.startDate, this.range.endDate)['daysDiff'];
    if (dayDeff < 0) {
      this.range.endDate = this.range.startDate;
    }
    
  }

  filterDate(){
    this.httpGet();
  }

  saveToJson(){
    const body = {
      controller : this.controller,
      startDate: this.range.startDate['year'] + "-" + this.range.startDate['month'].toString().padStart(2, '0') + "-" + this.range.startDate['day'],
      endDate: this.range.endDate['year'] + "-" + this.range.endDate['month'].toString().padStart(2, '0') + "-" + this.range.endDate['day'],

    }
    this.http.post<any>(environment.api+"Reports/saveToJson", body,{
      headers:this.configService.headers(), 
    }).subscribe(
      data=>{ 
        console.log(data);
      },
      error=>{
        console.log(error);
      }
    )
  }
}
