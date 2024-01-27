<?php

namespace App\Controllers;

class Home extends BaseController
{
    function __construct()
    {
        if (model("Token")->checkValidToken() == '') {
            exit;
        } 
    }
    public function index()
    {
        return view('welcome_message');
    }

    public function hello()
    {
        if (model("Token")->checkValidToken() == '') {
            $data = array(
                "error" => true,
                "code" => 401,
            );
        } else {

            $tenantId = model("Token")->getCurrentUser()->tenantId;
            $q = "SELECT *
            FROM  a1_journal  
            WHERE  tenantId = '$tenantId' ";
            $items = $this->db->query($q)->getResultArray();

            $data = array(
                "error" => false,
                "code" => 202,
                "q" => $q,
                "items" => $items,
                "get" => $this->request->getVar(),
                "user" => model("Token")->getCurrentUser(),
            );
        }

        return $this->response->setJSON($data);

    }
}
