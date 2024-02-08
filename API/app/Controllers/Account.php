<?php

namespace App\Controllers;
use OpenApi\Annotations as OA;
 
class Account extends BaseController
{
    function __construct()
    {
        if ( model("Token")->checkValidToken() == '' ) {
        //   exit;
        } 
    }
    
    public function index()
    { 
        $data = [ 
            "error" => false,
            "account" => self::generatePyramid(), 
        ]; 
        return $this->response->setJSON($data); 
    }

    public function generatePyramid()
    {
        $query = $this->db->query("SELECT * FROM  ".$this->prefix."account WHERE parentId = '1' ORDER BY id ASC ");

        $rootAccounts = $query->getResult();
        $pyramid = [];

        foreach ($rootAccounts as $rootAccount) {
            $account = $this->buildPyramid($rootAccount);
            $pyramid[] = $account;
        }

        return $pyramid;

    }

    protected function buildPyramid($account)
    {
        $q = "SELECT *
        FROM  ".$this->prefix."account 
        WHERE  presence = 1 AND   parentid = '$account->id'  ORDER BY  id ASC ";

        $query = $this->db->query($q);
        $children = $query->getResult();

        $account->children = [];

        foreach ($children as $child) {
            $childAccount = $this->buildPyramid($child);
            $account->children[] = $childAccount;
        }

        return $account;
    }

  
 
    public function update()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true, 
            "code" => 400
        ];
        if ($post) {  
          

            foreach($post['items'] as $row){
                $this->db->table($this->prefix . "auto_number")->update([
                    "prefix" => $row['prefix'], 
                    "digit" => $row['digit'],  
                    "updateDate" => date("Y-m-d H:i:s"),
                ],"id = '".$row['id']."' ");
            }
            $data = [
                "error" => false, 
                "code" => 200
            ];
        }

        return $this->response->setJSON($data);

    }
}
