import { Component, OnInit } from '@angular/core'; 
import {  Router } from '@angular/router';
import { ConfigService } from 'src/app/service/config.service'; 

@Component({
  selector: 'app-relogin',
  templateUrl: './relogin.component.html',
  styleUrls: ['./relogin.component.css']
})
export class ReloginComponent {
  constructor( 
    private router : Router,
    private configService : ConfigService
  ){}
  
  logout(){
    console.log("logout");
    this.configService.removeToken().subscribe(
      data=>{
        console.log(data);
        this.router.navigate(['login']);
      },
      error=>{
        console.log(error);
      }
    )
  }
}
