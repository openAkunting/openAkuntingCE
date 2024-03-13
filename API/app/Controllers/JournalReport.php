<?php

namespace App\Controllers;
 
class JournalReport extends BaseController
{
    protected $maxDay = null;
    function __construct()
    {
        $this->maxDay = 100;
        if (model("Token")->checkValidToken() == '') {
            //   exit;
        }
    }

    public function index()
    {
        $startDate = $this->request->getVar()['startDate'];
        $endDate = $this->request->getVar()['endDate'];
      //  $isTable = isset($this->request->getVar()['table']) ? $this->request->getVar()['table'] : "journal";
 
        $dateObj1 = date_create($startDate);
        $dateObj2 = date_create($endDate);
        $interval = date_diff($dateObj1, $dateObj2);
        $totalDays = $interval->days;
       
        if ($totalDays < $this->maxDay) {
            $data = self::journalReport($this->request->getVar());
        } else {
            $data = array(
                "error" => true,
                "note" => "Max " . $this->maxDay . " days"
            );
        }

        return $this->response->setJSON($data);
    }

    private function journalReport($get)
    {
        $rest = [];
        $startDate = $get['startDate'];
        $endDate = $get['endDate'];
        $isTable = isset($get['table']) ? $get['table'] : "journal";
  
        $table = $this->prefix . $isTable . '_header';
        $q = "  SELECT journalDate ,  COUNT(id) as 'total'
                FROM  $table 
                WHERE journalDate between '$startDate' AND '$endDate'
                GROUP By journalDate";

        $journalHeaderGroup = $this->db->query($q)->getResultArray();
        foreach ($journalHeaderGroup as $header) {
            $journalData = [];

            $q = "SELECT * FROM $table WHERE DATE(journalDate) = '" . $header['journalDate'] . "' AND presence = 1  ";
            $journalHeader = $this->db->query($q)->getResultArray();

            foreach ($journalHeader as $row) {
                $journals = [];
                $tableJournal = $this->prefix . $isTable;

                $j = "SELECT 
                        j.id, j.accountId, a.name as 'account', b.name as 'branch' ,  b.id as 'branchId',
                        o.name as 'outlet', o.id as 'outletId', j.description, j.debit, j.credit
                    FROM  $tableJournal  as j
                    left join account as a on a.id = j.accountId
                    left join outlet as o on o.id = j.outletId
                    left join branch as b on b.id = o.branchId
                    WHERE  j.presence = 1 and j.journalId = '" . $row['id'] . "'
                    ORDER BY j.id ASC";

                $journal = $this->db->query($j)->getResultArray();


                $debit = 0;
                $credit = 0;
                foreach ($journal as $x) {
                    $debit += $x['debit'];
                    $credit += $x['credit'];
                }

                $journalData[] = array(
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



            $rest[] = array(
                "journalDate" => $header['journalDate'],
                "header" => $journalData,
            );

        }


        $data = [
            "error" => false,
            "code" => 200, 
            "data" => $rest,

        ];

        return $data;
    }
}
