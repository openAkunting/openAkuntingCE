import { Component, OnDestroy, OnInit } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { TabsService } from 'src/app/service/tabs.service';
import { Router, NavigationError, ActivationStart, NavigationEnd } from '@angular/router';
import { Subscription } from 'rxjs';

@Component({
  selector: 'app-home-tabs',
  templateUrl: './home-tabs.component.html',
  styleUrls: ['./home-tabs.component.css']
})
export class HomeTabsComponent implements OnInit, OnDestroy {
  tabHistory: any = [];
  active: string = "";
  private routerEventsSubscription: Subscription;

  constructor(
    private router: Router,
    private modalService: NgbModal,
    private tabs: TabsService
  ) {

    this.routerEventsSubscription = this.router.events.subscribe(event => {

      if (event instanceof ActivationStart) {
        // Handling NavigationStart event
        // console.log('URL berubah - NavigationStart:', event);
        const url = this.router.url; // Membaca URL saat ActivationStart terjadi
        // console.log('ActivationStart - URL:', url); 
      }
      else if (event instanceof NavigationEnd) {
        // Handling NavigationEnd event

        let str = event.url;
        if (str.charAt(0) === '/') {
          // Menghapus karakter '/' di awal string
          str = str.substring(1); 
        } 
        // Mencari posisi pertama tanda tanya untuk memotong string
        const index = str.indexOf('?'); 
        // Jika tanda tanya ditemukan, maka kita memotong string dari awal hingga sebelum tanda tanya
        const result = index !== -1 ? str.split('?')[0] : str;
       
        this.active = result;

        //console.log('Navigasi selesai - NavigationEnd:', event.url);
        if (event.url != '') {
          this.tabs.addTabs(event.url);
          this.tabHistory = this.tabs.getTabs();
        }
      }
      else if (event instanceof NavigationError) {
        // Handling NavigationError event
        console.error('Error dalam navigasi - NavigationError:', event.error);
      }
    });
  }
  ngOnDestroy(): void {
    // Menghentikan langganan ketika komponen dihancurkan
    this.routerEventsSubscription.unsubscribe();
  }


  ngOnInit(): void {
    this.modalService.dismissAll();
    this.tabHistory = this.tabs.getTabs();
    //console.log(this.tabs.getTabs()); 
  }

  addTab(path: string) {
    this.tabs.addTabs(path);
  }

  removeTab(data: any) {
    this.tabHistory = this.tabs.removeTabs(data);
  }


  navigate(item: any) {
    // Mengenkod parameter query secara manual
    const encodedQueryParams = encodeURIComponent(item.queryParams);

    // Navigasi menggunakan router.navigate()
    console.log(item.queryParams);
    this.router.navigate([item.active], { queryParams: item.queryParams });
  }
}
