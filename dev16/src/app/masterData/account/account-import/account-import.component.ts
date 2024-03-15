import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
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
export class AccountImportComponent implements OnInit{
  file: File | null = null;
  sheetNames: string[] = [];
  selectedSheetName: string | null = null;
  sheetData: any[] = [];
  sheetHeader: any = [];
  switch_expression : string = "";
  selectAccount : any = [];
  constructor(
    private http: HttpClient,
    private configService: ConfigService,
    private modalService: NgbModal,
    public lang: LanguageService
  ) { }
  ngOnInit(): void {
    this.resetAccount();
  }

  onFileChange(event: any): void {
    this.file = event.target.files[0];
    this.readExcelFile();
  }
  updateSelectAccount(){
    console.log(this.selectAccount, this.sheetHeader);
  }
  resetAccount(){
    this.selectAccount = [
      {
        name : "id",
        label : 'Account ID',
        status : false,
      },
      {
        name : "name",
        label : 'Name',
        status : false,
      },
      {
        name : "parentId",
        label : 'Parent Account',
        status : false,
      },
      {
        name : "accountTypeId",
        label : 'Account Classification',
        status : false,
      },
      {
        name : "cashBank",
        label : 'Cash Bank [0 or 1]',
        status : false,
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
  readExcelFile(index : number = 0): void {
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
      let i =0;

     
      this.sheetData[0].forEach((el: any) => {
        const temp = {
          index: i,
          name : el,
          field : "",
        }
        i++;
        this.sheetHeader.push(temp);
      });
      console.log(this.sheetHeader, this.sheetData[0]);
    };

    reader.readAsArrayBuffer(this.file);
    this.switch_expression = "step1";
  }

  onImportCoA(){
    const body = {
      sheetHeader: this.sheetHeader,
      sheetData : this.sheetData
    }
    this.http.post<any>(environment.api + "account/onImportCoA", body, {
      headers: this.configService.headers(),
    }).subscribe(
      data => {  
        console.log(data);
      },
      error => {
        console.log(error);
      }
    )
  }
   
}
