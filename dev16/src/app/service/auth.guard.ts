import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, RouterStateSnapshot, Router, } from '@angular/router';
import { ConfigService } from './config.service';
import { Observable } from 'rxjs';
@Injectable({
  providedIn: 'root',
})
export class authGuard {
  constructor(public config: ConfigService, public router: Router) { }

  canActivate(
    next: ActivatedRouteSnapshot,
    state: RouterStateSnapshot
  ): Observable<boolean> | Promise<boolean> | boolean { 
    if (this.config.getToken()) {
      return true;
    } else {
      this.router.navigate(['relogin']) 
      return false;
    }

  }
}