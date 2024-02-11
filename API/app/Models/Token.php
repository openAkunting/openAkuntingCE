<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\CodeIgniter;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use CodeIgniter\HTTP\IncomingRequest;

class Token extends Model
{
    protected $key = null;
    protected $prefix = null;
 
    protected $db = null;
    protected $request = null;

    function __construct()
    {
        $this->prefix = $_ENV['PREFIX'];
        $this->key = $_ENV['SECRETKEY']; 
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
                $data = false;
            }

        }

        return $data;
    }

    function userId()
    {
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
                $data = $data->access[self::getIndex()];
            } else {
                $data = false;
            }

        }

        return $data;
    }

    function getIndex()
    {
        return (int) service('request')->getHeaderLine('X-index');
    }



    function createData($query)
    {
        
        $jti = md5(rand(0, 99) . date("Y-m-d H:i:s") . uniqid());
        $access = [];
        $i = 0;
        foreach ($query as $rec) {
            $access[] = array(
                "appCode" => '',
                "user" => array(
                    "id" => $rec['id'],
                    "email" => $rec['email'],
                    "name" => $rec['name'],
                    "rule" => self::select("name","user_role","id = '".$rec['userRuleId']."'"),
                ),
                "role" => [],
            );

        
            $this->db->table($this->prefix . "user_jti")->insert([
                "userId" => $rec['id'],
                "jti" => $jti,
                "inputDate" => date("Y-m-d H:i:s"),
            ]);

            $q1 = "SELECT * FROM " . $this->prefix . "module ORDER BY id ASC";
            $access[$i]['role']['documentation'] =  [
                'Create','Read','Update','Delete', 
            ];
            foreach ($this->db->query($q1)->getResultArray() as $role) {
                $access[$i]['role'][ preg_replace('/\s+/', '_', strtolower($role['name'])) ] =  [
                    (int) self::select("_create","user_role_access","userRulesId = '".$rec['userRuleId']."' and moduleId = '".$role['id']."' "),
                    (int) self::select("_read","user_role_access","userRulesId = '".$rec['userRuleId']."' and moduleId = '".$role['id']."' "),
                    (int) self::select("_update","user_role_access","userRulesId = '".$rec['userRuleId']."' and moduleId = '".$role['id']."' "),
                    (int) self::select("_delete","user_role_access","userRulesId = '".$rec['userRuleId']."' and moduleId = '".$role['id']."' "),
                    
                ];
            }
            $i++;
        }

        $payload = [
            'iss' => 'http://localhost',
            'aud' => 'http://localhost', 
            "jti" => $jti,
            'iat' => time(),
            'nbf' => time(),
            'exp' => strtotime(((int) date("Y") + 1) . date("-m-d H:i:s")),
            "access" => $access,

        ];
    
        $authorization = JWT::encode($payload, $this->key, 'HS256');

       

        return [
            "payload" => $payload,
            "authorization" => $authorization,

        ];
    }
}