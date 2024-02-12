<?php

namespace App\Controllers;

use OpenApi\Annotations as OA;

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
        $user = "SELECT *
        FROM  " . $this->prefix . "journal 
        WHERE  presence = 1 
        ORDER BY JournalDate DESC";

        $data = [
            "error" => false,
            "items" => $this->db->query($user)->getResult(),

        ];
        return $this->response->setJSON($data);
    }

    public function selectItems()
    {

        $account = "SELECT *
        FROM  " . $this->prefix . "account  
        WHERE presence = 1  and status  = 1 and parentId != 0
        ORDER BY  name ASC";

        $outlet = "SELECT *
        FROM  " . $this->prefix . "outlet  
        WHERE presence = 1  
        ORDER BY  name ASC";

        $data = [
            "error" => false,
            "account" => $this->db->query($account)->getResult(),
            "outlet" => $this->db->query($outlet)->getResult(),
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
            $this->db->transStart();
            $debit = 0;
            $credit = 0;
            
            $jurnalId =  model("Core")->number("journal");
            foreach ($post['items'] as $row) {
                $this->db->table($this->prefix . "journal")->insert([ 
                    "jurnalId" => $jurnalId,
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
                $debit +=  $row['debit'];
                $credit +=  $row['credit']; 
                
            }

            $this->db->table($this->prefix . "journal_header")->insert([ 
                "id" => $jurnalId, 
                "JournalDate" => date("Y-m-d"), 
                "ref" => "",
                "totalCredit" => $credit,
                "totalDebit" => $debit,
                
                "presence" => 1,
                "updateDate" => date("Y-m-d H:i:s"),
                "updateBy" => model("Token")->userId(),
                "inputDate" => date("Y-m-d H:i:s"),
                "inputBy" => model("Token")->userId()
            ]);

            if(( ($credit - $debit)  == 0) && $this->db->transStatus() != false ){
                $this->db->transComplete();
            }else{
                $this->db->transRollback();
            }


          
            
            $data = [
                "error" => false,
                "transaction" => $this->db->transStatus() === false ? false : true,
                "code" => 200
            ];
        }

        return $this->response->setJSON($data);
    }
}
