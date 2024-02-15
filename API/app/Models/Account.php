<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\CodeIgniter;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use CodeIgniter\HTTP\IncomingRequest;

class Account extends Model
{
    protected $prefix = null;
    protected $db = null;
    protected $request = null;

    function __construct()
    {
        $this->prefix = $_ENV['PREFIX'];
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
    }


    function accountBalance($post = [])
    {
        $data = [];
        $table = isset($post['table']) ? $post['table'] : 'journal';
        //BEGIN BALANCE is 1st month
        $whereBefore = " year = '" . $post['year'] . "' AND  
        month = '" . ($post['month'] - 1) . "' AND 
        accountId = '" . $post['accountId'] . "' AND 
        outletID = '" . $post['outletID'] . "' and presence = 1 ";
        $beginBalance = (float) model("Core")->select("beginBalance", "account_balance", $whereBefore);

        //IF BEGIN BALANCE is 13th month



        $whereDebit = " presence = 1 AND  outletID =  '" . $post['outletID'] . "'  and accountId = '" . $post['accountId'] . "'  AND  YEAR(journalDate) = '" . $post['year'] . "' AND MONTH(journalDate) = '" . $post['month'] . "'  ";
        $newDebit = (int)model("Core")->select("sum(debit)", $table, $whereDebit);
        $newCredit = (int)model("Core")->select("sum(credit)", $table, $whereDebit);

        $endBalance = $beginBalance + ($newDebit + $newCredit);

        $where = " year = '" . $post['year'] . "' AND 
        month = '" . $post['month'] . "' AND 
        accountId = '" . $post['accountId'] . "' AND 
        outletID = '" . $post['outletID'] . "' and presence = 1 ";

        $id = model("Core")->select("id", "account_balance", $where);

        if (!$id) {
            $data = array(
                "year" => $post['year'],
                "month" => $post['month'],
                "accountId" => $post['accountId'],
                "outletID" => $post['outletID'],
                "beginBalance" => $beginBalance,
                "debit" => $newDebit,
                "credit" => $newCredit,
                "endBalance" => $endBalance,
                "presence" => 1,
                "inputDate" => date("Y-m-d H:i:s"),
                "inputBy" => model("Token")->userId(),
                "updateDate" => date("Y-m-d H:i:s"),
                "updateBy" => model("Token")->userId(),
            );
            $this->db->table("account_balance")->insert($data);
        }
           else { 
            $data = array(  
                "debit" => $newDebit,
                "credit" => $newCredit,
                "endBalance" => $endBalance, 
                "updateDate" => date("Y-m-d H:i:s"),
                "updateBy" => model("Token")->userId(),
            );
            $this->db->table("account_balance")->update($data, "id =  $id ");
        }

        return $data;
    }
}