import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { LanguageService } from 'src/app/service/language.service';
import { environment } from 'src/environments/environment';

import * as XLSX from 'xlsx';

@Component({
  selector: 'app-account-import',
  templateUrl: './account-import.component.html',
  styleUrls: ['./account-import.component.css']
})
export class AccountImportComponent implements OnInit {
  file: File | null = null;
  sheetNames: string[] = [];
  selectedSheetName: string | null = null;
  sheetData: any[] = [];
  sheetHeader: any = [];
  switch_expression: string = "";
  selectAccount: any = [];
  onSubmitDisable: boolean = true;
  constructor(
    private http: HttpClient,
    private configService: ConfigService,
    private modalService: NgbModal,
    private router:Router,
    public lang: LanguageService
  ) { }
  ngOnInit(): void {
    this.resetAccount();
  }

  back(){
    history.back();
  }
  onFileChange(event: any): void {
    this.file = event.target.files[0];
    this.readExcelFile();
  }
  updateSelectAccount() {
    console.log(this.selectAccount, this.sheetHeader);

    for (let i = 0; i < this.selectAccount.length; i++) {
      this.selectAccount[i]['status'] = false;
    }

    this.sheetHeader.forEach((el: any) => {
      if (el['field'] == 'id') {
        this.selectAccount[0]['status'] = true;
      }
      if (el['field'] == 'name') {
        this.selectAccount[1]['status'] = true;
      }
      if (el['field'] == 'parentId') {
        this.selectAccount[2]['status'] = true;
      }
      if (el['field'] == 'accountTypeId') {
        this.selectAccount[3]['status'] = true;
      }
      if (el['field'] == 'cashBank') {
        this.selectAccount[4]['status'] = true;
      }
    });
    this.onSubmitDisable = true;
    if (this.selectAccount[0]['status'] == true && this.selectAccount[1]['status'] == true) {
      this.onSubmitDisable = false;
    }

  }
  resetAccount() {
    this.selectAccount = [
      {
        name: "id",
        label: 'Account ID',
        status: false,
        required: true
      },
      {
        name: "name",
        label: 'Name',
        status: false,
        required: true
      },
      {
        name: "parentId",
        label: 'Parent Account',
        status: false,
        required: false,
      },
      {
        name: "accountTypeId",
        label: 'Account Classification',
        status: false,
        required: false,
      },
      {
        name: "cashBank",
        label: 'Cash Bank [0 or 1]',
        status: false,
        required: false,
      },
    ]
  }
  // onSelectSheet(){
  //   console.log(this.selectedSheetName);
  //   this.readExcelFile()
  // }

  onSelectSheet(event: Event): void {
    const index = (event.target as HTMLSelectElement).selectedIndex;
    console.log(index);
    this.sheetHeader = [];
    this.readExcelFile(index);
    // Gunakan nilai index di sini
  }
  readExcelFile(index: number = 0): void {
    if (!this.file) {
      return;
    }

    const reader: FileReader = new FileReader();

    reader.onload = (e: any) => {
      const data: ArrayBuffer = e.target.result;
      const workbook: XLSX.WorkBook = XLSX.read(data, { type: 'array' });

      // Ambil nama-nama sheet
      this.sheetNames = workbook.SheetNames;

      // Default pilih sheet pertama
      this.selectedSheetName = this.selectedSheetName == null ? this.sheetNames[0] : this.selectedSheetName;

      // Ambil data dari sheet yang dipilih
      const worksheet: XLSX.WorkSheet = workbook.Sheets[this.selectedSheetName];
      this.sheetData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
      let i = 0;


      this.sheetData[0].forEach((el: any) => {
        const temp = {
          index: i,
          name: el,
          field: "",
        }
        i++;
        this.sheetHeader.push(temp);
      });
      console.log(this.sheetHeader, this.sheetData[0]);
    };

    reader.readAsArrayBuffer(this.file);
    this.switch_expression = "step1";
  }

  onImportCoA() {
    if (this.selectAccount[0]['status'] == true && this.selectAccount[1]['status'] == true) {
      const body = {
        sheetHeader: this.sheetHeader,
        sheetData: this.sheetData
      }
      console.log(body);
      this.http.post<any>(environment.api + "account/onImportCoA", body, {
        headers: this.configService.headers(),
      }).subscribe(
        data => {
          console.log(data);
          this.router.navigate(['/md/a']);
        },
        error => {
          console.log(error);
        }
      )
    }else{
      alert("ERROR SUBMIT");
    }
  }

}
