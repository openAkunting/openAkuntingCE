import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, NavigationEnd, Router } from '@angular/router';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { filter } from 'rxjs/operators';

@Component({
  selector: 'app-home-left-sidebar',
  templateUrl: './home-left-sidebar.component.html',
  styleUrls: ['./home-left-sidebar.component.css']
})
export class HomeLeftSidebarComponent implements OnInit {
  date: any = new Date();
  startDate: string = "2024-" + ("0" + (this.date.getMonth() + 1)).slice(-2) + "-01";
  endDate: string = "2024-" + ("0" + (this.date.getMonth() + 1)).slice(-2) + "-29";
  activeRouteData: any;
  active :string = "";
  public isCollapsed = false;
  constructor( 
    private router: Router,
    private configService: ConfigService,
    private modalService: NgbModal,
  ) { 
    
  }
  ngOnInit() {

    this.router.events.pipe(
      filter(event => event instanceof NavigationEnd)
    ).subscribe(() => {
      // Mengakses data yang didefinisikan dalam rute saat ini setiap kali rute berubah
      this.activeRouteData = this.router.routerState.root.firstChild;
      console.log(this.activeRouteData.data['_value']); // Output: { active: "home" }
      this.active = this.activeRouteData.data['_value']['active'];
    });
  }
  logout() {
    console.log("logout");
    this.configService.removeToken().subscribe(
      data => {
        console.log(data);
        this.router.navigate(['relogin']);
      },
      error => {
        console.log(error);
      }
    )
  }
}
