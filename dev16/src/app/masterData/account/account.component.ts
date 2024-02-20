import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { LanguageService } from 'src/app/service/language.service';
import { environment } from 'src/environments/environment';

export class NewCoA {
  constructor(
    public id: number,
    public name: string,
    public accountTypeId: string,
  ) { }
}
@Component({
  selector: 'app-account',
  templateUrl: './account.component.html',
  styleUrls: ['./account.component.css']
})
export class AccountComponent implements OnInit {
  note: string = "";
  disable: boolean = true;
  items: any = [];
  itemsOrigin: any = [];
  accountType: any = [];
  item: any;
  accountTree: any = [];
  newCoA: any = new NewCoA(0, "", "");
  currencyOptions: any = { prefix: '', thousands: '.', decimal: ',', precision: 0, }
  columnHeader: any = [];
  selectedLang: string = 'en';
  translatedText: string = '';
  checkAllCashBank : boolean = false;
  checkAllStatus : boolean = false;
  constructor(
    private http: HttpClient,
    private configService: ConfigService,
    private modalService: NgbModal,
    public lang: LanguageService
  ) { }
  ngOnInit() {
    this.httpGet();
  }

  httpGet() {
    this.newCoA = new NewCoA(0, "", "");
    this.http.get<any>(environment.api + "account/chartOfAccount", {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        console.log(data);
        this.items = JSON.parse(JSON.stringify(data['items']));
        this.itemsOrigin = JSON.parse(JSON.stringify(data['items']));
        this.accountType = data['acccountType'];
        this.columnHeader = data['columnHeader'];
        this.accountTree = data['accountTree'];
      },
      error => {
        console.log(error);
      }
    )
  }

  onCheckBox(x: any, status: string, name: string) {
    if (this.disable == false) {
      if (status == '0') {
        x[name] = '1';
      }
      else if (status == '1') {
        x[name] = '0';
      }
    }
  }

  onCheckBoxAllCashBank(status : boolean){
    if (this.disable == false) {
      if (status == false) {
       this.checkAllCashBank = true; 
      }
      else if (status == true) {
        this.checkAllCashBank = false;
      }

      for(let i = 0; i < this.items.length ; i++){
        this.items[i]['cashBank'] = (this.checkAllCashBank  == true) ? "1":"0";
      }
    }
  }

  onCheckBoxAllStatus(status : boolean){
    if (this.disable == false) {
      if (status == false) {
       this.checkAllStatus = true; 
      }
      else if (status == true) {
        this.checkAllStatus = false;
      }

      for(let i = 0; i < this.items.length ; i++){
        this.items[i]['status'] = (this.checkAllStatus  == true) ? "1":"0";
      }
    }
  }

  fnRollback() {
    this.items = JSON.parse(JSON.stringify(this.itemsOrigin));
    this.disable = true;
  }

  fnUpdate() {
    const body = {
      items: this.items,
    }
    this.http.post<any>(environment.api + "account/chartOfAccountUpdate", body, {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        this.itemsOrigin = JSON.parse(JSON.stringify(this.items));
        console.log(data);
      },
      error => {
        console.log(error);
      }
    )
  }

  onCheck(x: any, check: boolean) {
    x.checkBox = check;
    console.log(x.checkBox);
  }

  fnDelete() {
    if (confirm("Delete this check ?")) {

      const body = {
        items: this.items,
      }
      this.http.post<any>(environment.api + "account/chartOfAccountDelete", body, {
        headers: this.configService.headers(),
      }).subscribe(
        data => {
          this.httpGet();
          console.log(data);
        },
        error => {
          console.log(error);
        }
      )
    }
  }

  addItemChild(x: any) {
    const body = {
      addItemChild: true,
      item: x
    }
    this.http.post<any>(environment.api + "account/addItemChild", body, {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        this.httpGet();
        console.log(data);
      },
      error => {
        console.log(error);
      }
    )
  }

  fnDetailAccountType(id: string, name: string) {
    const item = this.accountType.find((item: { id: string; }) => item.id === id);
    return item ? item[name] : '';
  }

  open(content: any, item: any) {
    this.item = item;
    if (this.item.id != '1') {
      this.newCoA['accountTypeId'] = item['accountTypeId']
    }

    this.modalService.open(content);
  }

  modal(content: any) {
    this.modalService.open(content);
  }
  onSubmit() {
    const body = {
      item: this.item,
      newCoA: this.newCoA
    }
    this.http.post<any>(environment.api + "account/chartOfAccountInsert", body, {
      headers: this.configService.headers(),
    }).subscribe(
      data => {
        this.httpGet();
        this.modalService.dismissAll();
        console.log(data);
      },
      error => {
        console.log(error);
      }
    )
  }

  exportData(ext: string) {
    window.open(environment.api + "export/account", '_blank');
  }


  countPossibleIPs(str: string, level: number) {
    var a = "";
    if (level == -1) { 
      // Memisahkan string menjadi oktet-oktet yang terpisah
      const octets = str.split('.');

      // Jumlah oktet yang sudah diketahui
      const t = octets.length - 1;
   
      for (let i = 0; i < t; i++) {
        a += "&nbsp; ";
      }
    }else{
      for (let i = 0; i < level; i++) {
        a += "&nbsp; ";
      }
    }
    return a;
  }
 


  getLevel(id: string, data: any, level: number = 0): number {
    for (const item of data) {
      if (item.id === id) {
        if (item.parentId == null || item.parentId == '0') {
          return level; // Jika ID merupakan root
        } else {
          return this.getLevel(item.parentId, data, level + 1); // Rekursif untuk mencari parent
        }
      }
    }
    return -1; // Jika ID tidak ditemukan
  }

  updateStatus(parentId: string, accountTypeId: string): void {
    this.items.forEach((item: any) => {
      if (item.parentId == parentId) {
        item.accountTypeId = accountTypeId;
        this.updateStatus(item.id, accountTypeId); // Rekursif untuk memperbarui anak-anak
      }
    });
  }

  // Contoh penggunaan
  updateParentStatus(parentId : string , accountTypeId : string): void {
    const parentIdToUpdate = parentId; // ID parent yang ingin diperbarui
    const newStatus = accountTypeId; // Status baru yang ingin diberikan

    // Memanggil fungsi untuk memperbarui status
    this.updateStatus(parentIdToUpdate, newStatus);

    // Menampilkan data setelah pembaruan
    //console.log(this.data);
  }

}
