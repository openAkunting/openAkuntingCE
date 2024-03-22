<?php

namespace App\Controllers;


class Ap extends BaseController
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
        $q = "SELECT * FROM ap_invoice WHERE presence = 1  order by id DESC ";
        $items = $this->db->query($q)->getResultArray();


        $q2 = "SELECT * FROM supplier WHERE presence = 1  order by id DESC ";
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
            $this->db->table($this->prefix . "ap_invoice")->insert([
                "id" => $id,
                "supplierId" => $post['data']['supplierId'],
                "invoiceDate" => $post['data']['invoiceDate']['year'] . "-" . $post['data']['invoiceDate']['month'] . "-" . $post['data']['invoiceDate']['day'],
                "dueDate" => $post['data']['due']['year'] . "-" . $post['data']['due']['month'] . "-" . $post['data']['due']['day'],
                "amount" => $post['data']['amount'] < 0 ? $post['data']['amount'] * -1 : $post['data']['amount'],

                "presence" => 1,
                "updateDate" => date("Y-m-d H:i:s"),
                "updateBy" => model("Token")->userId(),
                "inputDate" => date("Y-m-d H:i:s"),
                "inputBy" => model("Token")->userId(),
            ]);

            $id = model("Core")->number("ap_invoice_detail");
            $this->db->table($this->prefix . "ap_invoice_detail")->insert([
                "id" => $id,
                "invoiceId" => $id,
                "gnrNo" => $post['data']['gnrNo'],
                "poNo" => $post['data']['poNo'],
                "amount" => $post['data']['amount'] < 0 ? $post['data']['amount'] * -1 : $post['data']['amount'],

                "presence" => 1,
                "updateDate" => date("Y-m-d H:i:s"),
                "updateBy" => model("Token")->userId(),
                "inputDate" => date("Y-m-d H:i:s"),
                "inputBy" => model("Token")->userId(),
            ]);
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
        order by i.id DESC ";
        $items = $this->db->query($q)->getResultArray()[0];

        $q3 = "SELECT * , '' as 'checkBox' FROM ap_invoice_detail 
        WHERE presence = 1 AND invoiceId =  '$invoiceId' order by id DESC ";
        $detail = $this->db->query($q3)->getResultArray();

        $q2 = "SELECT * FROM supplier WHERE presence = 1     order by id DESC ";
        $selectSupplier = $this->db->query($q2)->getResultArray();
   
        $q5 = "SELECT * , '' as 'checkBox' FROM ap_invoice_payment 
        WHERE presence = 1 AND invoiceId =  '$invoiceId' order by id DESC ";
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
            $id = model("Core")->number("ap_invoice_detail");
            $this->db->table($this->prefix . "ap_invoice_detail")->insert([
                'id' => $post['invoiceId'] . '-' . $id,
                "invoiceId" => $post['invoiceId'],
                "gnrNo" => $post['data']['gnrNo'],
                "poNo" => $post['data']['poNo'],
                "amount" => $post['data']['amount'] < 0 ? $post['data']['amount'] * -1 : $post['data']['amount'],

                "presence" => 1,
                "updateDate" => date("Y-m-d H:i:s"),
                "updateBy" => model("Token")->userId(),
                "inputDate" => date("Y-m-d H:i:s"),
                "inputBy" => model("Token")->userId(),
            ]);
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
            $totalAmount = model("Core")->select("sum(amount)", "ap_invoice_detail", "presence = 1 and invoiceId = '$invoiceId' ");

            $this->db->table($this->prefix . "ap_invoice")->update([
                "amount" => $totalAmount,
                "updateDate" => date("Y-m-d H:i:s"),
                "updateBy" => model("Token")->userId(),
            ], " id = '" . $invoiceId . "' ");

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
}
