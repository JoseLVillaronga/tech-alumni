<?php

	ini_set("error_reporting",E_ALL);
	ini_set("display_errors","On");
	
	require "xmlRPCServer.class.php";
	require "test.class.php";
	
	//Todos los metodos que deseen exportarse a XML
	$objetoAServir	=	new test();
	$server			=	new xmlRPCServer();

	$server->addObject($objetoAServir);

	echo $server->start();

?>