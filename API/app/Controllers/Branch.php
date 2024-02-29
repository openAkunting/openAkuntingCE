<?php

namespace App\Controllers;

class Branch extends BaseController
{
    function __construct()
    {
        if (model("Token")->checkValidToken() == '') {
            //   exit;
        }
    }

    public function index()
    {
        $q = "SELECT * 
        FROM  " . $this->prefix . "branch  
        WHERE presence = 1 
        ORDER BY name ASC";

        $data = [
            "error" => false,
            "items" => $this->db->query($q)->getResult(),

        ];
        return $this->response->setJSON($data);
    }

    public function addNew()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
            $row = $post;

            $this->db->table($this->prefix . "branch")->insert([
                "name" => $row['name'],
                "status" => 1, 
                "updateBy" => model("Token")->userId(),
                "updateDate" => date("Y-m-d H:i:s"),
                "inputBy" => model("Token")->userId(),
                "inputDate" => date("Y-m-d H:i:s"),
            ]);


            $data = [
                "error" => false,
                "code" => 200,
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
            foreach ($post as $row) {
                $this->db->table($this->prefix . "branch")->update([
                    "name" => $row['name'],
                    "status" => $row['status'],
                    "updateBy" => model("Token")->userId(),
                    "updateDate" => date("Y-m-d H:i:s"),
                ], "id = '" . $row['id'] . "' ");
            }

            $data = [
                "error" => false,
                "code" => 200,
                "post" => $post,
            ];
        }

        return $this->response->setJSON($data);
    }
 
    public function onDelete()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
            $this->db->table($this->prefix . "branch")->update([
                "presence" => 0,
                "updateBy" => model("Token")->userId(),
                "updateDate" => date("Y-m-d H:i:s"),
            ], "id = '" . $post['id'] . "' ");


            $this->db->table($this->prefix . "outlet")->update([
                "presence" => 0,
                "updateBy" => model("Token")->userId(),
                "updateDate" => date("Y-m-d H:i:s"),
            ], "branchId = '" . $post['id'] . "' ");

            $data = [
                "error" => false,
                "code" => 200,
            ];
        }

        return $this->response->setJSON($data);
    }
 

}
