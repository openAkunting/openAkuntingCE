import { Component, OnInit } from '@angular/core';
import { ConfigService } from './service/config.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit {
  login : boolean = false;
  constructor(
    public config: ConfigService,
  ) { }

  ngOnInit() {
    console.log(this.config.getToken())

    if( this.config.getToken() != '' && this.config.getToken() != null){
      this.login = true;
    }
  }
}
