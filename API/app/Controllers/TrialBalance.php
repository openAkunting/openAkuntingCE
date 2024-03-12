<?php

namespace App\Controllers;


class TrialBalance extends BaseController
{
    function __construct()
    {
        if (model("Token")->checkValidToken() == '') {
            //   exit;
        }
    }

    public function index()
    { 
     
        
        $startDate = $this->request->getVar()['startDate'];
        $endDate = $this->request->getVar()['endDate'];

        $rangeDate = model("Core")->rangeDate($startDate, $endDate);

        $date = " AND (h.journalDate >= '$startDate' AND  h.journalDate <= '$endDate' )";


        $q = "SELECT id, name FROM " . $this->prefix . "account_type
        WHERE presence = 1 order by id ASC";
        $items = $this->db->query($q)->getResultArray();
        $i = 0;
        foreach ($items as $row) {
            $accountQuery = "SELECT a.id, a.name, SUM(j.debit)  AS 'debit', SUM(j.credit) AS 'credit'
            FROM " . $this->prefix . "account a  
            JOIN " . $this->prefix . "journal AS j ON j.accountId = a.id 
            JOIN " . $this->prefix . "journal_header AS h ON h.id = j.journalId
            WHERE a.accountTypeId = '".$row['id']."' AND a.presence = 1 AND h.presence = 1
            $date
            GROUP BY a.id   
            "; 
            $items[$i]['account'] = $this->db->query($accountQuery)->getResultArray(); 
            $j = 0; 
            foreach ($items[$i]['account'] as $rec) { 
               //$items[$i]['account'][$j]['begining'] = 0;
                $items[$i]['account'][$j]['begining'] = (float)model("Core")->select("SUM(h.debit - h.credit)","journal AS h","h.accountId = '". $items[$i]['account'][$j]['id']."' AND h.presence = 1 $date");
                $items[$i]['account'][$j]['balance']  = $items[$i]['account'][$j]['begining'] + ($rec['debit'] - $rec['credit']);
                $j++;
            } 
            $i++;
        } 

        $i=0;
        $total = "SELECT SUM(j.debit) AS 'debit', SUM(j.credit) AS 'credit', SUM(j.debit) - SUM(j.credit) AS 'balance'
        FROM  " . $this->prefix . "journal AS j
        JOIN " . $this->prefix . "journal_header AS h ON h.id = j.journalId
        WHERE j.presence = 1 AND h.presence = 1 $date";
        $total = $this->db->query($total)->getResultArray();
        foreach ( $total as $rec) { 
            //$items[$i]['account'][$j]['begining'] = 0;
             $total[$i]['begining'] = (float)model("Core")->select("SUM(h.debit - h.credit)","journal AS h","h.presence = 1 $date");
             
             $j++;
         } 

        $data = [
            "error" => false,
            "items" => $items,
            "balance" =>  $total, 

        ];
        return $this->response->setJSON($data);
    }
 
}
