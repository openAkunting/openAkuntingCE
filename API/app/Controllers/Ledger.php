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
        $q = "SELECT t1.*, a.name FROM (
            SELECT accountId, COUNT(id) AS 'total', SUM(debit) AS 'debit', SUM(credit) AS 'credit' 
            FROM journal 
            WHERE presence = 1
            GROUP BY accountId) AS t1
            JOIN account AS a ON a.id = t1.accountId";
        $items = $this->db->query($q)->getResultArray();
        $i = 0;
        foreach ($items as $row) {
            $ledgerQuery = "SELECT j.*, h.ref, h.note,   o.name AS 'outlet', b.name AS 'branch'
           
            FROM journal AS j
            LEFT JOIN journal_header AS h ON h.id = j.journalId
            LEFT JOIN outlet AS o ON o.id = j.outletId
            LEFT JOIN branch AS b ON b.id = o.branchId
            WHERE accountId = '" . $row['accountId'] . "' 
            ORDER BY j.accountId ASC ";
            $items[$i]['journal'] = $this->db->query($ledgerQuery)->getResultArray();

            // foreach ($items[$i]['ledger'] as $rec) {
            //     $balance += ($rec['debit'] - $rec['credit']);
            //     $items[$i]['ledger'][$j]['balance'] += $balance;
            //     $j++;
            // }

            $i++;
        }


        $data = [
            "error" => false,
            "items" => $items,

        ];
        return $this->response->setJSON($data);
    }


}
