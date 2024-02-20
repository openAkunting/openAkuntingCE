<?php

namespace App\Controllers;


class Ledger extends BaseController
{
    function __construct()
    {
        if (model("Token")->checkValidToken() == '') {
            //   exit;
        }
    }

    public function index()
    {
        $tblAccount = $this->prefix . 'account';
        $tblJournal = $this->prefix . 'journal';

        $rest = [];
        $q = "SELECT id, name FROM " . $this->prefix . "account_type
        WHERE presence = 1 order by id ASC";
        $items = $this->db->query($q)->getResultArray();
        $i = 0;
        foreach ($items as $row) {
            $ledgerQuery = "SELECT j.journalDate, a.id, a.name,  o.name AS 'outlet', b.name AS 'branch',
            j.description, j.debit, j.credit, 0 as 'balance',
            j.inputDate, j.inputBy
            FROM " . $this->prefix . "account a  
             JOIN " . $this->prefix . "journal AS j ON j.accountId = a.id
            LEFT JOIN " . $this->prefix . "outlet AS o ON o.id = j.outletId
            LEFT JOIN " . $this->prefix . "branch AS b ON b.id = o.branchId
            WHERE a.accountTypeId = '" . $row['id'] . "' AND a.presence = 1 
            ORDER BY j.journalDate ASC  
            ";
            $items[$i]['ledger'] = $this->db->query($ledgerQuery)->getResultArray();
            $j = 0;
            $balance = 0;
            foreach ($items[$i]['ledger'] as $rec) {
                $balance += ($rec['debit'] - $rec['credit']);
                $items[$i]['ledger'][$j]['balance'] += $balance;
                $j++;
            }

            $i++;
        }
 

        $data = [
            "error" => false,
            "items" => $items, 

        ];
        return $this->response->setJSON($data);
    }
  

}
