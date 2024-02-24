import { AfterViewInit, Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { environment } from 'src/environments/environment';
import { TabsService } from '../service/tabs.service';
declare var $: any;
@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit, AfterViewInit {
  date: any = new Date();
  startDate: string = "2024-" + ("0" + (this.date.getMonth() + 1)).slice(-2) + "-01";
  endDate: string = "2024-" + ("0" + (this.date.getMonth() + 1)).slice(-2) + "-29";
  history: any = [
    {
      name: 'Journal',
      id: 14232
    },
    {
      name: 'Chart Of Account',
      id: 53234
    }
  ];

  navigator: any = [];

  constructor(
    private activeRouter: ActivatedRoute,
    private router: Router,
    private configService: ConfigService,
    private modalService: NgbModal,
    private tabs: TabsService
  ) { }
  ngAfterViewInit(): void {
    this.jqSortable();
  }
  ngOnInit(): void {
    this.modalService.dismissAll();

    console.log(this.tabs.getTabs());

  }

  addTab(path: string) {
    this.tabs.addTabs(path);
  }

  jqSortable() {
    $(function () {
      $("#sortable").sortable({
        placeholder: "ui-state-highlight",
        handle: ".handle",
        update: function (event: any, ui: any) {

          const order: any[] = [];
          $(".handle").each((index: number, element: any) => {
            const itemId = $(element).data("id");
            order.push(itemId);
          });

          const body = {
            order: order,
          }
          console.log(body);
        }
      });

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
