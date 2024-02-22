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
            GROUP BY a.id   
            "; 
            $items[$i]['account'] = $this->db->query($accountQuery)->getResultArray(); 
            $j = 0; 
            foreach ($items[$i]['account'] as $rec) { 
                $items[$i]['account'][$j]['balance']  = ($rec['debit'] - $rec['credit']);
                $j++;
            } 
            $i++;
        } 
        $total = "SELECT SUM(j.debit) AS 'debit', SUM(j.credit) AS 'credit', SUM(j.debit) - SUM(j.credit) AS 'balance'
        FROM  " . $this->prefix . "journal AS j
        JOIN " . $this->prefix . "journal_header AS h ON h.id = j.journalId
        WHERE j.presence = 1 AND h.presence = 1";
        $total = $this->db->query($total)->getResultArray();

        $data = [
            "error" => false,
            "items" => $items,
            "balance" =>  $total, 

        ];
        return $this->response->setJSON($data);
    }
 
}