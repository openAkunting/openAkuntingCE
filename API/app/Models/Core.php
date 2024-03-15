<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\CodeIgniter;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use CodeIgniter\HTTP\IncomingRequest;

class Core extends Model
{
    protected $id = null;
    protected $db = null;
    protected $request = null;

    function __construct()
    {
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
    }


    function select($field = "", $table = "", $where = " 1 ")
    {
        $data = null;
        if ($field != "") {
            $prefix = $_ENV['PREFIX'];
            $query = $this->db->query("SELECT $field  FROM $prefix$table WHERE $where LIMIT 1");
            if ($query->getRowArray()) {
                $row = $query->getRowArray();
                $data = $row[$field];
            }
        } else {
            $data = null;
        }
        return $data;
    } 

    function number($name = "")
    {
        if ($name) {
            $number = self::select('runningNumber', 'auto_number', "name = '" . $name . "'") + 1;
            $prefix = self::select('prefix', 'auto_number', "name = '" . $name . "'");
            if ($prefix == '$year') {
                $prefix = date("Y");
            }

            $this->db->table("auto_number")->update([
                "runningNumber" => $number,
                "updateDate" => time(), 
            ], "name = '" . $name . "' ");

            $new_number = str_pad($number, self::select('digit', 'auto_number', "name = '" . $name . "'"), "0", STR_PAD_LEFT);

            $this->db->table("auto_number")->update([  
                "lastRecord" => $prefix . $new_number, 
            ], "name = '" . $name . "' ");

            return $prefix . $new_number;
        }
    }

    function rangeDate($startDate, $endDate)
    {
        $date1 = strtotime($startDate);
        $date2 = strtotime($endDate);

        $daysDiff = floor(($date2 - $date1) / (60 * 60 * 24));

        // Periksa apakah perbedaan dalam hari kurang dari atau sama dengan 7
        return $daysDiff;
    }

     function getMonthList($startDate, $endDate)
    {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);

        $monthList = [];

        while ($start <= $end) {
            $monthList[] = array($start->format('Y'),$start->format('m'));
            $start->modify('first day of next month');
        }

        return $monthList;
    }

    function isUrlValid($url)
    {
        // Menggunakan fungsi strpos untuk mencari "http://" atau "https://"
        return (strpos($url, "http://") === 0 || strpos($url, "https://") === 0);
    }


}