import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { HomeComponent } from './home/home.component';
import { NotFoundComponent } from './not-found/not-found.component';
import { LoginComponent } from './login/login.component';
import { ReloginComponent } from './login/relogin/relogin.component';
import { GeneralLedgerParameterComponent } from './masterData/general-ledger-parameter/general-ledger-parameter.component';
import { AutoNumberComponent } from './masterData/auto-number/auto-number.component';
import { AccountComponent } from './masterData/account/account.component';
import { AccountTypeComponent } from './masterData/account/account-type/account-type.component';
import { UserComponent } from './masterData/user/user.component';
import { UserDetailComponent } from './masterData/user/user-detail/user-detail.component';
import { UserRoleComponent } from './masterData/user/user-role/user-role.component';
import { JournalComponent } from './generalLedger/journal/journal.component';
import { JournalListReportComponent } from './report/journal-list-report/journal-list-report.component';
import { AccountImportComponent } from './masterData/account/account-import/account-import.component';
import { LedgerComponent } from './generalLedger/ledger/ledger.component';
import { TrailBalanceComponent } from './report/trail-balance/trail-balance.component';
import { authGuard } from './service/auth.guard';
import { BranchComponent } from './masterData/branch/branch.component';
import { FinancialStatementsComponent } from './report/financial-statements/financial-statements.component';
import { TestComponent } from './test/test.component';
import { ApInvoiceComponent } from './accountPayable/ap-invoice/ap-invoice.component';
import { ApInvoiceDetailComponent } from './accountPayable/ap-invoice/ap-invoice-detail/ap-invoice-detail.component';
import { ApPaymentComponent } from './accountPayable/ap-payment/ap-payment.component';

const routes: Routes = [
  { path: "", component: HomeComponent, data: { active: "home" }, canActivate: [authGuard] },
  { path: "home", component: HomeComponent, data: { active: "home" }, canActivate: [authGuard] },
  { path: "login", component: LoginComponent, data: { active: "Login" }, },
  { path: "relogin", component: ReloginComponent, data: { active: "relogin" }, },

  { path: "md/glp", component: GeneralLedgerParameterComponent, data: { active: "md/glp",  name: 'GL Parameter', role:'masterData' }, canActivate: [authGuard] },
  { path: "md/an", component: AutoNumberComponent, data: { active: "md/an", tabs: true, name: 'Auto Number', role:'account' }, canActivate: [authGuard] },
  { path: "md/a", component: AccountComponent, data: { active: "md/a", tabs: true, name: 'Chart Of Account', role:'account' }, canActivate: [authGuard] },
  { path: "md/a/i", component: AccountImportComponent, data: { active: "md/a/i" }, canActivate: [authGuard] },
  { path: "md/branch", component: BranchComponent, data: { active: "md/branch", name: 'Branch & Outlet', role: 'masterData', tabs: true }, canActivate: [authGuard] },



  { path: "md/at", component: AccountTypeComponent, data: { active: "md/at" }, canActivate: [authGuard] },
  { path: "md/u", component: UserComponent, data: { active: "md/u" }, canActivate: [authGuard] },
  { path: "md/ud", component: UserDetailComponent, data: { active: "md/ud" }, canActivate: [authGuard] },
  { path: "md/ur", component: UserRoleComponent, data: { active: "md/ur" }, canActivate: [authGuard] },

  { path: "gl/jurnal", component: JournalComponent, data: { active: "gl/jurnal", tabs: true, name: 'Journal', role: 'journal' }, canActivate: [authGuard] },
  { path: "gl/cashBank", component: JournalComponent, data: { active: "gl/cashBank", tabs: true, name: 'Cash Bank', role: 'journal' }, canActivate: [authGuard] },
  { path: "gl/ledger", component: LedgerComponent, data: { active: "gl/ledger", tabs: true, name: 'Ledger', role: 'journal' }, canActivate: [authGuard] },
  { path: "trialBalance", component: TrailBalanceComponent, data: { active: "trialBalance", tabs: true, name: 'Trial Balance', role: 'report' }, canActivate: [authGuard] },


  { path: "ap/invoice", component: ApInvoiceComponent, data: { active: "ap/invoice", tabs: true, name: 'AP Invoice', role: 'ap' }, canActivate: [authGuard] },
  { path: "ap/invoice/detail", component: ApInvoiceDetailComponent, data: { active: "ap/invoice/detail", tabs: true, name: 'AP Invoice Detail', role: 'ap' }, canActivate: [authGuard] },
  { path: "ap/payment", component: ApPaymentComponent, data: { active: "ap/payment", tabs: true, name: 'AP payment', role: 'ap' }, canActivate: [authGuard] },



  { path: "report/journalList", component: JournalListReportComponent, data: { active: "report/journalList", tabs: true, name: 'Journal Report', role: 'report', }, canActivate: [authGuard] },
  { path: "report/profitAndLoss", component: FinancialStatementsComponent, data: { controller: "profitAndLoss", active: "report/profitAndLoss", tabs: true, name: 'Profit And Loss', role: 'report', }, canActivate: [authGuard] },
  { path: "report/balanceSheet", component: FinancialStatementsComponent, data: { controller: "balanceSheet", active: "report/balanceSheet", tabs: true, name: 'Balance Sheet', role: 'report', }, canActivate: [authGuard] },
  { path: "test", component: TestComponent, data: {}, canActivate: [authGuard] },



  { path: "**", component: NotFoundComponent, data: { active: "404" }, canActivate: [] },

];

@NgModule({
  imports: [RouterModule.forRoot(routes, { useHash: true })],
  exports: [RouterModule]
})
export class AppRoutingModule { }
