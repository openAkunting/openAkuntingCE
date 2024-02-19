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
export class AccountImportComponent {
  file: File | null = null;
  sheetNames: string[] = [];
  selectedSheetName: string | null = null;
  sheetData: any[] = [];
  sheetHeader: any = [];

  constructor(
    private http: HttpClient,
    private configService: ConfigService,
    private modalService: NgbModal,
    public lang: LanguageService
  ) { }

  onFileChange(event: any): void {
    this.file = event.target.files[0];
    this.readExcelFile();
  }

  onSelectSheet(){
    console.log(this.selectedSheetName);
    this.readExcelFile()
  }
  readExcelFile(): void {
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
      console.log(this.sheetNames, this.sheetData);
    };

    reader.readAsArrayBuffer(this.file);
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
