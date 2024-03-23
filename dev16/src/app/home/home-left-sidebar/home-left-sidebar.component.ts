import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, NavigationEnd, Router } from '@angular/router';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { filter } from 'rxjs/operators';
import { HttpClient } from '@angular/common/http';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-home-left-sidebar',
  templateUrl: './home-left-sidebar.component.html',
  styleUrls: ['./home-left-sidebar.component.css']
})
export class HomeLeftSidebarComponent implements OnInit {
  date: any = new Date();
  thisMonth : any = new Date( this.date.getFullYear(), this.date.getMonth()+1, 1);
  startDate: string = this.date.getFullYear()+"-" + ("0" + (this.date.getMonth() + 1)).slice(-2) + "-01";
  endDate: string = this.thisMonth.toISOString().slice(0,10);
  
  activeRouteData: any;
  active :string = "";
  public isMasterData = true;
  public isGeneralLedger = true;
  public isReport = true;
  public isAP = true;
  public isAR = true;
  public isFixedAsset = true;
  
  constructor( 
    private router: Router,
    private configService: ConfigService,
    private modalService: NgbModal, 
    private http: HttpClient,
  ) { 
    
  }
  ngOnInit() {
    console.log("thisMonth",this.thisMonth.toISOString().slice(0,10));
    this.router.events.pipe(
      filter(event => event instanceof NavigationEnd)
    ).subscribe(() => {
      // Mengakses data yang didefinisikan dalam rute saat ini setiap kali rute berubah
      this.activeRouteData = this.router.routerState.root.firstChild; 
      this.active = this.activeRouteData.data['_value']['active'];
      this.tabsSave(this.activeRouteData.data['_value']);
    });
  }

  tabsSave(data : any){
    console.log(data);
    this.http.post<any>(environment.api+"user/tabs",data,{
      headers: this.configService.headers(),
    }).subscribe(
      data=>{
        console.log(data);
      }
    )
  }
  logout() {
    console.log("logout");
    this.configService.removeToken().subscribe(
      data => {
        console.log(data);
       // this.router.navigate(['relogin']);
        location.reload();
      },
      error => {
        console.log(error);
      }
    )
  }
}
