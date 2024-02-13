<?php

namespace App\Controllers;

use OpenApi\Annotations as OA;

class Journal extends BaseController
{
    function __construct()
    {
        if (model("Token")->checkValidToken() == '') {
            //   exit;
        }
    }

    public function index()
    {
       
        $rest = [];
        $q = "SELECT * FROM " . $this->prefix . "journal_header 
        WHERE presence = 1 order by journalDate ASC";
        $items = $this->db->query($q)->getResultArray();
        foreach ($items as $row) {

            $j = "SELECT j.id, j.accountId, j.description, j.debit, j.credit,  a.name as 'account', o.name as 'outlet', b.name as 'branch'
            FROM  " . $this->prefix . "journal as j
            left join account as a on a.id = j.accountId
            left join outlet as o on o.id = j.outletId
            left join branch as b on b.id = o.branchId
            WHERE  j.presence = 1 and j.journalId = '" . $row['id'] . "'
            ORDER BY j.id ASC";
            $journal = $this->db->query($j)->getResultArray();
 
            $rest[] = array(
                "id" => $row['id'],
                "note" => $row['note'],
                "ref" => $row['ref'],
                "journalDate" => $row['journalDate'],
                "journal" => $journal,
                "inputDate" => $row['inputDate'],
                "inputBy" => $row['inputBy'],

            );
        }


        $data = [
            "error" => false,
            //     "items" => $this->db->query($d)->getResult(),
            "items" => $rest,

        ];
        return $this->response->setJSON($data);
    }

    public function selectItems()
    {

        $account = "SELECT id, name
        FROM account AS t1
        WHERE NOT EXISTS (
            SELECT 1
            FROM account AS t2
            WHERE t2.parentId = t1.id
        )
        ORDER BY id ASC";

        $outlet = "SELECT o.*, b.name as 'branch'
        FROM  " . $this->prefix . "outlet  as o
        left join branch as b on b.id = o.branchId 
        WHERE o.presence = 1 and o.status = 1
        ORDER BY  b.name ASC, o.name ASC";

        $template = "SELECT *
        FROM  " . $this->prefix . "template   
        WHERE  presence = 1 and tableName = 'journal_template'
        ORDER BY name ASC";

        $data = [
            "error" => false,
            "account" => $this->db->query($account)->getResult(),
            "outlet" => $this->db->query($outlet)->getResult(),
            "template" => $this->db->query($template)->getResult(),
        ];
        return $this->response->setJSON($data);
    }

    public function onSubmit()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
            /// $this->db->transStart();
            $debit = 0;
            $credit = 0;

            $journalId = model("Core")->number("journal");
            foreach ($post['items'] as $row) {

                /**
                 * JOURNAL
                 */
                $this->db->table($this->prefix . "journal")->insert([
                    "journalId" => $journalId,
                    "outletId" => $row['outletId'],
                    "accountId" => $row['accountId'],
                    "journalDate" => $post['model']['journalDate']['year'] . "-" . $post['model']['journalDate']['month'] . "-" . $post['model']['journalDate']['day'],

                    "debit" => $row['debit'],
                    "credit" => $row['credit'],
                    "description" => $row['description'],
                    "presence" => 1,
                    "updateDate" => date("Y-m-d H:i:s"),
                    "updateBy" => model("Token")->userId(),
                    "inputDate" => date("Y-m-d H:i:s"),
                    "inputBy" => model("Token")->userId()
                ]);
                $debit += $row['debit'];
                $credit += $row['credit'];



                /**
                 * ACCOUNT_BALANCE
                 */
                // $year = $post['model']['journalDate']['year'];
                // $month = $post['model']['journalDate']['month'];
                // $id = (int) model("Core")->select("id", "account_balance", "accountId = '" . $row['accountId'] . "' and year = '$year' and month = '$month' ");

                // if (!$id) {
                //     $monthLast = $month - 1; 
                //     $this->db->table($this->prefix . "account_balance")->insert([
                //         "accountId" => $row['accountId'],
                //         "year" => $year,
                //         "month" => $month,
                //         "beginBalance" => model("Core")->select("endBalance", "account_balance", "accountId = '" . $row['accountId'] . "' and year = '$year' and month = '$monthLast' "),
                //         "debit" => 0,
                //         "credit" => 0,
                //         "presence" => 1,
                //         "updateDate" => date("Y-m-d H:i:s"),
                //         "updateBy" => model("Token")->userId(),
                //         "inputDate" => date("Y-m-d H:i:s"),
                //         "inputBy" => model("Token")->userId()
                //     ]);
                // }
                // $where = "YEAR(JournalDate) = $year AND MONTH(JournalDate) = $month and presence = 1 
                // AND accountId = '" . $row['accountId'] . "'";

                // $this->db->table($this->prefix . "account_balance")->update([
                //     "debit" => (float) model("Core")->select("sum(debit)", "journal", $where),
                //     "credit" => (float) model("Core")->select("sum(credit)", "journal", $where),
                //     "updateDate" => date("Y-m-d H:i:s"),
                //     "updateBy" => model("Token")->userId(),
                // ], " accountId =  '" . $row['accountId'] . "' and year = $year and month = $month ");


            }

            $this->db->table($this->prefix . "journal_header")->insert([
                "id" => $journalId,
                "journalDate" => $post['model']['journalDate']['year'] . "-" . $post['model']['journalDate']['month'] . "-" . $post['model']['journalDate']['day'],
                "ref" => $post['model']['ref'],
                "note" => $post['model']['note'],
                "totalCredit" => $credit,
                "totalDebit" => $debit,

                "presence" => 1,
                "updateDate" => date("Y-m-d H:i:s"),
                "updateBy" => model("Token")->userId(),
                "inputDate" => date("Y-m-d H:i:s"),
                "inputBy" => model("Token")->userId()
            ]);



            // if ((($credit - $debit) == 0) && $this->db->transStatus() != false) {
            //     $this->db->transComplete();
            // } else {
            //     $this->db->transRollback();
            // }

            $data = [
                "error" => false,
                "transaction" => $this->db->transStatus() === false ? false : true,
                "code" => 200
            ];
        }

        return $this->response->setJSON($data);
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

            if (model("Core")->select("id", "template", "name='" . $post['nameOfTemplate'] . "' and presence = 1 ")) {
                /**
                 * OVERWRITE
                 */

                $id = model("Core")->select("id", "template", "name='" . $post['nameOfTemplate'] . "' and presence = 1 ");
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
                    "tableName" => "journal_template",
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
        WHERE  presence = 1 and id = '$templateId'
        ORDER BY name ASC";

        $journal_template = "SELECT *
        FROM  " . $this->prefix . "journal_template   
        WHERE  presence = 1 and templateId  = '$templateId'
        ORDER BY id ASC";

        $data = array(
            "template" => $this->db->query($template)->getResult()[0],
            "journal_template" => $this->db->query($journal_template)->getResult(),
        );
        return $this->response->setJSON($data);
    }
}
