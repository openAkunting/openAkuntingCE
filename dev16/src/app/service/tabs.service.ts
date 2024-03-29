import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { ConfigService } from './config.service';

@Injectable({
  providedIn: 'root'
})
export class TabsService {

  constructor(
    private router: Router,
    private config: ConfigService
  ) { }

  init() {
    console.log('create tabs ', this.config.account()['access'][0]['user']['email']);
    let saveTabs64: string | null = localStorage.getItem(this.config.account()['access'][0]['user']['email'] + ".tabs");
    if (typeof saveTabs64 !== "string") {
      localStorage.setItem(this.config.account()['access'][0]['user']['email'] + ".tabs", "W10=")
    }
  }
  addTabs(queryParams: string) {
    const splittedArray = queryParams.split('?');
    //console.log(splittedArray);
    let path = splittedArray[0];

    let tabs: any;
    let saveTabs64: string | null = localStorage.getItem(this.config.account()['access'][0]['user']['email'] + ".tabs");
    if (typeof saveTabs64 === "string") {

      if (saveTabs64 != null) {
        tabs = JSON.parse(atob(saveTabs64));
      } else {
        tabs = [];
      }
    } else {
      this.init();
    }

    const data: any = this.router.config;
    //console.log('tabs', tabs, path);
    // console.log('data',data);  

    if (path.charAt(0) === '/') {
      path = path.substring(1);
    }



    let index = data.findIndex((item: { path: string; }) => item.path === path);
    //console.log("data[index] :", data[index]);
    if (data[index] !== undefined && data[index].data.tabs === true) {
      data[index].data['id'] = Math.round(Math.random() * 10000);

      let addPush: boolean = true;
      for (let i = 0; i < tabs.length; i++) {
        if (tabs[i]['active'] == data[index]['data']['active']) {
          addPush = false;
        }
      }

      if (addPush == true) {
        console.log(data[index].data);

        data[index].data['queryParamsSting'] = splittedArray[1];

        if (splittedArray[1] !== undefined) {
 
          const pairs = splittedArray[1].split('&'); 
          // Objek kosong untuk menyimpan pasangan nama dan nilai
          const obj : any = {};

          pairs.forEach(pair => {
            // Membagi setiap pasangan nama dan nilai berdasarkan '='
            const keyValue = pair.split('='); 
            // Menyimpan pasangan nama dan nilai ke dalam objek
            obj[keyValue[0]] = keyValue[1];
          });

          data[index].data['queryParams'] = obj;
        }


        tabs.push(data[index].data);
        localStorage.setItem(this.config.account()['access'][0]['user']['email'] + ".tabs", btoa(JSON.stringify(tabs)));
      }

      return data[index].data;
    } else {
      return false;
    }

  }

  getTabs() {
    let tabs: any;
    let saveTabs64: string | null = localStorage.getItem(this.config.account()['access'][0]['user']['email'] + ".tabs");
    if (saveTabs64) {
      tabs = JSON.parse(atob(saveTabs64));
      // Lanjutkan dengan pengolahan nilai tabs
    } else {
      this.init();
    }

    return tabs;
  }


  removeTabs(data: any) {
    console.log(this.getTabs())
    let index = this.getTabs().findIndex((item: { active: string; }) => item.active === data.active);
    let tabs = this.getTabs();
    if (index > -1) { // only splice array when item is found  
      tabs.splice(index, 1); // 2nd parameter means remove one item only 
      localStorage.setItem(this.config.account()['access'][0]['user']['email'] + ".tabs", btoa(JSON.stringify(tabs)));

    }
    return tabs;
  }
}
