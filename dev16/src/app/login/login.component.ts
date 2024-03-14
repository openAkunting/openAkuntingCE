import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from 'src/environments/environment';
import { Router } from '@angular/router';
import { ConfigService } from 'src/app/service/config.service';
import * as CryptoJS from 'crypto-js';

export class Login {
  constructor(
    public email: string,
    public password: string,
  ) { }
}

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit{
  model: any = new Login('', '');
  loading: boolean = false;
  api: string = environment.api;
  note: string = "";
  constructor(
    private http: HttpClient,
    private router: Router,
    private configService: ConfigService,
  ) { }
  ngOnInit(): void {
    if(this.configService.getToken()){
      this.router.navigate(['home']);
    }
  }

  onSubmit() {
    this.loading = true;
    this.note = "Loading..!";
    const hash = CryptoJS.MD5(CryptoJS.enc.Latin1.parse(this.model['password']));
    const md5 = hash.toString(CryptoJS.enc.Hex);
 
    const body = {
      email : this.model['email'],  
      password :   md5,
     // tokenName  :  environment.tokenName,
    }
    console.log(body);
    this.http.post<any>(this.api + 'auth/signin', body).subscribe(
      data => {
        this.loading = false;
        console.log(data);
        if (data['error'] !== true) { 
          this.note = "Login Success ";
          this.configService.setToken(data).subscribe(
            data => { 
              if (data) {
                console.log('Token set successfully');
                //this.router.navigate(['home']).then(
               //   ()=>{
                    location.reload();
                    this.router.navigate(['home'])
                //  }
               // );
              } else {
                console.error('Failed to set token');
              }
           
            });
        
        } else {
          this.note = "Login fail!";
        }
      },
      e => {
        console.log(e);
        this.note = "Error Server!";
      },
    );


  }

}