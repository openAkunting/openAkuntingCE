<?php

namespace App\Controllers;

use OpenApi\Annotations as OA;

class Account extends BaseController
{
    function __construct()
    {
        if (model("Token")->checkValidToken() == '') {
              exit;
        }

        // rules check here
        if (model("Token")->checkRule('Chart Of Account')[1] != 1) { 
             echo "500 : Error Role";
             exit;
        }
    }

    public function chartOfAccount()
    {

        $q = "SELECT a.*, '' as 'checkBox' , p.name as 'parent', t.name as 'accountType'
        FROM " . $this->prefix . "account as a  
        left join " . $this->prefix . "account AS p ON p.id = a.parentId
        left join " . $this->prefix . "account_type AS t ON t.id = a.accountTypeId
        WHERE a.presence = 1 and a.parentId != '0'
        ORDER BY a.id ASC, a.parentId ASC";

        $acccountType = "SELECT *
        FROM " . $this->prefix . "account_type as a  
        WHERE presence = 1 
        ORDER BY id ASC ";

        $acccountType = $this->db->query($acccountType)->getResultArray();
        $i = 0;
        foreach ($acccountType as $row) {
            $acccountType[$i]['normalBalance'] = $row['normalBalance'] == 'D' ? '[D] Debit' : '[C] Credit';
            $acccountType[$i]['position'] = $row['position'] == 'BS' ? '[BS] Balance Sheet' : '[PL] Profit & Loss';

            $i++;
        }


        $view = isset($this->request->getVar()['view']) ? $this->request->getVar()['view'] : false;
        $data = [
            "error" => false,
            "items" => $view == 'tree' ? self::generatePyramid() : $this->db->query($q)->getResult(),
            "acccountType" => $acccountType,

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

    public function chartOfAccountInsert()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
            $id = $post['item']['id'] == '1' ? $post['newCoA']['id'] : $post['item']['id'] . '.' . $post['newCoA']['id'];
            $this->db->table($this->prefix . "account")->insert([
                "id" => $id,
                "parentId" => $post['item']['id'],
                "accountTypeId" => $post['newCoA']['accountTypeId'],
                "name" => $post['newCoA']['name'],
                "updateDate" => date("Y-m-d H:i:s"),
                "updateBy" => model("Token")->userId(),
                "inputDate" => date("Y-m-d H:i:s"),
                "inputBy" => model("Token")->userId(),
            ]);

            $data = [
                "error" => false,
                "code" => 200
            ];
            return $this->response->setJSON($data);
        }
    }
    public function chartOfAccountDelete()
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

    public function chartOfAccountUpdate()
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
                    "balance" => $row['balance'],
                    "accountTypeId" => $row['accountTypeId'],
                    "name" => $row['name'],
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


    public function accountType()
    {
        $acccountType = "SELECT *, '' as 'checkBox'
        FROM " . $this->prefix . "account_type 
        WHERE presence = 1 
        ORDER BY id ASC ";
        $acccountType = $this->db->query($acccountType)->getResultArray();
        $data = [
            "error" => false,
            "items" => $acccountType,
        ];
        return $this->response->setJSON($data);
    }

    public function accountTypeUpdate()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
            foreach ($post['items'] as $row) {

                $this->db->table($this->prefix . "account_type")->update([
                    "position" => $row['position'],
                    "normalBalance" => $row['normalBalance'],
                    "name" => $row['name'],
                    "status" => $row['status'],
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
    public function accountTypeInsert()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
            $row = $post['model']; 
            $this->db->table($this->prefix . "account_type")->insert([
                "position" => $row['position'],
                "normalBalance" => $row['normalBalance'],
                "name" => $row['name'],
                "status" => 1,
                "id" => $row['id'],
                "updateDate" => date("Y-m-d H:i:s"),
                "updateBy" => model("Token")->userId(),
                "inputDate" => date("Y-m-d H:i:s"),
                "inputBy" => model("Token")->userId() 
            ]);
           
            $data = [
                "error" => false,
                "code" => 200
            ];
        }

        return $this->response->setJSON($data);

    }

    public function accountTypeDelete()
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
                    $this->db->table($this->prefix . "account_type")->update([
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
}
