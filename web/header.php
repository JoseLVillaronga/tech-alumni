<?php
if(!isset($description)){
	$description="Telecomunicaciones, diseño, asesoramiento, entrenamiento, reparación y logística";
}
if(!isset($imgFace)){
	$imgFace="/img/logo2.png";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo $titulo; ?></title>
	<link rel="shortcut icon" href="img/favicon.ico">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
	<!--Import Google Icon Font-->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!-- Compiled and minified CSS -->
	<link rel="stylesheet" href="css/materialize.min.css">
	<!-- CSS Propio -->
	<link rel="stylesheet" href="css/style.css">
    <link href="owl-carousel/owl.carousel.css" rel="stylesheet">
    <link href="owl-carousel/owl.theme.css" rel="stylesheet">
    <link href="owl-carousel/owl.transitions.css" rel="stylesheet">
    <link href="owl-carousel/owl.carousel.css" rel="stylesheet">
    <link href="owl-carousel/owl.theme.css" rel="stylesheet">
    <link href="owl-carousel/owl.transitions.css" rel="stylesheet">
    <script src="assets/js/bootstrap-collapse.js"></script>
    <script src="assets/js/bootstrap-transition.js"></script>
    <script src="assets/js/bootstrap-tab.js"></script>
    <link href="assets/js/google-code-prettify/prettify.css" rel="stylesheet">
	<!--Import jQuery before materialize.js-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<!-- Compiled and minified JavaScript -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
	<script src="assets/js/google-code-prettify/prettify.js"></script>
    <script src="owl-carousel/owl.carousel.js"></script>
    <script src="assets/js/application.js"></script>
	<!-- Inicializar -->
	<script src="js/inicio.js"></script>
	<meta name="description" content="Reparaciones y Logistica, Servicios de Ingenieria ...">
	<meta name="keywords" content="soporte, soporte tecnico, diseño, diseño hfc, desarrollo, aplicaciones, camaras, instalacion">
	<meta name="generator" content="brain, two hands and a keyboard">
	<meta property="og:url" content="https://<?php echo $_SERVER['SERVER_NAME'].$redirecciona; ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="Teccam S.R.L." />
	<meta property="og:description" content="<?php echo $description; ?>" />
	<meta property="og:image" content="<?php echo "https://".$_SERVER['SERVER_NAME'].$imgFace; ?>" />
	<meta property="og:image:url" content="<?php echo "https://".$_SERVER['SERVER_NAME'].$imgFace; ?>" />
	<meta property="og:image:alt" content="Teccam S.R.L." />
	<meta property="fb:app_id" content="1754245461486425" />
</head>
<body onload="document.getElementById('c').click();document.getElementById('d').click();" onkeydown="if (event.keyCode == 115) location.href='login.php';">
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1754245461486425',
      xfbml      : true,
      version    : 'v2.6'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/es_LA/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-128579670-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-128579670-1');
</script>
		<ul id="dropdown1" class="dropdown-content" style="z-index: 30;">
		  <li><a href="servicios.php" class="waves-effect">Ingenieria</a></li>
		  <li><a href="sistemas.php" class="waves-effect">Seguridad</a></li>
		  <li><a href="lab_reparaciones.php" class="waves-effect">Reparaciones</a></li>
		  <li><a href="laboratorios.php" class="waves-effect">Laboratorios</a></li>
		</ul>
	    <ul id="slide-out" class="side-nav">
			<li class="logo" id="menu-lateral-logo">
				<div style="height: 100%;width: 100%;background-color: rgba(255,255,255,.6);">
	            	<object width="220" height="150" data="js/teccam.html" style="margin-left: 30px;"></object>
	            </div>
	        </li>
			<li><a href="/en/" class="waves-effect">English Version</a></li>
	    	<li class="bold"><a href="inst.php" class="waves-effect">Institucional</a></li>
	    	<li class="bold"><a href="productos.php" class="waves-effect">Productos</a></li>
	      	<?php
	      	if(!empty($_SESSION['usuario'])){
	      		echo "<li class=\"bold\"><a href=\"abm.php\" class=\"waves-effect\">ABM</a></li>";
	      	}
	      	?>
	    	<li>
		        <ul class="collapsible collapsible-accordion">
		          <li>
		            <a class="collapsible-header waves-effect" style="background-image: none;">Servicios<i class="material-icons">arrow_drop_down</i></a>
		            <div class="collapsible-body">
		              <ul>
		                <li><a href="servicios.php" class="waves-effect">Ingenieria</a></li>
		                <li><a href="sistemas.php" class="waves-effect">Seguridad</a></li>
		                <li><a href="lab_reparaciones.php" class="waves-effect">Reparaciones</a></li>
		                <li class="bold"><a href="laboratorios.php" class="waves-effect">Laboratorios</a></li>
		              </ul>
		            </div>
		          </li>
		        </ul>
	    	</li>
	      	
	      	
	      	<li class="bold"><a href="clientes.php" class="waves-effect">Clientes</a></li>
	      	<li class="bold"><a href="https://solar.teccam.net" target="_blank" class="waves-effect">Energias Alternativas</a></li>
	      	<li class="bold"><a href="contacto.php" class="waves-effect">Contacto</a></li>
	      	<li class="bold"><a href="https://laboratorio.teccam.net:10443/aplicaciones.php" target="_blank">Aplicaciones</a></li>

	    </ul>
	<div class="navbar-fixed">
	  <nav class="nav1">

	    <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
          

	    <div class="nav-wrapper">
	      	<a href="<?php if($_SERVER['SERVER_NAME']=="www.teccam.com.uy" OR $_SERVER['SERVER_NAME']=="teccam.com.uy"){echo "https://www.teccam.com.uy";}else{echo "https://www.teccam.net";} ?>" class="brand-logo"><img id="logo-teccam" src="img/logo.png"></a>
	      	<ul class="right hide-on-med-and-down">
	      		<li><a href="/en/">English version</a></li>
		        <li><a href="inst.php" class="waves-effect waves-light">Institucional</a></li>
				<li><a href="productos.php" class="waves-effect waves-light">Productos</a></li>
	        	<?php
	        		if(!empty($_SESSION['usuario'])){
	        			echo "<li><a href=\"abm.php\" class=\"waves-effect waves-light\">ABM</a></li>";
	        		}
	        	?>
		        <li style="z-index: 10;"><a class="dropdown-button waves-effect waves-light" href="#" data-activates="dropdown1">Servicios<i class="material-icons right">arrow_drop_down</i></a></li>
	        	<li><a href="clientes.php" class="waves-effect waves-light">Clientes</a></li>
	        	<li><a href="https://solar.teccam.net" target="_blank" class="waves-effect waves-light">Energias Alternativas</a></li>
	        	<li><a href="contacto.php" class="waves-effect waves-light"><i class="material-icons left">my_location</i>Contacto</a></li>
	      	</ul>
	    </div>
	  </nav>
	</div>
<div id="caja-cont">