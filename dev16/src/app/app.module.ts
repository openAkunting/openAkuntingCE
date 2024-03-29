import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { NgxCurrencyDirective } from "ngx-currency";

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { HomeComponent } from './home/home.component';
import { NotFoundComponent } from './not-found/not-found.component';
import { LoginComponent } from './login/login.component';
import { ReloginComponent } from './login/relogin/relogin.component';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { CommonModule } from '@angular/common';
import { GeneralLedgerParameterComponent } from './masterData/general-ledger-parameter/general-ledger-parameter.component';
import { AutoNumberComponent } from './masterData/auto-number/auto-number.component';
import { AccountComponent } from './masterData/account/account.component';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { AccountTypeComponent } from './masterData/account/account-type/account-type.component';
import { UserComponent } from './masterData/user/user.component';
import { UserDetailComponent } from './masterData/user/user-detail/user-detail.component';
import { UserRoleComponent } from './masterData/user/user-role/user-role.component';
import { UserRoleAccessComponent } from './masterData/user/user-role-access/user-role-access.component';
import { LanguageService } from './service/language.service';
import { BranchComponent } from './masterData/branch/branch.component';
import { OutletComponent } from './masterData/outlet/outlet.component';
import { JournalComponent } from './generalLedger/journal/journal.component';
import { JournalCreateComponent } from './generalLedger/journal/journal-create/journal-create.component';
import { JournalDetailComponent } from './generalLedger/journal/journal-detail/journal-detail.component';
import { JournalListReportComponent } from './report/journal-list-report/journal-list-report.component';
import { ProfitAndLossReportComponent } from './report/profit-and-loss-report/profit-and-loss-report.component';
import { BalanceSheetReportComponent } from './report/balance-sheet-report/balance-sheet-report.component';
import { AccountImportComponent } from './masterData/account/account-import/account-import.component';
import { LedgerComponent } from './generalLedger/ledger/ledger.component';
import { TrailBalanceComponent } from './report/trail-balance/trail-balance.component';
import { TrailBalanceDetailComponent } from './report/trail-balance/trail-balance-detail/trail-balance-detail.component';
import { JournalByAccountComponent } from './generalLedger/journal-by-account/journal-by-account.component';
import { OutletAccountComponent } from './masterData/branch/outlet-account/outlet-account.component'; 
import { HomeTabsComponent } from './home/home-tabs/home-tabs.component';
import { HomeLeftSidebarComponent } from './home/home-left-sidebar/home-left-sidebar.component';
import { FinancialStatementsComponent } from './report/financial-statements/financial-statements.component';
import { TestComponent } from './test/test.component'; 
import { Select2Module } from 'ng-select2-component';
import { ApInvoiceComponent } from './accountPayable/ap-invoice/ap-invoice.component';
import { ApInvoiceDetailComponent } from './accountPayable/ap-invoice/ap-invoice-detail/ap-invoice-detail.component';
import { ApPaymentComponent } from './accountPayable/ap-payment/ap-payment.component';
import { ApPaymentDetailComponent } from './accountPayable/ap-payment/ap-payment-detail/ap-payment-detail.component';

@NgModule({
  declarations: [
    AppComponent,
    HomeComponent,
    NotFoundComponent,
    LoginComponent,
    ReloginComponent,
    GeneralLedgerParameterComponent,
    AutoNumberComponent,
    AccountComponent,
    AccountTypeComponent,
    UserComponent,
    UserDetailComponent,
    UserRoleComponent,
    UserRoleAccessComponent,
    BranchComponent,
    OutletComponent,
    JournalComponent,
    JournalCreateComponent,  JournalDetailComponent, 
    JournalListReportComponent, ProfitAndLossReportComponent, BalanceSheetReportComponent, 
    AccountImportComponent, LedgerComponent, TrailBalanceComponent, TrailBalanceDetailComponent, 
    JournalByAccountComponent, OutletAccountComponent, HomeTabsComponent, HomeLeftSidebarComponent, FinancialStatementsComponent, TestComponent, ApInvoiceComponent, ApInvoiceDetailComponent, ApPaymentComponent, ApPaymentDetailComponent, 
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    FormsModule,
    HttpClientModule,
    CommonModule,
    NgbModule,
    NgxCurrencyDirective,
    Select2Module 
  ],
  providers: [LanguageService],
  bootstrap: [AppComponent]
})
export class AppModule { }
