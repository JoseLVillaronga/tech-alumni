<?php
header("Content-Type: text/html; charset=utf-8");
$clientes=json_decode(file_get_contents("http://localhost/webservices/json.php"));
echo "<pre>";
var_dump($clientes);
echo "</pre>";
