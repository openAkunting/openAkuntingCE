<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\CodeIgniter;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use CodeIgniter\HTTP\IncomingRequest;

use DateTime;

class journal extends Model
{
    protected $prefix = null;
    protected $db = null;
    protected $request = null;

    function __construct()
    {
        $this->prefix = $_ENV['PREFIX'];
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
    }

    function InsertJournalAP($journalId, $paymentId)
    {
        $totalCredit = 0;
        $totalDebit = 0;

        $q = "SELECT 
            a.id , a.paymentDate, a.debitAccountId, d.payment
        FROM ap_payment AS a  
        LEFT JOIN ap_payment_detail AS d ON d.paymentId = a.id
        WHERE a.presence = 1  AND a.id = '$paymentId'";
        $apPaymentDetail = $this->db->query($q)->getResultArray();
        foreach ($apPaymentDetail as $row) {
            $this->db->table($this->prefix . "journal")->insert([
                "journalId" => $journalId,
                "journalDate" => $row['paymentDate'],
                //     "outletId" => $row['outletId'],
                "accountId" => $row['debitAccountId'],
                "debit" => $row['payment'],
                "credit" => 0,
                "presence" => 1,
                "updateDate" => date("Y-m-d H:i:s"),
                "updateBy" => model("Token")->userId(),
                "inputDate" => date("Y-m-d H:i:s"),
                "inputBy" => model("Token")->userId()
            ]);
            $totalDebit += $row['payment'];
        }

        $q = "SELECT a.id , a.paymentDate, d.adjustment, d.adjustmentAccountId
        FROM ap_payment AS a  
        LEFT JOIN ap_payment_detail AS d ON d.paymentId = a.id
        WHERE a.presence = 1  AND a.id ='000018' AND d.adjustment != 0 
        AND d.adjustmentAccountId != '' ";
        $apPaymentDetail = $this->db->query($q)->getResultArray();
        foreach ($apPaymentDetail as $row) {
            $this->db->table($this->prefix . "journal")->insert([
                "journalId" => $journalId,
                "journalDate" => $row['paymentDate'],
                //     "outletId" => $row['outletId'],
                "accountId" => $row['adjustmentAccountId'],
                "debit" => $row['adjustment'],
                "credit" => 0,
                "presence" => 1,
                "updateDate" => date("Y-m-d H:i:s"),
                "updateBy" => model("Token")->userId(),
                "inputDate" => date("Y-m-d H:i:s"),
                "inputBy" => model("Token")->userId()
            ]);
            $totalDebit += $row['adjustment'];
        }


        $totalCredit = $totalDebit;

        $q = "SELECT *  FROM ap_payment
        WHERE presence = 1 AND id  =  '$paymentId' ";
        $item = $this->db->query($q)->getResultArray()[0];

        $this->db->table($this->prefix . "journal")->insert([
            "journalId" => $journalId,
            "journalDate" => $item['paymentDate'], 
            "accountId" => $item['creditAccountId'],
            "debit" => 0,
            "credit" => $totalCredit,
            "presence" => 1,
            "updateDate" => date("Y-m-d H:i:s"),
            "updateBy" => model("Token")->userId(),
            "inputDate" => date("Y-m-d H:i:s"),
            "inputBy" => model("Token")->userId()
        ]);

        $this->db->table($this->prefix . "journal_header")->insert([
            "id" => $journalId,
            "journalDate" => $item['paymentDate'], 
            "totalCredit" => $totalCredit,
            "totalDebit" => $totalDebit, 
            "ap" => $paymentId,
            "presence" => 1,
            "updateDate" => date("Y-m-d H:i:s"),
            "updateBy" => model("Token")->userId(),
            "inputDate" => date("Y-m-d H:i:s"),
            "inputBy" => model("Token")->userId()
        ]);


    }
}