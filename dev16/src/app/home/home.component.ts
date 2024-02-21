import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {

  startDate : string = "2024-01-01";
  endDate : string = "2024-01-31";

  constructor(
    private activeRouter : ActivatedRoute,
    private router : Router,
    private configService : ConfigService,
    private modalService: NgbModal
  ){}
  ngOnInit(): void {
   this.modalService.dismissAll();
  }
  
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
