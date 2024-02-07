import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { HomeComponent } from './home/home.component';
import { NotFoundComponent } from './not-found/not-found.component';
import { LoginComponent } from './login/login.component';
import { ReloginComponent } from './login/relogin/relogin.component';

const routes: Routes = [
  { path: "", component: HomeComponent, data: { active: "home" }, },
  { path: "home", component: HomeComponent, data: { active: "home" }, }, 
  { path: "login", component: LoginComponent, data: { active: "Login" }, },
  { path: "relogin", component: ReloginComponent, data: { active: "relogin" }, },
  
  { path: "**", component: NotFoundComponent, data: { active: "404" },  canActivate:[]  }, 
 
];

@NgModule({
  imports: [RouterModule.forRoot(routes, { useHash: true })],
  exports: [RouterModule]
})
export class AppRoutingModule { }
