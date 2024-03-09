import { Component } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';

@Component({
  selector: 'app-home-left-sidebar',
  templateUrl: './home-left-sidebar.component.html',
  styleUrls: ['./home-left-sidebar.component.css']
})
export class HomeLeftSidebarComponent {
  date: any = new Date();
  startDate: string = "2024-" + ("0" + (this.date.getMonth() + 1)).slice(-2) + "-01";
  endDate: string = "2024-" + ("0" + (this.date.getMonth() + 1)).slice(-2) + "-29";
  public isCollapsed = false;
  constructor(
    private activeRouter: ActivatedRoute,
    private router: Router,
    private configService: ConfigService,
    private modalService: NgbModal, 
  ) { }
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
