<?php

	//client side test to use for wsdl.class.php
	$auth=json_encode(array("username"=>"cP1413558736125","email"=>"sistema@teccam.net"));
	$url = "https://vps.teccam.net/soap/index.php?wsdl";
    $client = new SoapClient($url);

    $client->decode_utf8 = false;
    
	if(isset($_GET['usuario'])){
		$user=$_GET['usuario'];
	}else{
		$user = "";
	}
	if(isset($_GET['mac'])){
		$mac=$_GET['mac'];
	}else{
		$mac = "";
	}
	$result3= $client->test($user)."<br />";
	$result4= $client->cmPorMac($mac,$auth)."<br />";
    //echo($result)."<br />";
	//echo $result2."<br />";
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>Cliente SOAP</title>
</head>
	<body>
	  <form action="cliente.php" method="get" accept-charset="utf-8">
	  	Usuario
		<input type="text" name="usuario" value="<?php echo $user; ?>" /><br /><br />
		Nro. MAC 
		<input type="text" name="mac" value="<?php echo $mac; ?>" />
			   <p><input type="submit" value="Continue &rarr;"/></p>
	  </form>
	  <br />
	  <br />
	  <?php echo $result3."<br />"; ?>
	  <br />
	  <?php echo $result4."<br />"; ?>
	</body>
</html>