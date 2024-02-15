<?php

namespace App\Controllers; 
class CashBank extends BaseController
{
    function __construct()
    {
        if (model("Token")->checkValidToken() == '') {
            //   exit;
        }
    }

    public function index()
    {

        $rest = [];
        $q = "SELECT * FROM " . $this->prefix . "cash_bank_header 
        WHERE presence = 1 order by journalDate ASC";
        $items = $this->db->query($q)->getResultArray();
        foreach ($items as $row) {

            $j = "SELECT j.id, j.accountId, j.description, j.debit, j.credit,  a.name as 'account', o.name as 'outlet', b.name as 'branch'
            FROM  " . $this->prefix . "cash_bank as j
            left join account as a on a.id = j.accountId
            left join outlet as o on o.id = j.outletId
            left join branch as b on b.id = o.branchId
            WHERE  j.presence = 1 and j.journalId = '" . $row['id'] . "'
            ORDER BY j.id ASC";
            $journal = $this->db->query($j)->getResultArray();

            $rest[] = array(
                "id" => $row['id'],
                "note" => $row['note'],
                "ref" => $row['ref'],
                "journalDate" => $row['journalDate'],
                "journal" => $journal,
                "inputDate" => $row['inputDate'],
                "inputBy" => $row['inputBy'],

            );
        }


        $data = [
            "error" => false,
            //     "items" => $this->db->query($d)->getResult(),
            "items" => $rest,

        ];
        return $this->response->setJSON($data);
    }

    public function selectItems()
    {

        $account = "SELECT id, name, cashBank
        FROM " . $this->prefix . "account AS t1
        WHERE NOT EXISTS (
            SELECT 1
            FROM account AS t2
            WHERE t2.parentId = t1.id
        )
        AND cashBank = 1
        ORDER BY id ASC";

        $outlet = "SELECT o.*, b.name as 'branch'
        FROM  " . $this->prefix . "outlet  as o
        left join branch as b on b.id = o.branchId 
        WHERE o.presence = 1 and o.status = 1
        ORDER BY  b.name ASC, o.name ASC";

        $template = "SELECT *
        FROM  " . $this->prefix . "template   
        WHERE  presence = 1 and tableName = 'cash_bank'   
        ORDER BY name ASC";

        $data = [
            "error" => false,
            "account" => $this->db->query($account)->getResult(),
            "outlet" => $this->db->query($outlet)->getResult(),
            "template" => $this->db->query($template)->getResult(),
        ];
        return $this->response->setJSON($data);
    }

    public function onSubmit()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
            $debit = 0;
            $credit = 0;
            foreach ($post['items'] as $row) {
                $debit += $row['debit'];
                $credit += $row['credit'];
            }
            if (($credit - $debit) == 0) {

                $this->db->transStart();
                $debit = 0;
                $credit = 0;
                $dates = [];
                if ($post['typeJournal'] == 'recurring') {
                    $startPeriod = $post['model']['startPeriod']['year'] . "-" . $post['model']['startPeriod']['month'] . "-" . $post['model']['startPeriod']['day'];
                    $endPeriod = $post['model']['endPeriod']['year'] . "-" . $post['model']['endPeriod']['month'] . "-" . $post['model']['endPeriod']['day'];

                    // Ubah string tanggal menjadi objek DateTime
                    $startDate = new \DateTime($startPeriod);
                    $endDate = new \DateTime($endPeriod);

                    // Buat interval antara dua tanggal
                    $interval = new \DateInterval('P1D'); // Interval 1 hari
                    $dateRange = new \DatePeriod($startDate, $interval, $endDate);

                    // Loop untuk menambahkan setiap tanggal ke dalam array
                    foreach ($dateRange as $date) {
                        $dates[] = $date->format('Y-m-d');

                        if ((int)$date->format('d') == (int)$post['model']['dateOfJournal']) { 

                            $journalId = model("Core")->number("cash_bank");
                            foreach ($post['items'] as $row) {
                                $journalDate =  $date->format('Y-m-d');
                                $this->db->table($this->prefix . "cash_bank")->insert([
                                    "journalId" => $journalId,
                                    "outletId" => $row['outletId'],
                                    "accountId" => $row['accountId'],
                                    "journalDate" => $journalDate ,

                                    "debit" => $row['debit'],
                                    "credit" => $row['credit'],
                                    "description" => $row['description'],
                                    "presence" => 1,
                                    "updateDate" => date("Y-m-d H:i:s"),
                                    "updateBy" => model("Token")->userId(),
                                    "inputDate" => date("Y-m-d H:i:s"),
                                    "inputBy" => model("Token")->userId()
                                ]);
                                $debit += $row['debit'];
                                $credit += $row['credit'];

                                $accountBalanceData = array(
                                    "debit" => $row['debit'],
                                    "credit" =>  $row['credit'], 
                                    "journalDate" => $journalDate,
                                    "year" => (int)$post['model']['journalDate']['year'],
                                    "month" => (int)$post['model']['journalDate']['month'],
                                    "outletID" => $row['outletId'],
                                    "accountId" => $row['accountId'],
                                );
                                $accountBalance =  model("Account")->accountBalance($accountBalanceData);
                            }
                            $this->db->table($this->prefix . "cash_bank_header")->insert([
                                "id" => $journalId,
                                "journalDate" => $date->format('Y-m-d'),
                                "ref" => $post['model']['ref'],
                                "note" => $post['model']['note'],
                                "totalCredit" => $credit,
                                "totalDebit" => $debit,
                                "presence" => 1,
                                "updateDate" => date("Y-m-d H:i:s"),
                                "updateBy" => model("Token")->userId(),
                                "inputDate" => date("Y-m-d H:i:s"),
                                "inputBy" => model("Token")->userId()
                            ]);

                        }
                    }


                } else {
                    $journalDate  =  $post['model']['journalDate']['year'] . "-" . $post['model']['journalDate']['month'] . "-" . $post['model']['journalDate']['day'];
                  
                    $journalId = model("Core")->number("cash_bank");
                    foreach ($post['items'] as $row) {
                        $this->db->table($this->prefix . "cash_bank")->insert([
                            "journalId" => $journalId,
                            "outletId" => $row['outletId'],
                            "accountId" => $row['accountId'],
                            "journalDate" => $journalDate,
                            "debit" => $row['debit'],
                            "credit" => $row['credit'],
                            "description" => $row['description'],
                            "presence" => 1,
                            "updateDate" => date("Y-m-d H:i:s"),
                            "updateBy" => model("Token")->userId(),
                            "inputDate" => date("Y-m-d H:i:s"),
                            "inputBy" => model("Token")->userId()
                        ]);
                        $debit += $row['debit'];
                        $credit += $row['credit'];
                        
                        $accountBalanceData = array(
                            "debit" => $row['debit'],
                            "credit" =>  $row['credit'], 
                            "journalDate" => $journalDate,
                            "year" => (int)$post['model']['journalDate']['year'],
                            "month" => (int)$post['model']['journalDate']['month'],
                            "outletID" => $row['outletId'],
                            "accountId" => $row['accountId'],
                        );
                        $accountBalance =  model("Account")->accountBalance($accountBalanceData);
                    }
                    $this->db->table($this->prefix . "cash_bank_header")->insert([
                        "id" => $journalId,
                        "journalDate" => $post['model']['journalDate']['year'] . "-" . $post['model']['journalDate']['month'] . "-" . $post['model']['journalDate']['day'],
                        "ref" => $post['model']['ref'],
                        "note" => $post['model']['note'],
                        "totalCredit" => $credit,
                        "totalDebit" => $debit,
                        "presence" => 1,
                        "updateDate" => date("Y-m-d H:i:s"),
                        "updateBy" => model("Token")->userId(),
                        "inputDate" => date("Y-m-d H:i:s"),
                        "inputBy" => model("Token")->userId()
                    ]);
                }

                if ($this->db->transStatus() != false) {
                    $this->db->transComplete();
                } else {
                    $this->db->transRollback();
                }
                $data = [
                    "error" => false,
                    "accountBalance" => $accountBalance,
                    "transaction" => $this->db->transStatus() === false ? false : true,
                    "code" => 200,
                   
                ];
            } else {
                $data = [
                    "error" => true,
                    "code" => 400
                ];
            }

        }

        return $this->response->setJSON($data);
    } 
}
