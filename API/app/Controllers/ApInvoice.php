<?php

namespace App\Controllers;


class ApInvoice extends BaseController
{
    function __construct()
    {
        if (model("Token")->checkValidToken() == '') {
            //   exit;
        }
    }

    public function index()
    {
        $tblAccount = $this->prefix . 'account';
        $tblJournal = $this->prefix . 'journal';

        /* $startDate = $this->request->getVar()['startDate'];
         $endDate = $this->request->getVar()['endDate'];

         $rangeDate = model("Core")->rangeDate($startDate, $endDate);
         $date = " AND (h.journalDate >= '$startDate' AND  h.journalDate <= '$endDate' )";
        */
        $rest = [];
        $q = "SELECT * FROM ap_invoice WHERE presence = 1  order by id ASC ";
        $items = $this->db->query($q)->getResultArray();

        $q2 = "SELECT s.* , a.name AS 'creditAccountName'
        FROM supplier as s 
        LEFT JOIN account AS a ON a.id = s.creditAccountId
        WHERE s.presence = 1  
        order by s.name ASC ";
        $selectSupplier = $this->db->query($q2)->getResultArray();

        $data = [
            "error" => false,
            "items" => $items,
            "selectSupplier" => $selectSupplier,
        ];
        return $this->response->setJSON($data);
    }

    public function onInsertNewInvoice()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
            $id = model("Core")->number("ap_invoice");
            $creditAccountId = model("Core")->select("creditAccountId", "supplier", "id = '" . $post['data']['supplierId'] . "' ");
            $this->db->table($this->prefix . "ap_invoice")->insert([
                "id" => $id,
                "supplierId" => $post['data']['supplierId'],
                "creditAccountId" => $creditAccountId,

                "invoiceDate" => $post['data']['invoiceDate'],
                "dueDate" => $post['data']['due'],
                //"amount" => $post['data']['amount'] < 0 ? $post['data']['amount'] * -1 : $post['data']['amount'],

                "presence" => 1,
                "updateDate" => date("Y-m-d H:i:s"),
                "updateBy" => model("Token")->userId(),
                "inputDate" => date("Y-m-d H:i:s"),
                "inputBy" => model("Token")->userId(),
            ]);

            // $id = model("Core")->number("ap_invoice_detail");
            // $this->db->table($this->prefix . "ap_invoice_detail")->insert([
            //     "id" => $id,
            //     "invoiceId" => $id,
            //     "gnrNo" => $post['data']['gnrNo'],
            //     "poNo" => $post['data']['poNo'],
            //     "amount" => $post['data']['amount'] < 0 ? $post['data']['amount'] * -1 : $post['data']['amount'],

            //     "presence" => 1,
            //     "updateDate" => date("Y-m-d H:i:s"),
            //     "updateBy" => model("Token")->userId(),
            //     "inputDate" => date("Y-m-d H:i:s"),
            //     "inputBy" => model("Token")->userId(),
            // ]);
            $data = [
                "error" => false,
                "code" => 200
            ];

        }

        return $this->response->setJSON($data);
    }

    public function detail()
    {

        $invoiceId = $this->request->getVar()['invoiceId'];

        $rest = [];
        $q = "SELECT i.*, s.accountId
        FROM ap_invoice  as i
        left join supplier as s on s.id = i.supplierId
        WHERE i.presence = 1 AND i.id =  '$invoiceId' 
        order by i.id ASC ";
        $items = $this->db->query($q)->getResultArray()[0];

        $q3 = "SELECT * , '' as 'checkBox' FROM ap_invoice_detail 
        WHERE presence = 1 AND invoiceId =  '$invoiceId' order by id ASC ";
        $detail = $this->db->query($q3)->getResultArray();

        $q2 = "SELECT * FROM supplier WHERE presence = 1     order by id ASC ";
        $selectSupplier = $this->db->query($q2)->getResultArray();

        $q5 = "SELECT * , '' as 'checkBox' FROM ap_payment_detail 
        WHERE presence = 1 AND invoiceId =  '$invoiceId' order by id ASC ";
        $itemPayment = $this->db->query($q5)->getResultArray();


        $data = [
            "error" => false,
            "item" => $items,
            "itemDetails" => $detail,
            "itemPayments" => $itemPayment,
            "selectSupplier" => $selectSupplier,
        ];
        return $this->response->setJSON($data);
    }

    public function onInsertNewInvoiceDetail()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
            foreach ($post['data'] as $row) { 
                $id = model("Core")->number("ap_invoice_detail");
                $this->db->table($this->prefix . "ap_invoice_detail")->insert([
                    'id' => $post['invoiceId'] . '-' . $id,
                    "invoiceId" => $post['invoiceId'],
                    "gnr" => $row['gnr'],
                    "po" => $row['po'],
                    "amount" => $row['amount'], 
                    "accountId" => $row['accountId'],
                    "presence" => 1,
                    "updateDate" => date("Y-m-d H:i:s"),
                    "updateBy" => model("Token")->userId(),
                    "inputDate" => date("Y-m-d H:i:s"),
                    "inputBy" => model("Token")->userId(),
                ]);
            }
            $data = [
                "error" => false,
                "code" => 200
            ];

        }

        return $this->response->setJSON($data);
    }

    public function onDeleteDetail()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
            $this->db->transStart();
            foreach ($post['data'] as $row) {
                if ($row['checkBox'] == 'true') {
                    $this->db->table($this->prefix . "ap_invoice_detail")->update([
                        "presence" => 0,
                        "updateDate" => date("Y-m-d H:i:s"),
                        "updateBy" => model("Token")->userId(),
                    ], " id = '" . $row['id'] . "' ");
                }
            }
            $invoiceId = $post['invoiceId'];
            self::updateInvoiceAmount($invoiceId);

            if ($this->db->transStatus() != false) {
                $this->db->transComplete();
            } else {
                $this->db->transRollback();
            }
            $data = [
                "error" => false,
                "code" => 200,
                "transaction" => $this->db->transStatus() === false ? false : true,
            ];

        }

        return $this->response->setJSON($data);
    }

    public function onInsertNewInvoicePayment()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
            $id = model("Core")->number("ap_payment");
            $this->db->table($this->prefix . "ap_payment")->insert([
                'id' => $post['invoiceId'] . '-' . $id,
                "invoiceId" => $post['invoiceId'],
                /// "dueDate" => $post['data']['dueDate']['year'] . "-" . $post['data']['dueDate']['month'] . "-" . $post['data']['dueDate']['day'],
                "paymentDate" => $post['data']['paymentDate']['year'] . "-" . $post['data']['paymentDate']['month'] . "-" . $post['data']['paymentDate']['day'],

                "amount" => $post['data']['amount'] < 0 ? $post['data']['amount'] * -1 : $post['data']['amount'],

                "presence" => 1,
                "updateDate" => date("Y-m-d H:i:s"),
                "updateBy" => model("Token")->userId(),
                "inputDate" => date("Y-m-d H:i:s"),
                "inputBy" => model("Token")->userId(),
            ]);

            self::updateInvoiceAmount($post['invoiceId']);

            $data = [
                "error" => false,
                "code" => 200
            ];

        }

        return $this->response->setJSON($data);
    }
    public function onDeletePayment()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
            $this->db->transStart();
            foreach ($post['data'] as $row) {
                if ($row['checkBox'] == 'true') {
                    $this->db->table($this->prefix . "ap_payment")->update([
                        "presence" => 0,
                        "updateDate" => date("Y-m-d H:i:s"),
                        "updateBy" => model("Token")->userId(),
                    ], " id = '" . $row['id'] . "' ");
                }
            }
            $invoiceId = $post['invoiceId'];
            self::updateInvoiceAmount($invoiceId);

            if ($this->db->transStatus() != false) {
                $this->db->transComplete();
            } else {
                $this->db->transRollback();
            }
            $data = [
                "error" => false,
                "code" => 200,
                "transaction" => $this->db->transStatus() === false ? false : true,
            ];

        }

        return $this->response->setJSON($data);
    }
    private function updateInvoiceAmount($id = "")
    {
        $totalAmount = model("Core")->select("sum(amount)", "ap_invoice_detail", "presence = 1 and invoiceId = '$id' ");
        $totalPaid = model("Core")->select("sum(amount)", "ap_payment", "presence = 1 and invoiceId = '$id' ");

        $this->db->table($this->prefix . "ap_invoice")->update([
            "amount" => $totalAmount,
            "paid" => $totalPaid,
            "updateDate" => date("Y-m-d H:i:s"),
            "updateBy" => model("Token")->userId(),
        ], " id = '" . $id . "' ");

        return true;
    }
}


