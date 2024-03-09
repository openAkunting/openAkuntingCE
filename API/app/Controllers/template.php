<?php

namespace App\Controllers;

class Template extends BaseController
{
    function __construct()
    {
        if (model("Token")->checkValidToken() == '') {
            //   exit;
        }
    }
    /**
     * TEMPLATE
     */
    public function onSaveAsTemplate()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
             $this->db->transStart();
            $id = model("Core")->select("id", "template", "name='" . $post['nameOfTemplate'] . "' and tableName = '" . $post['tableName'] . "' and  presence = 1 ");
             
            if ($post['tableName'] == 'cashbank') {
                $newItem = [];
                $balance = 0;
                foreach ($post['items'] as $row) { 
                    $isDebit = (float) $row['debit'];
                    if ($isDebit > 0) { 

                        if ($post['cashbank']['position'] == 'credit') {
                            $row['debit'] = (float) $isDebit;
                            $row['credit'] = 0;
                        } else if ($post['cashbank']['position'] == 'debit') {
                            $row['debit'] = 0;
                            $row['credit'] = (float) $isDebit;
                        }

                        $balance += $row['credit'] + $row['debit'];

                        $newItem[] = $row;
                    }
                }
                $newItem[] = array(
                    "accountId" => $post['cashbank']['accountId'],
                    "credit" => $post['cashbank']['position'] == 'credit' ? $balance : 0,
                    "debit" => $post['cashbank']['position'] == 'debit' ? $balance : 0,
                    "description" => "",
                    "outletId" => "",
                );
                $post['items'] = $newItem;
            }
            
            
            if ($id) {
                /**
                 * OVERWRITE
                 */
                $this->db->table($this->prefix . "template")->update([
                    "id" => $id,
                    "ref" => $post['model']['ref'],
                    "note" => $post['model']['note'],
                    "presence" => 1,
                    "updateDate" => date("Y-m-d H:i:s"),
                    "updateBy" => model("Token")->userId(),
                ], " id = '$id'");

                $this->db->table($this->prefix . "journal_template")->update([
                    "presence" => 0,
                    "updateDate" => date("Y-m-d H:i:s"),
                    "updateBy" => model("Token")->userId(),
                ], " templateId ='$id' ");

                foreach ($post['items'] as $row) {
                    /**
                     * JOURNAL TEMPLATE
                     */  
                    $this->db->table($this->prefix . "journal_template")->insert([
                        "templateId" => $id,
                        "outletId" => $row['outletId'],
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

                }


            } else {


                $id = model("Core")->number("template");
                $this->db->table($this->prefix . "template")->insert([
                    "id" => $id,
                    "name" => $post['nameOfTemplate'],
                    "tableName" => $post['tableName'],
                    "ref" => $post['model']['ref'],
                    "note" => $post['model']['note'],
                    "presence" => 1,
                    "updateDate" => date("Y-m-d H:i:s"),
                    "updateBy" => model("Token")->userId(),
                    "inputDate" => date("Y-m-d H:i:s"),
                    "inputBy" => model("Token")->userId()
                ]);

                foreach ($post['items'] as $row) {

                    /**
                     * JOURNAL TEMPLATE
                     */
                    if ($row['accountId'] || $row['debit'] || $row['credit']) {
                        $this->db->table($this->prefix . "journal_template")->insert([
                            "templateId" => $id,
                            "outletId" => $row['outletId'],
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
                    }


                }
            }

            $this->db->table("journal_template")->delete(" presence = 0");

            if ($this->db->transStatus() != false) {
                $this->db->transComplete();
            } else {
                $this->db->transRollback();
            }

            $data = [
                "error" => false,
                "post" => $post,
              //  "transaction" => $this->db->transStatus() === false ? false : true,
                "code" => 200
            ];
        }

        return $this->response->setJSON($data);
    }

    public function loadTemplate()
    {
        $templateId = $this->request->getVar()['templateId'];
 
        $template = "SELECT *
        FROM  " . $this->prefix . "template   
        WHERE  presence = 1 AND id = '$templateId'
        ORDER BY name ASC";

        $journal_template = "SELECT *
        FROM  " . $this->prefix . "journal_template   
        WHERE  presence = 1 AND templateId  = '$templateId' and outletId != '' 
        ORDER BY id ASC";

        $cashbank2= "SELECT accountId, credit, debit
        FROM  " . $this->prefix . "journal_template   
        WHERE  presence = 1 AND templateId  = '$templateId' and outletId = '' 
        ORDER BY id ASC";
        $cashbank2 = $this->db->query($cashbank2)->getResultArray();
        $cashbank =  count($cashbank2) >0 ? $cashbank2[0]: [
            "accountId" => 0,
            "debit" => 0, 
        ];

        $data = array(  
            "template" => $this->db->query($template)->getResult()[0],
            "journal_template" => $this->db->query($journal_template)->getResult(),
            "cashbank" => array(
                "accountId" => $cashbank['accountId'],
                "position" => $cashbank['debit'] > 0 ? 'debit' : 'credit'
            ),
        );
        return $this->response->setJSON($data);
    }

    public function onChangeTemplate()
    {
        $tableName = $this->request->getVar()['tableName'];

        $template = "SELECT *
        FROM  " . $this->prefix . "template   
        WHERE  presence = 1 and tableName = '$tableName'
        ORDER BY name ASC";

        $data = array(
            "template" => $this->db->query($template)->getResult(),
            //"q" => $template,
        );
        return $this->response->setJSON($data);
    }
}
