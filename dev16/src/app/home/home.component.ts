import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { ConfigService } from 'src/app/service/config.service';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent {

  startDate : string = "2024-01-01";
  endDate : string = "2024-01-31";

  constructor(
    private activeRouter : ActivatedRoute,
    private router : Router,
    private configService : ConfigService
  ){}
  
  logout(){
    console.log("logout");
    this.configService.removeToken().subscribe(
      data=>{
        console.log(data);
        this.router.navigate(['relogin']);
      },
      error=>{
        console.log(error);
      }
    )
  }
}
