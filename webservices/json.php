<?php
header("Content-Type: text/html; charset=utf-8");
$array=array(
	array(
		"Nombre" => "Juan",
		"Apellido" => "Perez",
		),

	array(
		"Nombre" => "Pedro",
		"Apellido" => "Gomez",
		),
	);
echo json_encode($array);
