<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\CodeIgniter;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use CodeIgniter\HTTP\IncomingRequest;

class Token extends Model
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
    
        $key = $_ENV['SECRETKEY']; 
        if (service('request')->getHeaderLine('Authorization')) {

            $Authorization = explode(" ", service('request')->getHeaderLine('Authorization'));
            if (strtoupper($Authorization[0]) == 'BEARER') {
                $decoded = JWT::decode($Authorization[1], new Key($key, 'HS256'));
                return $decoded->jti;
            } else {
                return false;
            }

        } else {
            return false;
        } 

    }



    function getData()
    {
        $data = false;
        $key = $_ENV['SECRETKEY']; 
        if (service('request')->getHeaderLine('Authorization')) {
            $Authorization = explode(" ", service('request')->getHeaderLine('Authorization'));
            if (strtoupper($Authorization[0]) == 'BEARER') {
                $data = JWT::decode($Authorization[1], new Key($key, 'HS256')); 
            } else {
                $data =  false;
            }

        }

        return $data;
    }

    function userId(){
       return self::getData()->access[0]->user->id;
    }


    function getCurrentUser()
    {
        $data = false;
        $key = $_ENV['SECRETKEY']; 
        if (service('request')->getHeaderLine('Authorization')) {
            $Authorization = explode(" ", service('request')->getHeaderLine('Authorization'));
            if (strtoupper($Authorization[0]) == 'BEARER') {
                $data = JWT::decode($Authorization[1], new Key($key, 'HS256')); 
                $data  = $data->access[self::getIndex()];
            } else {
                $data =  false;
            }

        }

        return $data;
    }

    function getIndex(){
        return (int)service('request')->getHeaderLine('X-index');
    }
 

}