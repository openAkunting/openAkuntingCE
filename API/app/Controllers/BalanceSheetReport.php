<?php

namespace App\Controllers; 

class BalanceSheetReport extends BaseController
{
    protected $maxDay = null;
    function __construct()
    {
        $this->maxDay = 33;
        if (model("Token")->checkValidToken() == '') {
            //   exit;
        }
    }

    public function index()
    {
     //   $startDate = $this->request->getVar()['startDate'];
     //   $endDate = $this->request->getVar()['endDate'];
        $startDate = '2024-01-01';
        $endDate = '2024-01-31'; 
     
        $dateObj1 = date_create($startDate);
        $dateObj2 = date_create($endDate);
        $interval = date_diff($dateObj1, $dateObj2);
        $totalDays = $interval->days;
       
        if ($totalDays < $this->maxDay) {
            $data = self::report($this->request->getVar());
        } else {
            $data = array(
                "error" => true,
                "note" => "Max " . $this->maxDay . " day"
            );
        }

        return $this->response->setJSON($data);
    }

    private function report($get)
    {
        $rest = [];
        $startDate = $get['startDate'];
        $endDate = $get['endDate'];
       
       
        $isTable = isset($get['table']) ? $get['table'] : "journal";
  
        $table = $this->prefix . $isTable;
        $q = "  SELECT journalDate ,  COUNT(id) as 'total'
                FROM  $table 
                WHERE journalDate between '$startDate' AND '$endDate'
                GROUP By journalDate";

        $journal = $this->db->query($q)->getResultArray();
        


        $data = [
            "error" => false,
            "code" => 200, 
            "data" => $rest,

        ];

        return $data;
    }
}
