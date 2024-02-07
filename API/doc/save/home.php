<?php
require("./../vendor/autoload.php");

$openapi = \OpenApi\Generator::scan(['./../app/Controllers/Home.php']);

header('Content-Type: application/json');
echo $openapi->toJson();