<?php

namespace App\Controllers;

class Reports extends BaseController
{
    protected $maxDay = null;
    function __construct()
    {
        $this->maxDay = 33;
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
            $data = self::report($this->request->getVar(), " balanceSheet = 1");
        } else {
            $data = array(
                "error" => true,
                "note" => "Max " . $this->maxDay . " day"
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

    private function report($get, $typeReport )
    {
        $rest = [];
        $startDate = $get['startDate'];
        $endDate = $get['endDate'];
        $data = [];
        $isTable = isset($get['table']) ? $get['table'] : "journal";

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
                WHERE  j.accountId= '".$account[$i]['id']."' "; 

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
                return !empty($value);
            }
            return $value !== '';
        });

        // Menampilkan hasil
        print_r($array);
    }
}
