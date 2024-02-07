<?php
require("./../vendor/autoload.php");

$openapi = \OpenApi\Generator::scan(['./../app/Controllers/Auth.php']);

header('Content-Type: application/json');
echo $openapi->toJson();