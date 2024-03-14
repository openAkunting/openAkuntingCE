import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { LanguageService } from 'src/app/service/language.service';
import { environment } from 'src/environments/environment';
import { OutletAccountComponent } from './outlet-account/outlet-account.component';
export class NewCoA {
  constructor(
    public id: number,
    public name: string,
    public accountTypeId: string,
  ) { }
}
@Component({
  selector: 'app-branch',
  templateUrl: './branch.component.html',
  styleUrls: ['./branch.component.css']
})
export class BranchComponent implements OnInit {
  note: string = "";
  disable: boolean = true;
  item: any = [];
  items: any = [];
  itemsChild: any = [];
  activeIndexItem: number | null = null;
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
    this.http.get<any>(environment.api + "branch/index", {
      headers: this.configService.headers()
    }).subscribe(
      data => {
        console.log(data);
        this.items = data['items'];
      },
      error => {
        console.log(error);
      }
    )
  }

  addBranch() {
    let foo = prompt('Name new branch :');
    console.log(foo);

    const body = {
      name: foo,
    }
    if (foo != null) {
      this.http.post<any>(environment.api + "branch/addNew", body, {
        headers: this.configService.headers()
      }).subscribe(
        data => {
          console.log(data);
          this.httpGet();
        },
        error => {
          console.log(error);
        }
      )
    }
  }

  editable() {
    this.disable = false;
  }

  onCheckBranch(i: number, status: string) {
    if (this.disable == false) {
      this.items[i]['status'] = status;
    }
  }

  onDeleteBranch(item: any) {
    if (confirm("Delete this branch and outlet ?")) {
      const body = {
        id: item.id
      }
      this.http.post<any>(environment.api + "branch/onDelete", body, {
        headers: this.configService.headers()
      }).subscribe(
        data => {
          console.log(data);
          this.httpGet();
        },
        error => {
          console.log(error);
        }
      )
    }
  }

  onUpdateBranch() {
    this.http.post<any>(environment.api + "branch/onUpdate", this.items, {
      headers: this.configService.headers()
    }).subscribe(
      data => {
        console.log(data);
      },
      error => {
        console.log(error);
      }
    )
  }

  showOutlet(index: number | null, item: any) {
    this.activeIndexItem = index;
    this.item = item;
    this.http.get<any>(environment.api + "Outlet", {
      headers: this.configService.headers(),
      params: {
        id: item.id
      }
    }).subscribe(
      data => {
        console.log(data);
        this.itemsChild = data['items'];
      },
      error => {
        console.log(error);
      }
    )
  }

  onCheckOutlet(i: number, status: string) {
    if (this.disable == false) {
      this.itemsChild[i]['status'] = status;
    }
  }

  addOutlet() {
    let foo = prompt('Name new outlet :');
    console.log(foo);

    const body = {
      name: foo,
      branchId: this.item['id']
    }
    if (foo != null) {
      this.http.post<any>(environment.api + "Outlet/addNew", body, {
        headers: this.configService.headers()
      }).subscribe(
        data => {
          console.log(data);
          this.showOutlet(this.activeIndexItem, this.item);
        },
        error => {
          console.log(error);
        }
      )
    }
  }

  onUpdateOutlet() {
    this.http.post<any>(environment.api + "outlet/onUpdate", this.itemsChild, {
      headers: this.configService.headers()
    }).subscribe(
      data => {
        console.log(data);
      },
      error => {
        console.log(error);
      }
    )
  }

  onDeleteOutlet(item: any) {
    if (confirm("Delete this outlet ?")) {
      const body = {
        id: item.id
      }
      this.http.post<any>(environment.api + "outlet/onDelete", body, {
        headers: this.configService.headers()
      }).subscribe(
        data => {
          console.log(data);
          this.showOutlet(this.activeIndexItem, this.item);
        },
        error => {
          console.log(error);
        }
      )
    }
  }

  open(outletId :number) {
		const modalRef = this.modalService.open(OutletAccountComponent,{ size:'md', scrollable: true });
		modalRef.componentInstance.branchId = this.item['id'];
    modalRef.componentInstance.outletId = outletId;

    modalRef.componentInstance.newItemEvent.subscribe(() => {
      console.log('ok');
    });
	}
}
