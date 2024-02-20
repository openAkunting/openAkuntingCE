<?php

namespace App\Controllers;

use OpenApi\Annotations as OA;

class Account extends BaseController
{
    function __construct()
    {
        if (model("Token")->checkValidToken() == '') {
            //      exit;
        }

        // rules check here
        // if (model("Token")->checkRule('Chart Of Account')[1] != 1) { 
        //  echo "500 : Error Role";
        //  exit;
        //  }
    }

    public function chartOfAccount()
    {
        $accountTree = [];
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


        $col = $this->db->query('SHOW COLUMNS  FROM `account` ')->getResultArray();
        $header = [];
        $i = 0;
        foreach ($col as $row) {
            $header[] = array(
                "column" => $row['Field'],
                "checkBox" => true,
            );
            if ($i > 4)
                break;
            $i++;
        }

        $accountTreeQuery = "SELECT id, parentId
        FROM " . $this->prefix . "account";
        $accountTree = $this->db->query($accountTreeQuery)->getResultArray();


        $item = $this->db->query($q)->getResultArray();
        $data = [
            "error" => false,
            "items" => $view == 'tree' ? self::generatePyramid() : $item,
            "acccountType" => $acccountType,
            "columnHeader" => $header,
            //   "level" => model("Account")->getLevel("1110.001.000",$accountTree),
            "accountTree" => $accountTree,
        ];
        return $this->response->setJSON($data);
    }

    function test()
    {
        $q = "SELECT id, parentId, name
        FROM " . $this->prefix . "account";
        $item = $this->db->query($q)->getResultArray();


        // ID yang ingin diperiksa
        $idToCheck = "1110.001.000";

        // Memanggil fungsi untuk mendapatkan level/turunan ID
        $level = model("Account")->getLevel($idToCheck, $item);

        if ($level >= 0) {
            echo "ID $idToCheck berada di level $level.";
        } else {
            echo "ID $idToCheck tidak ditemukan dalam database.";
        }
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
                    "cashBank" => $row['cashBank'],
                    "status" => $row['status'],
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

    public function onImportCoA()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {

            for ($i = 0; $i < count($post['sheetData']); $i++) {
                if ($i > 0) {
                    $this->db->table($this->prefix . "account")->insert([
                        "id" => $post['sheetData'][$i][0] == "" ? "0" : $post['sheetData'][$i][0],
                        "name" => isset($post['sheetData'][$i][2]) ? $post['sheetData'][$i][2] : "",
                        "parentId" => $post['sheetData'][$i][1] == "" ? "0" : $post['sheetData'][$i][1],
                        "presence" => 1,
                        "updateDate" => date("Y-m-d H:i:s"),
                        "updateBy" => model("Token")->userId(),
                        "inputDate" => date("Y-m-d H:i:s"),
                        "inputBy" => model("Token")->userId(),
                    ]);
                }
            }
            $data = [
                "sheetHeader" => $post['sheetHeader'],
                "sheetData" => $post['sheetData'][23],
                "error" => false,
                "code" => 200
            ];
            return $this->response->setJSON($data);
        }
    }

    public function exportAccount()
    {

    }
}
