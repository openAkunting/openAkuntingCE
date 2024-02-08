<?php

namespace App\Controllers;

use OpenApi\Annotations as OA;

class Account extends BaseController
{
    function __construct()
    {
        if (model("Token")->checkValidToken() == '') {
            //   exit;
        }
    }

    public function index()
    {

        $q = "SELECT a.*, '' as 'checkBox' , p.description as 'parent'
        FROM ".$this->prefix."account as a  
        left join ".$this->prefix."account as p on p.id = a.parentId
        WHERE a.presence = 1 and a.parentId != '0'
        ORDER BY a.id ASC, a.parentId ASC";

        $view = isset($this->request->getVar()['view']) ? $this->request->getVar()['view'] : false;
        $data = [
            "error" => false,
            "items" => $view == 'tree' ? self::generatePyramid() : $this->db->query($q)->getResult(),
        ];
        return $this->response->setJSON($data);
    }

    public function generatePyramid()
    {
        $query = $this->db->query("SELECT * FROM  " . $this->prefix . "account WHERE parentId = '1' ORDER BY id ASC ");

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
        FROM  " . $this->prefix . "account 
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

    public function masterAccountInsert()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
            $this->db->table($this->prefix . "account")->insert([
                "id" => $post['items']['id'],
                "parentId" => $post['items']['parentId'],
                "nature" => $post['items']['nature'],
                "cashBank" => $post['items']['cashBank'],
                "status" => $post['items']['status'],
                "typeOfAccount" => $post['items']['typeOfAccount'],
                "description" => $post['items']['description'],
                "updateDate" => date("Y-m-d H:i:s"),
                "updateBy" => model("Token")->userId(),
                "insertDate" => date("Y-m-d H:i:s"),
                "insertBy" => model("Token")->userId(),
            ]);

            $data = [
                "error" => false,
                "code" => 200
            ];
        }
    }
    public function masterAccountDelete()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
            foreach ($post['items'] as $row) {
                if ($row['checkBox'] == 'true') { 
                    $this->db->table($this->prefix . "account")->update([
                        "presence" => 0,
                        "updateDate" => date("Y-m-d H:i:s"),
                        "updateBy" => model("Token")->userId()
                    ], "id = '" . $row['id'] . "' ");
                }
            }
            $data = [
                "error" => false,
                "code" => 200
            ];
        }
        return $this->response->setJSON($data);
    }

    public function masterAccountUpdate()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
            $this->db->table($this->prefix . "account")->update([
                "nature" => $post['items']['nature'],
                "cashBank" => $post['items']['cashBank'],
                "status" => $post['items']['status'],
                "typeOfAccount" => $post['items']['typeOfAccount'],
                "description" => $post['items']['description'],
                "updateDate" => date("Y-m-d H:i:s"),
                "updateBy" => model("Token")->userId()
            ], "id = '" . $post['items']['id'] . "' ");

            $data = [
                "error" => false,
                "code" => 200
            ];
        }

        return $this->response->setJSON($data);

    }

    public function masterAccountUpdateAll()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
            foreach ($post['items'] as $row) {

                $this->db->table($this->prefix . "account")->update([
                    "nature" => $row['nature'],
                    "cashBank" => $row['cashBank'],
                    "status" => $row['status'],
                    "typeOfAccount" => $row['typeOfAccount'],
                    "description" => $row['description'],
                    "updateDate" => date("Y-m-d H:i:s"),
                    "updateBy" => model("Token")->userId()
                ], "id = '" . $row['id'] . "' ");
            }
            $data = [
                "error" => false,
                "code" => 200
            ];
        }

        return $this->response->setJSON($data);

    }
}
