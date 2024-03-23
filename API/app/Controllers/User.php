<?php

namespace App\Controllers;

use OpenApi\Annotations as OA;

class User extends BaseController
{
    function __construct()
    {
        if (model("Token")->checkValidToken() == '') {
            //   exit;
        }
    }

    public function index()
    {
        $user = "SELECT u.id, u.email, u.name, u.sa, u.status, r.name AS 'role'
        FROM  " . $this->prefix . "user AS u 
        LEFT JOIN " . $this->prefix . "user_role AS r ON u.userRuleId = r.id
        WHERE u.presence = 1 
        ORDER BY u.name ASC";

        $data = [
            "error" => false,
            "items" => $this->db->query($user)->getResult(),

        ];
        return $this->response->setJSON($data);
    }

    public function tabs()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
            $jti = model("Token")->checkValidToken();
            if ($post['tabs'] == true && model("Core")->select("count(id)","user_tabs","jti = '$jti'") < 6) { 
                
                $this->db->table($this->prefix . "user_tabs")->insert([
                    "jti" => $jti,
                    "name" => $post['name'],
                    "active" => $post['active'],
                    "role" => $post['role'],
                    "note" => json_encode($post),
                    "inputDate" => date("Y-m-d H:i:s"),
                ]);

            }
            $data = [
                "error" => false,
                "code" => 200
            ];
        }

        return $this->response->setJSON($data);
    }
 
    public function getTabs()
    {
        $jti = model("Token")->checkValidToken();
        $user = "SELECT  *
        FROM  " . $this->prefix . "user_tabs  
        WHERE  jti = '$jti'
        ORDER BY sorting ASC"; 

        $data = [
            "error" => false,
            "items" => $this->db->query($user)->getResult(),  
        ];
        return $this->response->setJSON($data);
    }


    public function detail()
    {
        $id = $this->request->getVar()['id'];
        $user = "SELECT u.*
        FROM  " . $this->prefix . "user  as u 
        WHERE u.presence = 1  and id = '$id'
        ORDER BY u.name ASC";

        $userRole = "SELECT id, name, isLock
        FROM  " . $this->prefix . "user_role
        WHERE presence = 1 
        ORDER BY name ASC";


        $data = [
            "error" => false,
            "items" => $this->db->query($user)->getResult()[0],
            "userRole" => $this->db->query($userRole)->getResult(),

        ];
        return $this->response->setJSON($data);
    }

    public function userDetailUpdate()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
            $row = $post['model'];
            if ($row['sa'] == '1') {
                $this->db->table($this->prefix . "user")->update([
                    "sa" => 0
                ]);
            }

            $this->db->table($this->prefix . "user")->update([
                "userRuleId" => $row['userRuleId'],
                "name" => $row['name'],
                "sa" => $row['sa'],
                "status" => $row['status'],
                "updateBy" => model("Token")->userId(),
                "updateDate" => date("Y-m-d H:i:s"),
            ], "id = '" . $post['id'] . "' ");


            $data = [
                "error" => false,
                "code" => 200
            ];
        }

        return $this->response->setJSON($data);
    }


    public function userRole()
    {
        $user = "SELECT  * 
        FROM  " . $this->prefix . "user_role   
        WHERE presence = 1 
        ORDER BY name ASC";

        $data = [
            "error" => false,
            "items" => $this->db->query($user)->getResult(),
        ];
        return $this->response->setJSON($data);
    }

    public function userRoleAccess()
    {
        $id = $this->request->getVar()['id'];

        $user = "SELECT  a.* , m.name as 'module'
        FROM  " . $this->prefix . "user_role_access as a
        left join module as m on m.id = a.moduleId
        WHERE a.presence = 1  and a.userRulesId = '$id'
        ORDER BY a.moduleId ASC";

        $data = [
            "error" => false,
            "items" => $this->db->query($user)->getResult(),
        ];
        return $this->response->setJSON($data);
    }

    public function userRoleAccessUpdate()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
            foreach ($post['items'] as $row) {
                $this->db->table($this->prefix . "user_role_access")->update([
                    "_read" => $row['_read'],
                    "_create" => $row['_create'],
                    "_update" => $row['_update'],
                    "_delete" => $row['_delete'],
                    "updateBy" => model("Token")->userId(),
                    "updateDate" => date("Y-m-d H:i:s"),
                ], " id = '" . $row['id'] . "' ");
            }

            $data = [
                "error" => false,
                "code" => 200,
            ];
        }

        return $this->response->setJSON($data);
    }

    public function userRoleAccessReload()
    {
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $data = [
            "error" => true,
            "code" => 400
        ];
        if ($post) {
            $userRulesId = $post['id'];
            $user = "SELECT  * FROM  " . $this->prefix . "module ";

            foreach ($this->db->query($user)->getResultArray() as $row) {
                if (!model("Core")->select("id", "user_role_access", "moduleId = '" . $row['id'] . "' and userRulesId = '$userRulesId' ")) {
                    $this->db->table($this->prefix . "user_role_access")->insert([
                        "userRulesId" => $userRulesId,
                        "moduleId" => $row['id'],
                        "_read" => 1,
                        "_create" => 1,
                        "_update" => 1,
                        "_delete" => 0,
                        "updateBy" => model("Token")->userId(),
                        "updateDate" => date("Y-m-d H:i:s"),
                        "inputBy" => model("Token")->userId(),
                        "inputDate" => date("Y-m-d H:i:s"),
                    ]);
                }
            }

            $data = [
                "error" => false,
                "code" => 200,
            ];
        }

        return $this->response->setJSON($data);
    }
}
