<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth extends BaseController
{
    protected $prefix = null; 
    protected $key = null; 

    function __construct()
    {
        $this->prefix =  $_ENV['PREFIX']; 
        $this->key = $_ENV['SECRETKEY'];  
    }
    function index()
    {
        // $json = file_get_contents('php://input');
        // $post = json_decode($json, true);

        $key = $_ENV['SECRETKEY'];
        $payload = [
            'iss' => 'http://localhost',
            'aud' => 'http://localhost',
            "token" => uniqid(),
            'iat' => time() . microtime(),
            'nbf' => strtotime(date("Y-m-d H:i:s")),
        ];
        $jwt = JWT::encode($payload, $key, 'HS256');

        $data = array(
            "error" => false,
            "token" => $jwt,
            "payload" => $payload,
        );
        return $this->response->setJSON($data);
    }

    function signin()
    {

        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "post" => $post,
            "note" => ""
        ];
        if ($post) {
            $email = str_replace(["'", '"', "\'", "#"], "", $post['email']);
            $pass = str_replace(["'", '"', "\'", "#"], "", $post['password']);
            
            $table = $this->prefix."account";
            $key = $this->key;
                   
           $q = "SELECT id, email, tenantId
                FROM  $table  
                WHERE email='$email' AND password = '$pass' AND presence = 1  ";
 
            $query =  $this->db->query($q)->getResultArray();

            if (count($query)) {
                    
                $app = [];

                foreach($query as $rec){
                    $token = md5(rand(0,99) . date("Y-m-d H:i:s") . uniqid());
                  
                    $payload = [ 
                        'iss' => 'http://localhost',
                        'aud' => 'http://localhost',
                        "query" => $rec, 
                        "token" => $token,
                        "tenantId" => $rec['tenantId'],
                        "prefix" => $this->prefix,
                        'iat' => time() . microtime(),
                        'nbf' => strtotime(date("Y-m-d H:i:s")),
                    ];
                    $app[] = array(
                        "tenantId" => $rec['tenantId'],  
                        "account" => $rec,
                        "company" => model("Core")->select("company","tenant","id = '".$rec['tenantId']."' "),
                        "token" => JWT::encode($payload, $key, 'HS256'),
                    );


                }

                // $this->db->table("account_auth")->insert([
                //     "accountId" => $id,
                //     "ipAddress" => $this->request->getIPAddress(),
                //     "token" => $token,
                //     "input_date" => date("Y-m-d H:i:s"),
                // ]);

                $data = array(
                    "error" => false,
                    "app" => $app,
                    "post" => $post, 
                );
            } else {
                $data = array(
                    "post" => $post,
                    "error" => true,
                    "note" => "Wrong password or email",
                );
            }
        }

        return $this->response->setJSON($data);
    }
    function getToken()
    {
     
        $post = $this->request->getVar();
        $data = array( 
            "error" => true, 
        );
        if ($post) {
            
            $data = array(
                "get" => $post,
                "error" => false, 
            );
        }
      
        return $this->response->setJSON($data);
    }

    function verifyToken()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = array( 
            "error" => true, 
        );
        if ($post) {
            $token = $post['token'];
            $key = $_ENV['SECRETKEY'];
 
            $decoded = JWT::decode($token, new Key($key, 'HS256')); 
            $data = array(
                "post" => $post,
                "error" => false,
                "decoded" => $decoded, 
            );
        }
      
        return $this->response->setJSON($data);
    }
    function checkToken()
    {

        if (model("Core")->checkValidToken() == '') { 
            $data = array(  
                "error" => true, 
                "note" => "Error 500",
            ); 
          //  exit;
        }else{
            $data = array(  
                "error" => false, 
                "rest" => model("Core")->checkValidToken(),
            ); 
        } 
         
        return $this->response->setJSON($data);

    }
}
