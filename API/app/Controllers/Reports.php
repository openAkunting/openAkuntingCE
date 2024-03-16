<?php

namespace App\Controllers;

class Reports extends BaseController
{
    protected $maxDay = null;
    function __construct()
    {
        $this->maxDay = 400;
        if (model("Token")->checkValidToken() == '') {
            //   exit;
        }
    }
    public function BalanceSheet()
    {
        $startDate = $this->request->getVar()['startDate'];
        $endDate = $this->request->getVar()['endDate'];


        $dateObj1 = date_create($startDate);
        $dateObj2 = date_create($endDate);
        $interval = date_diff($dateObj1, $dateObj2);
        $totalDays = $interval->days;

        if ($totalDays < $this->maxDay) {
            $data = self::reportByMonth($this->request->getVar(), " balanceSheet = 1");
        } else {
            $data = array(
                "error" => true,
                "note" => "Max " . $this->maxDay . " day",
                "totalDays" => $totalDays
            );
        }

        return $this->response->setJSON($data);
    }
    public function ProfitAndLoss()
    {
        $startDate = $this->request->getVar()['startDate'];
        $endDate = $this->request->getVar()['endDate'];


        $dateObj1 = date_create($startDate);
        $dateObj2 = date_create($endDate);
        $interval = date_diff($dateObj1, $dateObj2);
        $totalDays = $interval->days;

        if ($totalDays < $this->maxDay) {
            $data = self::report($this->request->getVar(), " profitAndLoss = 1 ");
        } else {
            $data = array(
                "error" => true,
                "note" => "Max " . $this->maxDay . " day"
            );
        }

        return $this->response->setJSON($data);
    }

    private function report($get, $typeReport)
    {
        $rest = [];
        $startDate = $get['startDate'];
        $endDate = $get['endDate'];
        $data = [];

        $dataAccountQ = "SELECT id, parentId, name
        FROM " . $this->prefix . "account 
        WHERE  presence = 1
        ORDER BY  id ASC,  parentId ASC";
        $dataAccount = $this->db->query($dataAccountQ)->getResultArray();

        $accountTypeQ = "SELECT * FROM account_type WHERE $typeReport ";
        $account = [];
        $total = array(
            "debit" => 0,
            "credit" => 0,
            "balance" => 0,
        );
        $date = " AND (h.journalDate >= '$startDate' AND  h.journalDate <= '$endDate' )";


        foreach ($this->db->query($accountTypeQ)->getResultArray() as $row) {
            $subtotal = array(
                "debit" => 0,
                "credit" => 0,
                "balance" => 0,
            );

            $q = "SELECT a.id , a.name
            FROM account AS a
            LEFT JOIN account_type AS t ON t.id = a.accountTypeId
            WHERE a.presence = 1 AND t.$typeReport and a.accountTypeId = '" . $row['id'] . "'
            ORDER BY a.id ASC, a.parentId ASC
            ";
            $account = $this->db->query($q)->getResultArray();
            for ($i = 0; $i < count($account); $i++) {

                $balanceQ = "SELECT SUM(j.debit) AS 'debit' , SUM(j.credit) AS 'credit', SUM(j.debit-j.credit) AS 'balance' 
                FROM journal AS j
                JOIN journal_header AS h ON h.id = j.journalId  $date  
                WHERE  j.accountId= '" . $account[$i]['id'] . "' ";

                $balance = $this->db->query($balanceQ)->getResultArray()[0];

                $account[$i]['level'] = model("Account")->getLevel($account[$i]['id'], $dataAccount);
                $account[$i]['debit'] = $balance['debit'];
                $account[$i]['credit'] = $balance['credit'];
                $account[$i]['balance'] = $balance['balance'];


                $total['debit'] += $account[$i]['debit'];
                $total['credit'] += $account[$i]['credit'];
                $total['balance'] += $account[$i]['balance'];

                $subtotal['debit'] += $account[$i]['debit'];
                $subtotal['credit'] += $account[$i]['credit'];
                $subtotal['balance'] += $account[$i]['balance'];
            }

            $filteredAccounts = [];
            for ($i = 0; $i < count($account); $i++) {
                if ($account[$i]['balance'] !== null) {
                    $filteredAccounts[] = $account[$i];
                }
            }

            $data[] = array(
                "id" => $row['id'],
                "typeOfAccount" => $row['name'],
                "account" => $filteredAccounts,
                "subtotal" => $subtotal,
            );

        }
        $rest = [
            "error" => false,
            "code" => 200,
            "data" => $data,
            "total" => $total,
        ];

        return $rest;
    }

    private function reportByMonth($get, $typeReport)
    {
        $rest = [];
        $startDate = $get['startDate'];
        $endDate = $get['endDate'];
        $data = [];

        $dataAccountQ = "SELECT id, parentId, name
        FROM " . $this->prefix . "account 
        WHERE  presence = 1
        ORDER BY  id ASC,  parentId ASC";
        $dataAccount = $this->db->query($dataAccountQ)->getResultArray();

        $accountTypeQ = "SELECT * FROM account_type WHERE $typeReport ";
        $accountType = $this->db->query($accountTypeQ)->getResultArray();
        $account = [];
        $total = [];
        // $date = " AND (h.journalDate >= '$startDate' AND  h.journalDate <= '$endDate' )";
        $date = "";
        $subtotal = [];
        $getMonthList = model("Account")->getMonthList($startDate, $endDate);
        foreach ($accountType as $row) {

            $filteredAccounts = [];
            $q = "SELECT a.id , a.name
            FROM account AS a
            LEFT JOIN account_type AS t ON t.id = a.accountTypeId
            WHERE a.presence = 1 AND t.$typeReport and a.accountTypeId = '" . $row['id'] . "'
            ORDER BY a.id ASC, a.parentId ASC
            ";
            $i = 0;
            $account = $this->db->query($q)->getResultArray();
            foreach ($account as $rec) {


                $dataByDate = [];
                foreach ($getMonthList as $has) {

                    $balanceQ = "SELECT SUM(j.debit) AS 'debit' , SUM(j.credit) AS 'credit', SUM(j.debit-j.credit) AS 'balance' 
                    FROM journal AS j
                    JOIN journal_header AS h ON h.id = j.journalId  
                    WHERE  j.accountId= '" . $rec['id'] . "' and  j.presence = 1 and h.presence = 1 AND   
                    YEAR(j.journalDate) = '" . $has[0] . "' and  
                    MONTH(j.journalDate) = '" . $has[1] . "' ";

                    $balance = $this->db->query($balanceQ)->getResultArray()[0];

                  //  $account[$i]['level'] = model("Account")->getLevel($rec['id'], $dataAccount); 
                  //  $account[$i]['level22'] = model("Account")->totalChild($rec['id']); 
                    
                    // $account[$i]['balance'] = $balance['balance'];
                    $i++;
                    $dataByDate[] = array( 
                        "startDate" => array(
                            "year" => (int)date("Y", strtotime($has[0] . "-" . $has[1] . "-01")),
                            "month" => (int)date("m", strtotime($has[0] . "-" . $has[1] . "-01")),
                            "day" => (int)date("d", strtotime($has[0] . "-" . $has[1] . "-01"))
                        ),
                        "endDate" => array(
                            "year" => (int)date("Y", strtotime($has[0] . "-" . $has[1] . "-01")),
                            "month" => (int)date("m", strtotime($has[0] . "-" . $has[1] . "-01")),
                            "day" => (int)date("t", strtotime($has[0] . "-" . $has[1] . "-01"))
                        ),

                        "debit" => (float) $balance['debit'],
                        "credit" => (float) $balance['credit'],
                        "balance" => (float) $balance['balance'],
                    );
                }



                $allRec = array(
                    "id" => $rec['id'],
                    "name" => $rec['name'],
                    "level" => model("Account")->getLevel($rec['id'], $dataAccount),
                    "hasChild" => model("Account")->totalChild($rec['id']),
                    "data" => $dataByDate,
                );


                $filteredAccounts[] = $allRec;

            }
            $subtotal = [];
            foreach ($getMonthList as $has) {
                $accountSummaryQuery = "SELECT SUM(j.debit) AS 'debit' , SUM(j.credit) AS 'credit', SUM(j.debit-j.credit) AS 'balance' 
                FROM journal AS j
                JOIN journal_header AS h ON h.id = j.journalId  
                JOIN account AS a ON a.id = j.accountId
                WHERE  a.accountTypeId = '" . $row['id'] . "' and  j.presence = 1 and h.presence = 1 AND   
                YEAR(j.journalDate) = '" . $has[0] . "' and  
                 MONTH(j.journalDate) = '" . $has[1] . "' ";
                $accountSummary = $this->db->query($accountSummaryQuery)->getResultArray()[0];
                $subtotal[] = array(
                    //    "q" => $accountSummaryQuery,
                    "accountTypeId" => $row['id'],
                    "date" => $has,
                    "totalDebit" => (float) $accountSummary['debit'],
                    "totalCredit" => (float) $accountSummary['credit'],
                    "totalBalance" => (float) $accountSummary['balance'],
                );
            }


            $data[] = array(
                "id" => $row['id'],
                "typeOfAccount" => $row['name'],
                "account" => $filteredAccounts,
                "subtotal" => $subtotal,
            );

        }


        $accounTypeIdWhere = "";
        $or = "";
        foreach ($accountType as $a) {
            $accounTypeIdWhere .= " $or a.accountTypeId = '" . $a['id'] . "'   ";
            $or = " OR ";
        }


        foreach ($getMonthList as $has) {
            $accountSummaryQuery = "SELECT SUM(j.debit) AS 'debit' , SUM(j.credit) AS 'credit', SUM(j.debit-j.credit) AS 'balance' 
            FROM journal AS j
            JOIN journal_header AS h ON h.id = j.journalId  
            JOIN account AS a ON a.id = j.accountId
            WHERE  ( $accounTypeIdWhere ) AND  j.presence = 1 and h.presence = 1 AND   
            YEAR(j.journalDate) = '" . $has[0] . "' and  
             MONTH(j.journalDate) = '" . $has[1] . "' ";
            $accountSummary = $this->db->query($accountSummaryQuery)->getResultArray()[0];
            $total[] = array(
                "date" => $has,
                "totalDebit" => (float) $accountSummary['debit'],
                "totalCredit" => (float) $accountSummary['credit'],
                "totalBalance" => (float) $accountSummary['balance'],
            );
        }





        $rest = [
            "error" => false,
            "code" => 200,
            "startDate" => $startDate,
            "endDate" => $endDate,
            "data" => $data,
            "total" => $total,
            "listMonth" => $getMonthList,
        ];

        return $rest;
    }



    function test()
    {
        // Array awal
        $array = [
            'foo' => 'bar',
            'hahaha' => '',
            'baz' => 'qux',
            'emptyArray' => [],
            'nestedArray' => [
                'emptyNestedValue' => ''
            ]
        ];

        // Menghapus elemen yang kosong
        $array = array_filter($array, function ($value) {
            if (is_array($value)) {
                return !empty ($value);
            }
            return $value !== '';
        });

        // Menampilkan hasil
        print_r($array);
    }
}
