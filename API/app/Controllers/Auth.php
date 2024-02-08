<?php

namespace App\Controllers;

use OpenApi\Annotations as OA;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * @OA\Info(
 *  title="Auth REST API", 
 *  version="1.0",
 *  description ="You can authenticate to the REST API to access more endpoints and have a higher rate limit."
 * )
 */
class Auth extends BaseController
{
    protected $prefix = null;

    function __construct()
    {
        $this->prefix = $_ENV['PREFIX'];
    }


    function index()
    {
        $data = array(
            "error" => false,
        );
        return $this->response->setJSON($data);
    }

    /**
     * @OA\Post(
     *     path="/openAkunting/API/public/Auth/signin",
     *     summary =" Post Signin",
     *     tags={"Auth"}, 
     *      @OA\RequestBody(
     *           @OA\MediaType(
     *               mediaType="raw",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                            property="email",
     *                            type= "string",
     *                      ),
     *                      @OA\Property(
     *                            property="password",
     *                            type= "string | MD5",
     *                      )
     *                 ),  
     *           ),
     *      ),
     *     @OA\Response(response="400", description="Succces"),
     *     @OA\Response(response="200", description="Error"),
     * )
     */
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


            $key = $this->key;

            $q = "SELECT id, email, tenantId, name
                FROM  " . $this->prefix . "user
                WHERE email='$email' AND password = '$pass' AND presence = 1 ORDER BY priority DESC  ";

            $query = $this->db->query($q)->getResultArray();

            if (count($query)) {
                $jti = md5(rand(0, 99) . date("Y-m-d H:i:s") . uniqid());
                $access = [];

                foreach ($query as $rec) {
                    $access[] = array(
                        "tenantId" => $rec['tenantId'],
                        "user" => array(
                            "id" => $rec['id'],
                            "email" => $rec['email'],
                            "name" => $rec['name'],
                        ),
                        "company" => model("Core")->select("company", "tenant", "id = '" . $rec['tenantId'] . "' "),
                    );
                }

                $payload = [
                    'iss' => 'http://localhost',
                    'aud' => 'http://localhost',
                    "access" => $access,
                    "jti" => $jti,
                    'iat' => time(),
                    'nbf' => strtotime(date("Y-m-d H:i:s")),
                ];

                $authorization = JWT::encode($payload, $key, 'HS256');

                $data = array(
                    "error" => false,
                    "code" => 200,
                    "authorization" => $authorization,
                    "jti" => $jti,
                    "post" => $post,
                );

                $this->db->table($this->prefix . "user_jti")->insert([
                    "email" => $email,
                    "jti" => $jti,
                    "inputDate" => date("Y-m-d H:i:s"),
                ]);

            } else {
                $data = array(
                    "post" => $post,
                    "code" => 400,
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
            $token = $this->request->getVar()['token'];
            $data = array(
                "error" => true,
                "code" => 401,
                "get" => $post,
            );
            $id = model("Core")->select("userId", "user_otp", "requestCode = '$token' and presence = 1 ");
            if ($id) {
                $this->db->table($this->prefix . "user_otp")->update([
                    "updateDate" => date("Y-m-d H:i:s"),
                    "presence" => 0,
                ], " requestCode = '$token' ");


                $table = $this->prefix . "user";
                $key = $this->key;

                $q = "SELECT id, email, tenantId, name
                    FROM  $table  
                    WHERE id = '$id' AND presence = 1 ORDER BY priority DESC  ";

                $query = $this->db->query($q)->getResultArray();

                if (count($query)) {
                    //  $token  = md5(rand(0,99) . date("Y-m-d H:i:s") . uniqid());     
                    $access = [];

                    foreach ($query as $rec) {
                        $access[] = array(
                            "tenantId" => $rec['tenantId'],
                            "user" => array(
                                "id" => $rec['id'],
                                "email" => $rec['email'],
                                "name" => $rec['name'],
                            ),
                            "company" => model("Core")->select("company", "tenant", "id = '" . $rec['tenantId'] . "' "),
                        );
                    }

                    $payload = [
                        'iss' => 'http://localhost',
                        'aud' => 'http://localhost',
                        "access" => $access,
                        'iat' => time() . microtime(),
                        'nbf' => strtotime(date("Y-m-d H:i:s")),
                    ];

                    $authorization = JWT::encode($payload, $key, 'HS256');

                    $data = array(
                        "error" => false,
                        "code" => 202,
                        "authorization" => $authorization,
                        "post" => $post,
                    );
                } else {
                    $data = array(
                        "error" => false,
                        "code" => 401,
                        "get" => $post,
                    );
                }


            }

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
        if ($post && $_ENV['CI_ENVIRONMENT'] != 'production') {
            $token = $post['token'];
            $key = $_ENV['SECRETKEY'];

            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $data = array(
                "post" => $post,
                "note" => "development only",
                "error" => false,
                "decoded" => $decoded,
            );
        }

        return $this->response->setJSON($data);
    }

    function checkToken()
    {

        if (model("Token")->checkValidToken() == '') {
            $data = array(
                "error" => true,
                "code" => 401,
            );
            //  exit;
        } else {
            $data = array(
                "error" => false,
                "code" => 202,
                "get" => $this->request->getVar(),
                "jti" => model("Token")->checkValidToken(),
                "data" => model("Token")->getData(),
                "x-index" => model("Token")->getIndex(),
                "user" => model("Token")->getCurrentUser(),
            );
        }

        return $this->response->setJSON($data);

    }

}
