<?php

namespace App\Controllers;


class Journal extends BaseController
{
    function __construct()
    {
        if (model("Token")->checkValidToken() == '') {
            //   exit;
        }
    }

    public function index()
    {

        //and journalDate >= CURDATE()

        $startDate = $this->request->getVar()['startDate'];
        $endDate = $this->request->getVar()['endDate'];

        $rangeDate = model("Core")->rangeDate($startDate, $endDate);
        $rest = [];
        if ($rangeDate <= 100) {



            $date = " AND (journalDate >= '$startDate' AND  journalDate <= '$endDate' )";

            $q = "SELECT * FROM " . $this->prefix . "journal_header 
            WHERE presence = 1    $date
            ORDER BY journalDate ASC";
            $items = $this->db->query($q)->getResultArray();
            foreach ($items as $row) {

                $q1 = "SELECT j.id, j.accountId, j.description, j.debit, j.credit,  a.name as 'account', o.name as 'outlet', b.name as 'branch', j.updateDate
                FROM  " . $this->prefix . "journal as j
                left join account as a on a.id = j.accountId
                left join outlet as o on o.id = j.outletId
                left join branch as b on b.id = o.branchId
                WHERE  j.presence = 1 and j.journalId = '" . $row['id'] . "' and j.debit > 0
                ORDER BY j.sorting ASC, j.id ASC";
                $debit = $this->db->query($q1)->getResultArray();

                $q2 = "SELECT j.id, j.accountId, j.description, j.debit, j.credit,  a.name as 'account', o.name as 'outlet', b.name as 'branch', j.updateDate
                FROM  " . $this->prefix . "journal as j
                left join account as a on a.id = j.accountId
                left join outlet as o on o.id = j.outletId
                left join branch as b on b.id = o.branchId
                WHERE  j.presence = 1 and j.journalId = '" . $row['id'] . "' and j.credit > 0
                ORDER BY j.sorting ASC, j.id ASC";
                $credit = $this->db->query($q2)->getResultArray();

                $journal = array_merge($debit, $credit);

                $debit = 0;
                $credit = 0;
                foreach ($journal as $x) {
                    $debit += $x['debit'];
                    $credit += $x['credit'];
                }


                $rest[] = array(
                    "id" => $row['id'],
                    "note" => $row['note'],
                    "ref" => $row['ref'],
                    "journalDate" => $row['journalDate'],
                    "journal" => $journal,
                    "total" => array(
                        "debit" => $debit,
                        "credit" => $credit,
                    ),
                    "inputDate" => $row['inputDate'],
                    "inputBy" => $row['inputBy'],

                );


            }
        }
        $data = [
            "error" => false,
            "rangeDate" => $rangeDate,
            //     "items" => $this->db->query($d)->getResult(),
            "items" => $rest,

        ];
        return $this->response->setJSON($data);
    }

    public function searchById()
    {
        $id = $this->request->getVar()['id'];


        $startDate = $this->request->getVar()['startDate'];
        $endDate = $this->request->getVar()['endDate'];

        $rangeDate = model("Core")->rangeDate($startDate, $endDate);
        $rest = [];
       // if ($rangeDate <= 100) { 

            $date = " AND (h.journalDate >= '$startDate' AND  h.journalDate <= '$endDate' )";
 
            $q = "SELECT h.*  
            FROM " . $this->prefix . "journal_header AS h
            JOIN journal AS j ON j.journalId = h.id
            WHERE j.presence = 1 AND j.accountId = '$id' $date ";
            $items = $this->db->query($q)->getResultArray();
            foreach ($items as $row) {

                $j = "SELECT j.id, j.accountId, j.description, j.debit, j.credit,  a.name as 'account',   a.updateDate,
            o.name as 'outlet', b.name as 'branch'
            FROM  " . $this->prefix . "journal as j
            LEFT JOIN account as a on a.id = j.accountId
            LEFT JOIN outlet as o on o.id = j.outletId
            LEFT JOIN branch as b on b.id = o.branchId
            WHERE  j.presence = 1 and j.journalId = '" . $row['id'] . "'
            ORDER BY j.id ASC";
                $journal = $this->db->query($j)->getResultArray();

                $debit = 0;
                $credit = 0;
                foreach ($journal as $x) {
                    $debit += $x['debit'];
                    $credit += $x['credit'];
                }
                $rest[] = array(
                    "id" => $row['id'],
                    "note" => $row['note'],
                    "ref" => $row['ref'],
                    "journalDate" => $row['journalDate'],
                    "journal" => $journal,
                    "total" => array(
                        "debit" => $debit,
                        "credit" => $credit,
                    ),
                    "inputDate" => $row['inputDate'],
                    "inputBy" => $row['inputBy'],
                );
            }
       // }
        $data = [
            "error" => false,
            "code" => 200,
            "items" => $rest,
        ];
        return $this->response->setJSON($data);

    }

    public function selectItems()
    {

        // $account = "SELECT id, name
        // FROM " . $this->prefix . "account AS t1
        // WHERE NOT EXISTS (
        //     SELECT 1
        //     FROM account AS t2
        //     WHERE t2.parentId = t1.id
        // )
        // ORDER BY id ASC";

        $account = [];
        $accountTypeQuery = "SELECT t.id, t.name, COUNT(a.accountTypeId) AS 'total' 
        FROM  " . $this->prefix . "account_type AS t
        LEFT JOIN  " . $this->prefix . "account AS a ON a.accountTypeId = t.id
        WHERE a.presence = 1 AND a.`status` = 1 
        GROUP BY a.accountTypeId";
        $account = $this->db->query($accountTypeQuery)->getResultArray();
        $i = 0;
        foreach ($account as $rec) {

            // $q = "SELECT id, name 
            // FROM  " . $this->prefix . "account 
            // WHERE accountTypeId = '" . $rec['id'] . "'
            // ORDER BY id ASC";

            $q = "SELECT id, name,  status
            FROM " . $this->prefix . "account AS t1
            WHERE NOT EXISTS (
                SELECT 1
                FROM account AS t2
                WHERE t2.parentId = t1.id
            ) and accountTypeId = '" . $rec['id'] . "'
            ORDER BY id ASC";



            $account[$i]['coa'] = $this->db->query($q)->getResultArray();
            $i++;
        }


        $outlet = "SELECT o.*, b.name as 'branch'
        FROM  " . $this->prefix . "outlet  as o
        left join branch as b on b.id = o.branchId 
        WHERE o.presence = 1 and o.status = 1
        ORDER BY  b.name ASC, o.name ASC";

        $template = "SELECT *
        FROM  " . $this->prefix . "template   
        WHERE  presence = 1 and tableName = 'journal'
        ORDER BY name ASC";

        $accountCashBank = "SELECT id, name
        FROM  " . $this->prefix . "account   
        WHERE  presence = 1 and cashBank = '1'
        ORDER BY id  ASC";

        $data = [
            "error" => false,
            "code" => 200,
            "account" => $account,
            "outlet" => $this->db->query($outlet)->getResult(),
            "template" => $this->db->query($template)->getResult(),
            "accountCashBank" => $this->db->query($accountCashBank)->getResult(),

        ];
        return $this->response->setJSON($data);
    }

    public function onSelectOutlet()
    {
        $outletId = $this->request->getVar()['outletId'];
        // $q = "SELECT id, accountId , status as 'outletStatus' 
        // FROM outlet_account WHERE outletId = $outletId AND STATUS = 1";
        $account = [];
        $accountTypeQuery = "SELECT t.id, t.name, COUNT(a.accountTypeId) AS 'total' 
        FROM  " . $this->prefix . "account_type AS t
        LEFT JOIN  " . $this->prefix . "account AS a ON a.accountTypeId = t.id
        WHERE a.presence = 1 AND a.`status` = 1 
        GROUP BY a.accountTypeId";
        $account = $this->db->query($accountTypeQuery)->getResultArray();
        $i = 0;
        foreach ($account as $rec) {
            $q = "SELECT t1.id, t1.name , o.status
            FROM account AS t1
            LEFT JOIN outlet_account AS o ON t1.id = o.accountId
            WHERE NOT EXISTS (
                SELECT 1
                FROM account AS t2
                WHERE t2.parentId = t1.id
            )  AND o.outletId = '$outletId' and accountTypeId = '" . $rec['id'] . "'
            ORDER BY t1.id ASC
            ";
            //  $account[$i]['q'] = $q;
            $account[$i]['coa'] = $this->db->query($q)->getResultArray();
            $account[$i]['total'] = count($this->db->query($q)->getResultArray());
            $i++;
        }


        $data = [
            "error" => false,
            "code" => 200,
            "items" => $account,
        ];
        return $this->response->setJSON($data);
    }

    public function detail()
    {
        $id = $this->request->getVar()['id'];

        $account = "SELECT id, name
        FROM " . $this->prefix . "account AS t1
        WHERE NOT EXISTS (
            SELECT 1
            FROM account AS t2
            WHERE t2.parentId = t1.id
        )
        ORDER BY id ASC";

        $outlet = "SELECT o.*, b.name as 'branch'
        FROM  " . $this->prefix . "outlet  as o
        left join branch as b on b.id = o.branchId 
        WHERE o.presence = 1 and o.status = 1
        ORDER BY  b.name ASC, o.name ASC";




        $header = "SELECT h.* , t.name as 'template'
        FROM journal_header as h  
        LEFT JOIN template as t on t.id = h.templateId
        WHERE h.id = '$id' and h.presence = 1   ";
        $headerArray = $this->db->query($header)->getResultArray()[0];

        $typeOfJournal = (int) $headerArray['cashbank'] == 1 ? "cashbank" : "journal";


        if ($typeOfJournal == "cashbank") {
            $items = "SELECT j.*, j.credit+j.debit as 'amount' , a.name
            FROM journal as j
            LEFT JOIN account as a on a.id = j.accountId
            where j.journalId = '$id' and j.presence = 1 and j.outletId != ''
            ORDER BY j.sorting ASC, j.id ASC ";
        } else {
            $items = "SELECT j.*, 0 as 'amount' , a.name
              FROM journal as j
            LEFT JOIN account as a on a.id = j.accountId
            where j.journalId = '$id' and j.presence = 1 
            ORDER BY j.sorting ASC, j.id ASC ";
        }
        $items = $this->db->query($items)->getResultArray();


        $q1 = "SELECT *, credit+debit as 'amount'  FROM journal 
            where journalId = '$id' and presence = 1 and outletId = ''
            ORDER BY sorting ASC, id ASC ";
        $itemsCashbank = $this->db->query($q1)->getResultArray();
        $cashbank = count($itemsCashbank) > 0 ? $itemsCashbank[0] : [
            "id" => 0,
            "accountId" => 0,
            "debit" => 0,
        ];

        $accountCashBank = "SELECT id, name
        FROM  " . $this->prefix . "account   
        WHERE  presence = 1 and cashBank = '1'
        ORDER BY id  ASC";


        $data = [
            "q" => $q1,
            "error" => false,
            "header" => $headerArray,
            "typeOfJournal" => (int) $headerArray['cashbank'] == 1 ? "cashbank" : "journal",
            "outlet" => $this->db->query($outlet)->getResult(),
            "accountCashBank" => $this->db->query($accountCashBank)->getResult(),
            "items" => $items,
            "cashbank" => array(
                "id" => $cashbank['id'],
                "accountId" => $cashbank['accountId'],
                "position" => $cashbank['debit'] > 0 ? 'debit' : 'credit'
            ),
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

            if ($post['typeOfJournal'] == "cashbank") {
                $newItem = [];
                $balance = 0;
                // foreach ($post['items'] as $row) {
                //     $isDebit = (float) $row['debit'];
                //     if ($isDebit > 0) {
                //         if ($post['cashbank']['position'] == 'credit') {
                //             $row['debit'] = (float) $isDebit;
                //             $row['credit'] = 0;
                //         } else if ($post['cashbank']['position'] == 'debit') {
                //             $row['debit'] = 0;
                //             $row['credit'] = (float) $isDebit;
                //         }
                //         $balance += $row['credit'] + $row['debit'];
                //         $newItem[] = $row;
                //     }
                // }
                $totalDebit = 0;
                $totalCredit = 0;
                foreach ($post['items'] as $row) { 
                    $totalDebit += (float)$row['debit'];
                    $totalCredit += (float)$row['credit']; 
                    $newItem[] = $row;
                }
                $abs = abs($totalDebit - $totalCredit);
                $newItem[] = array(
                    "accountId" => $post['cashbank']['accountId'],
                    "debit" =>   $totalDebit - $totalCredit < 0 ?  $abs : 0 ,
                    "credit" =>   $totalCredit - $totalDebit < 0 ? $abs : 0,
                    "description" => "Cash Bank",
                    "outletId" => "",
                );
                $post['items'] = $newItem;
            } else {
                foreach ($post['items'] as $row) {
                    $debit += $row['debit'];
                    $credit += $row['credit'];
                }
            }



            if (($credit - $debit) == 0) {

                //  $this->db->transStart();
                $debit = 0;
                $credit = 0;
                $dates = [];
                if ($post['recurringOfJournal'] == 'recurring') {

                    $startPeriod = $post['model']['startPeriod']['year'] . "-" . $post['model']['startPeriod']['month'] . "-" . $post['model']['startPeriod']['day'];
                    $endPeriod = $post['model']['endPeriod']['year'] . "-" . $post['model']['endPeriod']['month'] . "-" . $post['model']['endPeriod']['day'];

                    // Convert the date string to a DateTime object
                    $startDate = new \DateTime($startPeriod);
                    $endDate = new \DateTime($endPeriod);

                    // Create an interval between two dates
                    $interval = new \DateInterval('P1D'); // Interval 1 day
                    $dateRange = new \DatePeriod($startDate, $interval, $endDate);

                    // Loop to add each date to the array
                    $n = 0;
                    $nextMonth = 0;
                    $recurringPerMonth = $post['model']['recurringPerMonth'];
                    foreach ($dateRange as $date) {
                        // $dates[] = $date->format('Y-m-d');

                        $month = (int) $date->format('m');

                        if ($nextMonth != $month) {
                            $dates[] = $month . ' ' . $n;
                            $nextMonth = $month;
                            $n++;

                            if ($n > $recurringPerMonth) {
                                $n = 1;
                            }
                        }

                        if ((int) $date->format('d') == (int) $post['model']['dateOfJournal'] && ($n == 1)) {
                            $debit = 0;
                            $credit = 0;
                            $journalId = model("Core")->number("journal");
                            foreach ($post['items'] as $row) {
                                $journalDate = $date->format('Y-m-d');

                                $this->db->table($this->prefix . "journal")->insert([
                                    "journalId" => $journalId,
                                    "journalDate" => $date->format('Y-m-d'),
                                    "outletId" => $row['outletId'],
                                    "accountId" => $row['accountId'],
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

                            }
                            $this->db->table($this->prefix . "journal_header")->insert([
                                "id" => $journalId,
                                "templateId" => $post['templateId'],
                                "journalDate" => $date->format('Y-m-d'),
                                "ref" => $post['model']['ref'],
                                "note" => $post['model']['note'],
                                "totalCredit" => $credit,
                                "totalDebit" => $debit,
                                "cashbank" => $post['typeOfJournal'] == "cashbank" ? 1 : 0,
                                "presence" => 1,
                                "updateDate" => date("Y-m-d H:i:s"),
                                "updateBy" => model("Token")->userId(),
                                "inputDate" => date("Y-m-d H:i:s"),
                                "inputBy" => model("Token")->userId()
                            ]);

                        }

                    }
                    // END of Loop
                } else {
                    $journalDate = $post['model']['journalDate']['year'] . "-" . $post['model']['journalDate']['month'] . "-" . $post['model']['journalDate']['day'];

                    $journalId = model("Core")->number("journal");
                    foreach ($post['items'] as $row) {
                        $this->db->table($this->prefix . "journal")->insert([
                            "journalId" => $journalId,
                            "journalDate" => $journalDate,
                            "outletId" => $row['outletId'],
                            "accountId" => $row['accountId'],
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

                    }
                    $this->db->table($this->prefix . "journal_header")->insert([
                        "id" => $journalId,
                        "journalDate" => $journalDate,
                        "templateId" => $post['templateId'],
                        "ref" => $post['model']['ref'],
                        "note" => $post['model']['note'],
                        "totalCredit" => $credit,
                        "totalDebit" => $debit,
                        "cashbank" => $post['typeOfJournal'] == "cashbank" ? 1 : 0,
                        "presence" => 1,
                        "updateDate" => date("Y-m-d H:i:s"),
                        "updateBy" => model("Token")->userId(),
                        "inputDate" => date("Y-m-d H:i:s"),
                        "inputBy" => model("Token")->userId()
                    ]);




                }

                // if ($this->db->transStatus() != false) {
                //     $this->db->transComplete();
                // } else {
                //     $this->db->transRollback();
                // }
                $data = [
                    "error" => false,
                    "post" => $post,
                    "transaction" => $this->db->transStatus() === false ? false : true,
                    "code" => 200,
                    "dates" => $dates,
                ];
            } else {
                $data = [
                    "error" => true,
                    "code" => 400
                ];
            }

            $data = [
                "error" => true,
                "post" => $post,
                "code" => 400,
            ];

        }

        return $this->response->setJSON($data);
    }

    public function addRow()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
            $journalId = $post['id'];
            $this->db->table($this->prefix . 'journal')->insert([
                "journalId" => $journalId,
                "presence" => 4,
                "updateDate" => date("Y-m-d H:i:s"),
                "updateBy" => model("Token")->userId(),
                "inputDate" => date("Y-m-d H:i:s"),
                "inputBy" => model("Token")->userId()
            ]);
            $id = model("Core")->select("id", $this->prefix . "journal", "journalId = '$journalId' and presence = 4 order by inputDate DESC");
            $data = [
                "error" => false,
                "code" => 200,
                "item" => [
                    "id" => $id,
                ],
            ];


        }

        return $this->response->setJSON($data);
    }
    public function onUpdate()
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


            if ($post['typeOfJournal'] == "cashbank") {
                $newItem = [];
                $balance = 0;
                foreach ($post['items'] as $row) {
                    $isDebit = (float) $row['amount'];
                    if ($isDebit > 0) {
                        if ($post['cashbank']['position'] == 'credit') {
                            $row['debit'] = (float) $isDebit;
                            $row['credit'] = 0;
                        } else if ($post['cashbank']['position'] == 'debit') {
                            $row['debit'] = 0;
                            $row['credit'] = (float) $isDebit;
                        }
                        $balance += (float) $isDebit;
                        $newItem[] = $row;
                    }
                }
                $newItem[] = array(
                    "id" => $post['cashbank']['id'],
                    "accountId" => $post['cashbank']['accountId'],
                    "credit" => $post['cashbank']['position'] == 'credit' ? $balance : 0,
                    "debit" => $post['cashbank']['position'] == 'debit' ? $balance : 0,
                    "description" => "",
                    "outletId" => "",
                );
                $post['items'] = $newItem;
            }

            foreach ($post['items'] as $row) {
                $debit += $row['debit'];
                $credit += $row['credit'];
            }


            if (($credit - $debit) == 0) {

                $this->db->transStart();


                $debit = 0;
                $credit = 0;
                $journalId = $post['journalId'];
                $this->db->table($this->prefix . "journal")->update([
                    "presence" => 4,
                    "updateDate" => date("Y-m-d H:i:s"),
                    "updateBy" => model("Token")->userId(),
                ], " journalId =  '" . $journalId . "' ");

                $journalDate = $post['model']['journalDate']['year'] . "-" . $post['model']['journalDate']['month'] . "-" . $post['model']['journalDate']['day'];


                foreach ($post['items'] as $row) {

                    $update = [
                        "outletId" => $row['outletId'],
                        "accountId" => $row['accountId'],
                        "debit" => $row['debit'],
                        "credit" => $row['credit'],
                        "presence" => 1,
                        "description" => $row['description'],
                        "updateDate" => date("Y-m-d H:i:s"),
                        "updateBy" => model("Token")->userId(),
                    ];

                    $this->db->table($this->prefix . "journal")->update($update, " id =  '" . $row['id'] . "' ");
                    $debit += $row['debit'];
                    $credit += $row['credit'];
                }

                $this->db->table($this->prefix . "journal_header")->update([
                    "journalDate" => $journalDate,
                    "ref" => $post['model']['ref'],
                    "note" => $post['model']['note'],
                    "totalCredit" => $credit,
                    "totalDebit" => $debit,
                    "updateDate" => date("Y-m-d H:i:s"),
                    "updateBy" => model("Token")->userId(),
                ], " id =  '$journalId' ");

                $this->db->table($this->prefix . "journal")->update([
                    "presence" => 0,
                    "updateDate" => date("Y-m-d H:i:s"),
                    "updateBy" => model("Token")->userId(),
                ], " presence =  4 ");


                if ($this->db->transStatus() != false) {
                    $this->db->transComplete();
                } else {
                    $this->db->transRollback();
                }
                $data = [
                    "error" => false,
                    // "accountBalance" => $accountBalance,
                    // "transaction" => $this->db->transStatus() === false ? false : true,
                    "code" => 200,
                    "post" => $post,
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


    public function onSorting()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
            $journalId = $post['journalId'];
            $i = 1;
            foreach ($post['order'] as $row) {
                $this->db->table($this->prefix . 'journal')->update([
                    "sorting" => $i,
                    "updateDate" => date("Y-m-d H:i:s"),
                    "updateBy" => model("Token")->userId(),
                ], " id = '" . $row . "'");
                $i++;
            }

            $id = model("Core")->select("id", $this->prefix . "journal", "journalId = '$journalId' and presence = 4 order by inputDate DESC");
            $data = [
                "error" => false,
                "code" => 200,
                "item" => [
                    "id" => $id,
                ],
            ];
        }

        return $this->response->setJSON($data);
    }

    function test()
    {

        $startPeriod = "2024-02-01";
        $endPeriod = "2024-07-01";

        // Ubah string tanggal menjadi objek DateTime
        $startDate = new \DateTime($startPeriod);
        $endDate = new \DateTime($endPeriod);

        // Buat interval antara dua tanggal
        $interval = new \DateInterval('P1D'); // Interval 1 hari
        $dateRange = new \DatePeriod($startDate, $interval, $endDate);

        // Data tanggal yang akan ditampilkan
        $dates = [];

        // Loop untuk menambahkan setiap tanggal ke dalam array
        foreach ($dateRange as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        print_r($dates);
    }

}
