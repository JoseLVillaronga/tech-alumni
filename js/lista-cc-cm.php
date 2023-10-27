<?php
require_once '../config.php';
$cq=new ControlCalidad($_GET['id']);
echo $cq->getNMuestras();
