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
import { JournalListReportComponent } from './report/journal-list-report/journal-list-report.component';
import { ProfitAndLossReportComponent } from './report/profit-and-loss-report/profit-and-loss-report.component';
import { BalanceSheetReportComponent } from './report/balance-sheet-report/balance-sheet-report.component';
import { AccountImportComponent } from './masterData/account-import/account-import.component';
import { LedgerComponent } from './generalLedger/ledger/ledger.component';
import { TrailBalanceComponent } from './report/trail-balance/trail-balance.component';

const routes: Routes = [
  { path: "", component: HomeComponent, data: { active: "home" }, },
  { path: "home", component: HomeComponent, data: { active: "home" }, },
  { path: "login", component: LoginComponent, data: { active: "Login" }, },
  { path: "relogin", component: ReloginComponent, data: { active: "relogin" }, },

  { path: "md/glp", component: GeneralLedgerParameterComponent, data: { active: "md/glp", tabs: true }, },
  { path: "md/an", component: AutoNumberComponent, data: { active: "md/an", tabs: true }, },
  { path: "md/a", component: AccountComponent, data: { active: "md/a", tabs: true }, },
  { path: "md/a/i", component: AccountImportComponent, data: { active: "md/a/i", tabs: true }, },

  { path: "md/at", component: AccountTypeComponent, data: { active: "md/at" }, },
  { path: "md/u", component: UserComponent, data: { active: "md/u" }, },
  { path: "md/ud", component: UserDetailComponent, data: { active: "md/ud" }, },
  { path: "md/ur", component: UserRoleComponent, data: { active: "md/ur" }, },

  { path: "gl/j", component: JournalComponent, data: { active: "gl/j", tabs: true, name: 'Journal', role: 'journal', controller: 'Journal' }, },
  { path: "gl/cb", component: JournalComponent, data: { active: "gl/cb", tabs: true, name: 'Cash Bank', role: 'cashBank', controller: 'CashBank' }, },
  { path: "gl/l", component: LedgerComponent, data: { active: "gl/l", tabs: true, name: 'Ledger', role: 'journal', controller: 'Journal' }, },

  { path: "tb", component: TrailBalanceComponent, data: { active: "tb", role: 'report', controller: 'Journal' }, },

  { path: "report/journalList", component: JournalListReportComponent, data: { active: "", role: 'report', controller: '' }, },
  { path: "report/profitAndLoss", component: ProfitAndLossReportComponent, data: { active: "", role: 'report', controller: '' }, },
  { path: "report/balanceSheet", component: BalanceSheetReportComponent, data: { active: "", role: 'report', controller: '' }, },



  { path: "**", component: NotFoundComponent, data: { active: "404" }, canActivate: [] },

];

@NgModule({
  imports: [RouterModule.forRoot(routes, { useHash: true })],
  exports: [RouterModule]
})
export class AppRoutingModule { }
