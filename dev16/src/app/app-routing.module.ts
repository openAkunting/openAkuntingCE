import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { HomeComponent } from './home/home.component';
import { NotFoundComponent } from './not-found/not-found.component';
import { LoginComponent } from './login/login.component';
import { ReloginComponent } from './login/relogin/relogin.component';
import { GeneralLedgerParameterComponent } from './masterData/general-ledger-parameter/general-ledger-parameter.component';
import { AutoNumberComponent } from './masterData/auto-number/auto-number.component';
import { AccountComponent } from './masterData/account/account.component';
import { AccountTypeComponent } from './masterData/account-type/account-type.component';
import { UserComponent } from './masterData/user/user.component';
import { UserDetailComponent } from './masterData/user/user-detail/user-detail.component';
import { UserRoleComponent } from './masterData/user/user-role/user-role.component';
import { JournalComponent } from './generalLedger/journal/journal.component';
import { CashBankComponent } from './generalLedger/cash-bank/cash-bank.component';
import { RecurringJournalComponent } from './generalLedger/journal/recurring-journal/recurring-journal.component';
 
const routes: Routes = [
  { path: "", component: HomeComponent, data: { active: "home" }, },
  { path: "home", component: HomeComponent, data: { active: "home" }, }, 
  { path: "login", component: LoginComponent, data: { active: "Login" }, },
  { path: "relogin", component: ReloginComponent, data: { active: "relogin" }, },
   
  { path: "md/glp", component: GeneralLedgerParameterComponent, data: { active: "md/glp" }, },
  { path: "md/an", component: AutoNumberComponent, data: { active: "md/an" }, },
  { path: "md/a", component: AccountComponent, data: { active: "md/a" }, },
  { path: "md/at", component: AccountTypeComponent, data: { active: "md/at" }, },
  { path: "md/u", component: UserComponent, data: { active: "md/u" }, },
  { path: "md/ud", component: UserDetailComponent, data: { active: "md/ud" }, },
  { path: "md/ur", component: UserRoleComponent, data: { active: "md/ur" }, },
   
  { path: "gl/j", component: JournalComponent, data: { active: "gl/j", role :'journal' }, },
  { path: "gl/rj", component: RecurringJournalComponent, data: { active: "gl/rj", role :'journal' }, },
  { path: "gl/cb", component: CashBankComponent, data: { active: "gl/cb", role :'cash_bank' }, },
    

  { path: "**", component: NotFoundComponent, data: { active: "404" },  canActivate:[]  }, 
 
];

@NgModule({
  imports: [RouterModule.forRoot(routes, { useHash: true })],
  exports: [RouterModule]
})
export class AppRoutingModule { }
