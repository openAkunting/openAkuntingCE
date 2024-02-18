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

            if ($this->db->transStatus() != false) {
                $this->db->transComplete();
            } else {
                $this->db->transRollback();
            }

            $data = [
                "error" => false,
                "transaction" => $this->db->transStatus() === false ? false : true,
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
        WHERE  presence = 1 AND templateId  = '$templateId'
        ORDER BY id ASC";

        $data = array(
            "template" => $this->db->query($template)->getResult()[0],
            "journal_template" => $this->db->query($journal_template)->getResult(),
        );
        return $this->response->setJSON($data);
    }
}
