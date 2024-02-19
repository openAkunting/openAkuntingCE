<?php

namespace App\Controllers;

class Cmd extends BaseController
{
    function __construct()
    {

    }
    public function index()
    {

        $data = array(
            "note" => "ok",
        );

        // echo  ;
        return $this->response->setJSON($data);
    }

    public function insertData()
    {
        // Lokasi file CSV
        $csvFile = WRITEPATH . 'uploads\\journalUmum_sample.csv'; // Ubah sesuai lokasi dan nama file CSV Anda

        // Membaca file CSV
        if (($handle = fopen($csvFile, "r")) !== FALSE) {
            $i = 0;
            // Loop melalui setiap baris CSV
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($i > 0) { 
                    print_r($row);
                    $journalId = model("Core")->number("journal");
                    $journalDate = $row[0];
                    $accountId = $row[1];
                    $userId = '1a';
                    $this->db->table($this->prefix . "journal")->insert([
                        "journalId" => $journalId,
                        "outletId" => 1,
                        "accountId" => $accountId,
                        "journalDate" => $journalDate,
                        "debit" => (int) $row[3],
                        "credit" => (int) $row[4],
                        "description" => $row[2],
                        "presence" => 1,
                        "updateDate" => date("Y-m-d H:i:s"),
                        "updateBy" => $userId,
                        "inputDate" => date("Y-m-d H:i:s"),
                        "inputBy" => $userId
                    ]);

                    $accountBalanceData = array(
                        "debit" => (int) $row[3],
                        "credit" => (int) $row[4],
                        "journalDate" => $journalDate,
                        "year" => (int) substr($journalDate, 0, 4),
                        "month" => (int) substr($journalDate, 5, 2),
                        "outletID" => 1,
                        "accountId" => $accountId,
                        "userId" => $userId,
                    );
                    $accountBalance =  model("Account")->accountBalance($accountBalanceData);

                    $this->db->table($this->prefix . "journal_header")->insert([
                        "id" => $journalId,
                        "accountId" => $accountId,
                        "journalDate" => $journalDate,
                        "totalCredit" => (int) $row[3],
                        "totalDebit" => (int) $row[4],
                        "presence" => 1,
                        "updateDate" => date("Y-m-d H:i:s"),
                        "updateBy" => $userId,
                        "inputDate" => date("Y-m-d H:i:s"),
                        "inputBy" => $userId
                    ]);
                }
                $i++;
            }

            fclose($handle);

            // Menampilkan pesan sukses atau melakukan redirect, sesuai kebutuhan Anda
            echo "Data berhasil disimpan ke dalam tabel.";
        } else {
            echo "Gagal membuka file CSV.";
        }
    }

    function journalHeader(){
        
        $template = "SELECT *
        FROM  " . $this->prefix . "journal 
        WHERE  presence = 1  AND debit > 0
        ORDER BY journalDate ASC";

        foreach($this->db->query($template)->getResultArray() as $post){
           
            $this->db->table($this->prefix . "journal_header")->insert([
                "id" => $post['journalId'], 
                "journalDate" => $post['journalDate'], 
                "totalCredit" => $post['debit'],
                "totalDebit" =>  $post['debit'],
                "presence" => 1,
                "updateDate" => $post['updateDate'],
                "updateBy" =>$post['updateBy'],
                "inputDate" =>$post['inputDate'],
                "inputBy" =>$post['inputBy'],
            ]);
        }
    }

}
