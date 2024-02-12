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
import { AccountTypeComponent } from './masterData/account-type/account-type.component';
import { UserComponent } from './masterData/user/user.component';
import { UserDetailComponent } from './masterData/user/user-detail/user-detail.component';
import { UserRoleComponent } from './masterData/user/user-role/user-role.component';
import { UserRoleAccessComponent } from './masterData/user/user-role-access/user-role-access.component';
import { LanguageService } from './service/language.service';
import { BranchComponent } from './masterData/branch/branch.component';
import { OutletComponent } from './masterData/outlet/outlet.component';
import { JournalComponent } from './generalLedger/journal/journal.component';
import { CashBankComponent } from './generalLedger/cash-bank/cash-bank.component'; 
import { JournalCreateComponent } from './generalLedger/journal/journal-create/journal-create.component'; 

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
    CashBankComponent, 
    JournalCreateComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    FormsModule,
    HttpClientModule,
    CommonModule,
    NgbModule,
    NgxCurrencyDirective
  ],
  providers: [LanguageService],
  bootstrap: [AppComponent]
})
export class AppModule { }
