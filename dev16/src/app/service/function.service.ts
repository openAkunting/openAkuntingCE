import { Injectable } from '@angular/core';  

@Injectable({
  providedIn: 'root'
})
export class FunctionService {
 
  constructor() { }

 
  onCheckRange(startDateGet  :any , endDateGet:any ) {
  
    const startDate = startDateGet['year'] +"-"+ startDateGet['month'].toString().padStart(2, '0') +"-"+ startDateGet['day'];
    const endDate = endDateGet['year'] +"-"+ endDateGet['month'].toString().padStart(2, '0') +"-"+ endDateGet['day'];
 
    const startDateInt = parseInt(startDateGet['year'] + startDateGet['month'].toString().padStart(2, '0') + startDateGet['day']);
    const endDateInt = parseInt(endDateGet['year'] + endDateGet['month'].toString().padStart(2, '0') + endDateGet['day']);
 
    // Parse string tanggal ke objek Date
    const startDateObj = new Date(startDate);
    const endDateObj = new Date(endDate);

    // Hitung perbedaan waktu dalam milidetik
    const timeDiff = endDateObj.getTime() - startDateObj.getTime();

    // Konversi milidetik ke hari
    const daysDiff = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
 

    const rest = {
        daysDiff : daysDiff,
        copyDate : ( startDateInt > endDateInt ) ? endDateObj : startDateObj
    }
  
    return rest; 
  }

}