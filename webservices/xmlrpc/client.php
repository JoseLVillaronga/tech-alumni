<?php
header("Content-Type: text/html; charset=utf-8");

	function do_call($host, $port, $request) { 

		$fp = fsockopen($host, $port, $errno, $errstr); 
		$query = "POST /webservices/xmlrpc/objServer.php HTTP/1.0\nUser_Agent: My Egg Client\nHost: ".$host."\nContent-Type: text/xml\nContent-Length: ".strlen($request)."\n\n".$request."\n"; 

		if (!fputs($fp, $query, strlen($query))) { 
		$errstr = "Write error"; 
		return 0; 
		} 

		$contents = ''; 
		while (!feof($fp)) { 
		$contents .= fgets($fp); 
		} 

		fclose($fp); 
		return $contents;

	} 

	$host = 'localhost'; 
	$port = 80;
	$request = xmlrpc_encode_request('noticias',''); 
	//echo $request;
	//die();
	$response = do_call($host, $port, $request); 
	
	//ESTO ES DEBIDO A QUE EL SERVIDOR DEVUELVE ADEMAS DE LA RESPUESTA LOS ENCABEZADOS HTTP!
	$xml=(substr($response, strpos($response, "\r\n\r\n")+4));
	//echo $xml;
	//die();
	$phpvars = xmlrpc_decode ($xml);

	//var_dump($phpvars);

?>
<table style='width:560px;border:1px solid grey;margin:auto;'>
	<?php foreach ($phpvars as $key){  ?>
	<tr style='background-color:#D8D8D8;'><td><h2 style='padding:10px;'>
		<?php echo $key['Titulo']; ?>
	</h2></td></tr>

	<tr><td style='padding:10px;background-color:blue;color:white;font-weight:600'><p>
		<?php echo $key['Copete']; ?>
	</p></td></tr>

	<tr><td style='padding:10px;'><p>
		<?php echo $key['Texto']; ?>
	</p></td></tr>
	<?php } ?>
</table>