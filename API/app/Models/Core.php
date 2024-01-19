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
    function header()
    {
        if (service('request')->getHeaderLine('Token')) {
            $jwtObj = explode('.', service('request')->getHeaderLine('Token'));
            $user = base64_decode($jwtObj[1]);
            $data = json_decode($user, true);
        } else {
            $data = false;
        }
        return $data;
    }

    function getJwtToken()
    {
        return service('request')->getHeaderLine('Token');
    }

    function checkValidToken()
    {
          $request = request();
         $token =  $request->header('Token');
        //
        // echo $token;
        // $authorizationHeader = $headers['Token'];
        // echo    $authorizationHeader." \n";
        // echo service('request')->getHeaderLine('Authorization');
        //$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhY2NvdW50Ijp7ImlkIjoiMTAwMSIsInVzZXJuYW1lIjoiVEVTUyIsImFjY291bnRUeXBlSWQiOiIwIiwibmFtZSI6IiJ9LCJ0b2tlbk5hbWUiOiJmYzg3NGt2dlhvU2RTdzVxQTI5MjA1Q1MxRE1GdW1hMkpVWllvVXRLV3c3bEMwZGNESCIsInRva2VuIjoiNDY1NGU5MThmMGVkOTdhMGNkMWIxMzFjMDMyNDk3MDUiLCJpYXQiOiIxNzAzNTg1NDgyMC45MjAzNTMwMCAxNzAzNTg1NDgyIiwibmJmIjoxNzAzNTg1NDgyfQ.lhdFS4qSbrOWG4qTpFcGp7GrFQG3pla_GZeQNgfo9hQ";

        $key = $_ENV['SECRETKEY'];
        //  list($headersB64, $payloadB64, $sig) = explode('.',  $token);
        //  $decoded = json_decode(base64_decode($payloadB64), true);
       

        $var = false;
        if (service('request')->getHeaderLine('Token')) {
            $decoded = JWT::decode( service('request')->getHeaderLine('Token'), new Key($key, 'HS256'));
            return $decoded->token;
          //  print_r( $decoded );
            // if (!$decoded->account) {
              
            //     return true;
            // } else {
               
            //     return false;
            // }
        }else{
            return false;
        }
      //  return self::header() != false ? self::header()['account']['id'] : "";
   
    }



    function accountId()
    {
        return self::header() != false ? self::header()['account']['id'] : "";
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

            return $prefix . $new_number;
        }
    }

 
  


    function isUrlValid($url)
    {
        // Menggunakan fungsi strpos untuk mencari "http://" atau "https://"
        return (strpos($url, "http://") === 0 || strpos($url, "https://") === 0);
    }
 
 
}