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
    //console.log('create tabs ',this.config.account()['access'][0]['user']['email']);
    if (localStorage.getItem(this.config.account()['access'][0]['user']['email'] + ".tabs") == undefined) {
      localStorage.setItem(this.config.account()['access'][0]['user']['email'] + ".tabs", "");
    }
  }
  addTabs(path: string) {
    let updatTabs : any ;
    let saveTabs64: string | null = localStorage.getItem(this.config.account()['access'][0]['user']['email'] + ".tabs");
    if (saveTabs64) {
        updatTabs  = JSON.parse(atob(saveTabs64));
        //console.log(updatTabs);
        // Lanjutkan dengan pengolahan nilai updatTabs
    } else {
        console.error("Data 'saveTabs64' di localStorage adalah null.");
        this.init();
    }

    const data: any = this.router.config;
    if (path.charAt(0) === '/') {
      path = path.substring(1);
    }
   
    let index = data.findIndex((item: { path: string; }) => item.path === path);

    if (data[index].data.tabs === true) {
      data[index].data['id'] = Math.round(Math.random() * 10000);
      updatTabs.push(data[index].data);  
      localStorage.setItem(this.config.account()['access'][0]['user']['email'] + ".tabs", btoa(JSON.stringify(updatTabs)));   
      return data[index].data;
    } else {
      return false;
    }

  }

  getTabs(){
    let updatTabs : any ;
    let saveTabs64: string | null = localStorage.getItem(this.config.account()['access'][0]['user']['email'] + ".tabs");
    if (saveTabs64) {
        updatTabs  = JSON.parse(atob(saveTabs64));
        
        // Lanjutkan dengan pengolahan nilai updatTabs
    } else {
        console.error("Data 'saveTabs64' di localStorage adalah null.");
        this.init();
    }

    return updatTabs;
  }
}
