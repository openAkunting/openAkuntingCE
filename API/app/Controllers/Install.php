<?php

namespace App\Controllers;
/**
 * @OA\Info(title="My First API", version="0.1")
 */
class Install extends BaseController
{
    function __construct()
    {
        if (file_exists("../../install.php") == false) {
            exit;
        }
    }
    public function index()
    {

        $data = array(
            "env" => $_ENV,
            "status" => count($_ENV) > 0 ? true : false,
            "installer" => file_exists("../../install.php"),
        );

        // echo  ;
        return $this->response->setJSON($data);
    }

    function tables()
    {

        // Nama file SQL yang berisi query CREATE TABLE
        $file_path = 'oA_masterTable.sql';

        // Membaca isi file SQL
        $sql_content = file_get_contents($file_path);


        // Memecah query berdasarkan titik koma
        $queries = explode(';', $sql_content);
        $i = 1;
        $item = [];
        // Menjalankan setiap query
        foreach ($queries as $query) {
            // Menghilangkan spasi ekstra dan baris baru
            $query = trim($query);
            // Menjalankan query jika tidak kosong
            if (!empty($query)) {
                $result = $this->db->query($query);
                // Memeriksa hasil query  
                $item[] = $i . " Query executed successfully";

            }
            $i++;
        }
        $data = array(
            "item" => $item,
        );
        return $this->response->setJSON($data);

    }

    public function config()
    {
        // Buat objek response
        $response = service('response');

        // Atur header sebagai 'application/javascript'
        $response->setContentType('application/javascript');

        // Outputkan konten JavaScript
        echo 'var apiUrl = ';

        // Return response
        return $response;
    }

}
