import { Injectable } from '@angular/core';
import { HttpHeaders } from '@angular/common/http';
import { Observable, of } from 'rxjs'; 

@Injectable({
  providedIn: 'root'
})
export class ConfigService {
  private tokenKey: string = "app1.openAkunting.com";
  private jtiKey: string = "jti.openAkunting.com";
  
  constructor() { }

  setToken(data: any): Observable<boolean> {
    try {
      localStorage.setItem(this.tokenKey, data['authorization']);
      localStorage.setItem(this.jtiKey, data['jti']);
      
      return of(true); // Mengembalikan Observable yang mengirimkan nilai boolean true
    } catch (error) {
      return of(false); // Mengembalikan Observable yang mengirimkan nilai boolean false jika terjadi kesalahan
    }
  }

  setGlobalToken(key : string, value: string): Observable<boolean> {
    try {
      localStorage.setItem(key, value);
      return of(true); // Mengembalikan Observable yang mengirimkan nilai boolean true
    } catch (error) {
      return of(false); // Mengembalikan Observable yang mengirimkan nilai boolean false jika terjadi kesalahan
    }
  }


  account() {
    const jwtObj = this.getToken().split("."); 
    return JSON.parse(atob(jwtObj[1]));
  }



  getToken(): any {
    return localStorage.getItem(this.tokenKey);
  }

  jti(){
    return this.account()['jti'];
  }
  headers() {
    return new HttpHeaders({
      'Content-Type': 'application/json',
      'Token': this.getToken(),
    });
  }

  removeToken(): Observable<boolean> {
    try {
      localStorage.removeItem(this.tokenKey);
      return of(true); // Mengembalikan Observable yang mengirimkan nilai boolean true
    } catch (error) {
      return of(false); // Mengembalikan Observable yang mengirimkan nilai boolean false jika terjadi kesalahan
    }
  }

}