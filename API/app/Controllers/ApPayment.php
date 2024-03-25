<?php

namespace App\Controllers;


class ApPayment extends BaseController
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
   
        
 
        $q2 = "SELECT p.* , s.name as 'statusName'
        FROM ap_payment AS p
        LEFT JOIN global_status AS s ON s.statusId = p.status
        WHERE p.presence = 1 
        order by p.id DESC ";
        $payment = $this->db->query($q2)->getResultArray();
  
        $q2 = "SELECT s.id, s.name,  s.accountId , a.name AS 'account'
        FROM supplier AS s 
        JOIN account AS a ON a.id = s.accountId
        WHERE s.presence = 1  
        order BY s.id ASC ";
        $selectSupplier = $this->db->query($q2)->getResultArray();
 

        $data = [
            "error" => false, 
            "payment" => $payment, 
            "selectSupplier" => $selectSupplier, 
            
        ];
        return $this->response->setJSON($data);
    }

    public function onInsertNewPayment()
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
                "id" => $id, 
                "supplierId" => $post['data']['supplierId'],
                "accountId" => $post['data']['accountId'],
                
                "memo" => $post['data']['memo'], 
                "paymentDate" => $post['data']['paymentDate']['year'] . "-" . $post['data']['paymentDate']['month'] . "-" . $post['data']['paymentDate']['day'],
               // "dueDate" => $post['data']['dueDate']['year'] . "-" . $post['data']['dueDate']['month'] . "-" . $post['data']['dueDate']['day'],
                "amount" => 0,
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

        $id = $this->request->getVar()['id'];

        $rest = [];
        $q = "SELECT p.*, a.name AS 'account', s.name as 'supplier' 
        FROM ap_payment   AS p
        LEFT JOIN account AS a ON a.id = p.accountId 
        left join supplier as s on s.id = p.supplierId
        WHERE  p.id =  '$id' 
        order by p.id ASC ";
        $item = $this->db->query($q)->getResultArray()[0];

        $q3 = "SELECT * , '' as 'checkBox' FROM ap_payment_detail 
        WHERE presence = 1 AND paymentId  =  '$id' order by id ASC ";
        $detail = $this->db->query($q3)->getResultArray();
  
        $q4 = "SELECT id,amount, paid  FROM ap_invoice 
        WHERE presence = 1 AND supplierId  =  '".$item['supplierId']."' order by id ASC ";
        $invoice = $this->db->query($q4)->getResultArray();

        $data = [
            "error" => false,
            "item" => $item,
            "detail" => $detail,  
            "selectInvoice" => $invoice,  
            
        ];
        return $this->response->setJSON($data);
    }

    public function accountCashBank(){ 
        $q33 = "SELECT id, name, cashbank
        FROM  account AS t1
        WHERE cashbank = 1 and presence = 1 and NOT EXISTS (
           SELECT 1
           FROM account AS t2
           WHERE t2.parentId = t1.id
        ) 
        ORDER BY id ASC";
        $selectAccount = $this->db->query($q33)->getResultArray(); 
        $data = [
            "error" => false,
            "items" => $selectAccount, 
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
            $id = model("Core")->number("ap_payment_detail");
            $this->db->table($this->prefix . "ap_payment_detail")->insert([
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
            self::updateInvoiceAmount($post['invoiceId']);
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
                    $this->db->table($this->prefix . "ap_payment_detail")->update([
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
            $id = model("Core")->number("ap_payment_payment");
            $this->db->table($this->prefix . "ap_payment_payment")->insert([
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
                    $this->db->table($this->prefix . "ap_payment_payment")->update([
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
        $totalAmount = model("Core")->select("sum(amount)", "ap_payment_detail", "presence = 1 and invoiceId = '$id' ");
        $totalPaid = model("Core")->select("sum(amount)", "ap_payment_payment", "presence = 1 and invoiceId = '$id' ");

        $this->db->table($this->prefix . "ap_payment")->update([
            "amount" => $totalAmount,
            "paid" => $totalPaid,
            "updateDate" => date("Y-m-d H:i:s"),
            "updateBy" => model("Token")->userId(),
        ], " id = '" . $id . "' ");

        return true;
    }
}


