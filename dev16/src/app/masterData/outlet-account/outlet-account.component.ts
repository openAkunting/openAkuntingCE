import { HttpClient } from '@angular/common/http';
import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { ConfigService } from 'src/app/service/config.service';
import { LanguageService } from 'src/app/service/language.service';

@Component({
  selector: 'app-outlet-account',
  templateUrl: './outlet-account.component.html',
  styleUrls: ['./outlet-account.component.css']
})
export class OutletAccountComponent implements OnInit {
  @Output() newItemEvent = new EventEmitter<string>();
  @Input() branchId: any; 
  
  constructor(
    public activeModal: NgbActiveModal,
    private http: HttpClient,
    private configService: ConfigService,
    public lang: LanguageService
  ) { }
 
  ngOnInit(): void {
    // this.newItemEvent.emit();
  }

}
