<?php

namespace App\Controllers;
use OpenApi\Annotations as OA;
/**
 * @OA\Info(title="My First API", version="0.1")
 */
class Home extends BaseController
{
    function __construct()
    {
        if ( model("Token")->checkValidToken() == '' ) {
        //    exit;
        } 
    }
    /**
     * @OA\Get(
     *     path="/api/resource.json",
     *     @OA\Response(response="200", description="An example resource")
     * )
     */
    public function index()
    { 
        $data = array(
            "error" => false,
            "code" => 200,
            "UTC" => date("H:i:s")
        );
        return $this->response->setJSON($data);
    }


    /**
     * @OA\Get(
     *     path="/api/hello",
     *     @OA\Response(response="200", description="An example resource")
     * )
     */
    public function hello()
    {
        if (model("Token")->checkValidToken() == '') {
            $data = array(
                "error" => true,
                "code" => 401,
            );
        } else {

            $tenantId = model("Token")->getCurrentUser()->tenantId;
            $q = "SELECT *
            FROM  ".$this->prefix."journal  
            WHERE  tenantId = '$tenantId' ";
            $items = $this->db->query($q)->getResultArray();

            $data = array(
                "error" => false,
                "code" => 202,
                "q" => $q,
                "items" => $items,
                "get" => $this->request->getVar(),
                "user" => model("Token")->getCurrentUser(),
            );
        }

        return $this->response->setJSON($data);

    }
}
