import { HttpClient } from '@angular/common/http';
import { AfterViewInit, Component, OnInit } from '@angular/core';
import { NgbModal, NgbModalConfig } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { LanguageService } from 'src/app/service/language.service';
import { environment } from 'src/environments/environment';
import { JournalCreateComponent } from './journal-create/journal-create.component';
import { JournalDetailComponent } from './journal-detail/journal-detail.component';
import { ActivatedRoute, Router } from '@angular/router';

export class Ranges {
  constructor(
    public startDate: any,
    public endDate: any,
  ) { }
}


@Component({
  selector: 'app-journal',
  templateUrl: './journal.component.html',
  styleUrls: ['./journal.component.css']
})
export class JournalComponent implements OnInit, AfterViewInit {
  items: any = [];
  warning : string = "";
  controller: string = "Journal";
  range: any = new Ranges([], []);
  constructor(
    private http: HttpClient,
    private configService: ConfigService,
    private modalService: NgbModal,
    public lang: LanguageService,
    public router: Router,
    public activeRouter: ActivatedRoute,

    config: NgbModalConfig,
  ) {
    config.backdrop = 'static';
    config.keyboard = false;
  }
  ngAfterViewInit(): void {
   // this.open('journal');
  }

  filterDate() {
    this.httpGet();
  }
  onCheckRange() {
  
    const startDate = this.range.startDate['year'] +"-"+ this.range.startDate['month'].toString().padStart(2, '0') +"-"+ this.range.startDate['day'];
    const endDate = this.range.endDate['year'] +"-"+ this.range.endDate['month'].toString().padStart(2, '0') +"-"+ this.range.endDate['day'];
 
    const startDateInt = parseInt(this.range.startDate['year'] + this.range.startDate['month'].toString().padStart(2, '0') + this.range.startDate['day']);
    const endDateInt = parseInt(this.range.endDate['year'] + this.range.endDate['month'].toString().padStart(2, '0') + this.range.endDate['day']);
 
    // Parse string tanggal ke objek Date
    const startDateObj = new Date(startDate);
    const endDateObj = new Date(endDate);

    // Hitung perbedaan waktu dalam milidetik
    const timeDiff = endDateObj.getTime() - startDateObj.getTime();

    // Konversi milidetik ke hari
    const daysDiff = Math.floor(timeDiff / (1000 * 60 * 60 * 24));

 
    if (startDateInt > endDateInt) {
      this.range.endDate = this.range.startDate;
    }
    if(daysDiff > 100){
      this.warning = "Date range must be less than 100 days";
    }
    console.log("onCheckRange", this.range, startDate,startDateObj, daysDiff);
  }
  ngOnInit(): void {
    console.log(this.activeRouter.snapshot.data['controller']);
    this.controller = this.activeRouter.snapshot.data['controller'];
    const date = new Date();
    this.range.startDate = {
      year: date.getFullYear(),
      month: date.getMonth() + 1,
      day: date.getDate(),
    };
    this.range.endDate = {
      year: date.getFullYear(),
      month: date.getMonth() + 1,
      day: date.getDate(),
    };
    this.httpGet();
  }

  httpGet() {
    this.http.get<any>(environment.api + "Journal/index", {
      headers: this.configService.headers(),
      params: {
        startDate: this.range.startDate['year'] + "-" + this.range.startDate['month'].toString().padStart(2, '0') + "-" + this.range.startDate['day'],
        endDate: this.range.endDate['year'] + "-" + this.range.endDate['month'].toString().padStart(2, '0') + "-" + this.range.endDate['day'],
      }
    }).subscribe(
      data => {
        this.items = data['items'];
        console.log(data);
      },
      error => {
        console.log(error);
      }
    )
  }


  search:string = "";
  searchByAccount() {
    const filteredData :any= [];
    let  i = 0;
    // Iterasi setiap objek di dalam array data
    this.items.forEach((entry: { journal: any[]; id: any; journalDate: any; }) => {
      // Iterasi setiap jurnal di dalam objek
      let n = 0;
      entry.journal.forEach(journal => {
       
        // Jika accountId cocok, tambahkan ke dalam array hasil
        if (journal.account == this.search || journal.accountId == this.search) {
          console.log(journal.account, this.search);
          filteredData.push({
            indexItem: i,
            indexJournal : n,
            // accountId: journal.accountId,
            // debit: journal.debit,
            // credit: journal.credit,
            search: journal.account
          });
        }
        n++;
      });
      i++;
    });
    console.log(filteredData)
    return filteredData;

  }


  open(component: string) {
    if (component == 'journal') {
      const modalRef = this.modalService.open(JournalCreateComponent, { size: 'xl' });
      modalRef.componentInstance.controller = this.controller;
      modalRef.componentInstance.newItemEvent.subscribe(() => {
        this.httpGet();
      });
    }

  }

  detail(item: any) {
    const modalRef = this.modalService.open(JournalDetailComponent, { size: 'xl' });
    modalRef.componentInstance.controller = this.controller;
    modalRef.componentInstance.id = item.id;
    modalRef.componentInstance.newItemEvent.subscribe(() => {
      this.httpGet();
    });
  }
}
