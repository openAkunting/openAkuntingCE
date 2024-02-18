<?php

namespace App\Controllers;

class Export extends BaseController
{
    function __construct()
    {

    }
    public function index()
    {

        $data = array(
            "note" => "ok",
        );

        // echo  ;
        return $this->response->setJSON($data);
    }
 

    public function account()
    {
         
        $q = "SELECT a.*,  p.name as 'parent', t.name as 'accountType'
        FROM " . $this->prefix . "account as a  
        left join " . $this->prefix . "account AS p ON p.id = a.parentId
        left join " . $this->prefix . "account_type AS t ON t.id = a.accountTypeId
        WHERE a.presence = 1 and a.parentId != '0'
        ORDER BY a.id ASC, a.parentId ASC";
 
        $data = "id;name;typeOfAccount";
        $response = $this->response
            ->setHeader('Content-Type', 'application/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="account.csv"');

        // Tambahkan konten CSV ke tanggapan

        foreach($this->db->query($q)->getResultArray() as $row){
            $data .=  $row['id'].";".$row['name'].";".$row['parent']."\n";
        }

        $response->appendBody($data);

        // Return response
        return $response;
      
         
    }
}
