<?php

namespace App\Controllers;

class Outlet extends BaseController
{
    function __construct()
    {
        if (model("Token")->checkValidToken() == '') {
            //   exit;
        }
    }

    public function index()
    {
        $id = $this->request->getVar()['id'];

        $q = "SELECT id, branchId, name, status 
        FROM  " . $this->prefix . "outlet  
        WHERE presence = 1 and branchId =  $id 
        ORDER BY name ASC";
        $item = $this->db->query($q)->getResultArray();
        for($i = 0; $i <  count($item) ; $i++){
            $item[$i]['totalCoA'] = model("Core")->select("count(id)","outlet_account","status = 1 and outletId = ".$item[$i]['id']);
        }
        $data = [
            "error" => false,
            "items" => $item , 
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
            $this->db->table($this->prefix . "outlet")->insert([
                "name" => $row['name'],
                "branchId" => $row['branchId'],
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
                $this->db->table($this->prefix . "outlet")->update([
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
            $this->db->table($this->prefix . "outlet")->update([
                "presence" => 0,
                "updateBy" => model("Token")->userId(),
                "updateDate" => date("Y-m-d H:i:s"),
            ], "id = '" . $post['id'] . "' ");

            $data = [
                "error" => false,
                "code" => 200,
            ];
        }

        return $this->response->setJSON($data);
    }


    public function coa()
    {
        $q = "SELECT id, name, balanceSheet, profitAndLoss, status, '' as 'checkBoxAll'
        FROM " . $this->prefix . "account_type  
        WHERE presence = 1 
        ORDER BY id ASC ";
        $outletId = $this->request->getVar()['outletId'];
        $branchId =  model("Core")->select("branchId","outlet"," presence = 1 and  id = ". $outletId );
        $item = $this->db->query($q)->getResultArray();
        $i = 0;
        foreach ($item as $row) {

            $account = "SELECT id, name, cashBank
            FROM " . $this->prefix . "account AS t1
            WHERE NOT EXISTS (
                SELECT 1
                FROM account AS t2
                WHERE t2.parentId = t1.id
            )
            AND presence = 1 and accountTypeId = " . $item[$i]['id'] . "
            ORDER BY id ASC";

            $item[$i]['account'] = $this->db->query($account)->getResultArray();
            $n = 0;
            foreach ($item[$i]['account'] as $row) {
                $item[$i]['account'][$n]['status'] = model("Core")->select("status", "outlet_account", "outletId = $outletId and accountId = '" . $item[$i]['account'][$n]['id'] . "' ");
                $n++;
            }

            $i++;
        }

        $data = [
            "error" => false,
            "items" => $item,
            "outlet" =>  model("Core")->select("name","outlet"," presence = 1 and  id = ". $outletId ),
            "branch" =>  model("Core")->select("name","branch"," presence = 1 and  id = ". $branchId  ),
            
        ];
        return $this->response->setJSON($data);
    }

    public function onSaveCoa()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
            $outletId = $post['outletId'];
            foreach ($post['items'] as $row) {

                foreach ($row['account'] as $a) {
                    $id = model("Core")->select("id", "outlet_account", "accountId = '" . $a['id'] . "' and  outletId = $outletId ");
                    if ($id) {
                        $this->db->table($this->prefix . "outlet_account")->update([
                            "status" => $a['status'] == null ? "0" : $a['status'],
                            "presence" => 1,
                            "updateBy" => model("Token")->userId(),
                            "updateDate" => date("Y-m-d H:i:s"),
                        ], "accountId = '" . $a['id'] . "' and  outletId = $outletId ");
                    } else { 
                        $this->db->table($this->prefix . "outlet_account")->insert([
                            "outletId" => $outletId,
                            "accountId" => $a['id'],
                            "status" => $a['status'] == null ? "0" : $a['status'],
                            "presence" => 1,
                            "updateBy" => model("Token")->userId(),
                            "updateDate" => date("Y-m-d H:i:s"),
                            "inputBy" => model("Token")->userId(),
                            "inputDate" => date("Y-m-d H:i:s"),
                        ]);
                    }

                }

            }
            $data = [
                "error" => false,
                "code" => 200,
                "post" => $post,
            ];
        }

        return $this->response->setJSON($data);
    }
}
