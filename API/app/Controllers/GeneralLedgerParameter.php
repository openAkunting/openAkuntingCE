<?php

namespace App\Controllers;
use OpenApi\Annotations as OA;
 
class GeneralLedgerParameter extends BaseController
{
    function __construct()
    {
        if ( model("Token")->checkValidToken() == '' ) {
           exit;
        } 
    }
    
    public function index()
    { 
        $q = "SELECT * FROM  ".$this->prefix."global_parameter  ORDER BY id  ";
        $items = $this->db->query($q)->getResultArray();
        $data = array(
            "error" => false,
            "code" => 200,
            "items" => $items,
        );
        return $this->response->setJSON($data);
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
                $this->db->table($this->prefix . "global_parameter")->update([
                    "value" => $row['value'], 
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
