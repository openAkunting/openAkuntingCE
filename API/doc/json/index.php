<?php

// Path ke berkas .env
$envFile = __DIR__ . '/../../.env';

// Periksa apakah berkas .env ada
if (!file_exists($envFile)) {
  die('.env file not found.');
}

// Baca isi berkas .env
$envVars = parse_ini_file($envFile);

// Periksa apakah variabel 'baseUrl' ada di dalam berkas .env
if (isset($envVars['baseUrl'])) {
   
  // Cetak nilai variabel 'baseUrl'
 
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: GET, POST");
  header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Token, Authorization");
  header("Access-Control-Allow-Credentials: true");
  header('Content-Type: application/json');
 
 
  if ($_GET['collection'] == 'auth') {
    $jsonString = file_get_contents($_GET['collection'].".json");
    $baseurl = ["{{baseUrl}}", "{{baseUrl.dev}}", "{{baseUrl.staging}}"];
    $replace = [ $envVars['baseUrl'],  $envVars['baseUrl.dev'],  $envVars['baseUrl.staging']];
    $jsonString = str_replace($baseurl , $replace , $jsonString);  
    echo $jsonString;
  } else {
    exit;
  }
} else {
  echo 'Variable baseUrl is not defined in .env file.'; 
}