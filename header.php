<?php
require_once "config.php";
$auth=new Usuario($_SESSION['usuario']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>SGCC - Alumni</title>
	<link rel="shortcut icon" href="img/favicon.png">
    <link href="css/icon.css" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min2.css"  media="screen,projection"/>
    <!--Esrtilo propio-->
	<link href="css/style.css" rel="stylesheet">
    <script src="assets/js/bootstrap-collapse.js"></script>
    <script src="assets/js/bootstrap-transition.js"></script>
    <script src="assets/js/bootstrap-tab.js"></script>
    <link href="assets/js/google-code-prettify/prettify.css" rel="stylesheet">
    <link href="js/jquery-ui.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="icofont/icofont.min.css">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta id="request-method" name="request-method" content="<?php echo($_SERVER['REQUEST_METHOD']); ?>" />
    <style>
    	#content {
    		min-height: 400px;
    	}
    </style>
<style type="text/css">
<!--
.Estilo1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
}
.Estilo2 {color: #000099}
-->
.menuTabla:hover {
	-moz-box-shadow: 0px 0px 9px rgba(0, 0, 0, .9);
	-webkit-box-shadow: 0px 0px 9px rgba(0, 0, 0, .9);
	box-shadow: 0px 0px 9px rgba(0, 0, 0, .9);
}
.menuTabla:active {
	-moz-box-shadow: 5px 5px 5px rgba(255, 0, 0, .7);
	-webkit-box-shadow: 5px 5px 5px rgba(255, 0, 0, .7);
	box-shadow: 5px 5px 5px rgba(255, 0, 0, .7);
}
.boton-celda:hover {
    -moz-box-shadow: 0px 1px 19px #000000;
    -webkit-box-shadow: 0px 1px 19px #000000;
    -ms-box-shadow: 0px 1px 19px #000000;
    box-shadow: 0px 1px 19px #000000;
}
.boton-celda:active {
    -moz-box-shadow:inset 0px 1px 19px #000000;
    -webkit-box-shadow:inset 0px 1px 19px #000000;
    -ms-box-shadow:inset 0px 1px 19px #000000;
    box-shadow:inset 0px 1px 19px #000000;
    background-color: #7F7F7F;
}
</style>
</head>
<body onload="document.getElementById('serie').focus();modificar();" onresize="modificar();" onkeydown="	if (event.keyCode == 27) window.open('','_self').close();if (event.keyCode == 13) location.href='tests-lista.php?mac='+document.getElementById('search').value;if (event.keyCode == 37) document.getElementById('atrasCSS').click();
				if (event.keyCode == 39) document.getElementById('adelanteCSS').click();LoadOnce();" class="fondo9">
    <!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/<?php if($_SERVER['REQUEST_SCRIPT']=="/test-info2.php"){echo "materialize.min2.js";}else{echo "materialize.min.js";} ?>"></script>
    <script src="assets/js/google-code-prettify/prettify.js"></script>
    <script src="assets/js/application.js"></script>
    <script type="text/javascript" src="js/inicio-m.js"></script>
    <!--Resto de la página-->
    <!-- Dropdown Structure -->
	<ul id="dropdown2" class="dropdown-content" style="z-index: 30;">
	  <li><a href="clientes.php" class="waves-effect">Usuarios</a></li>
	  <li><a href="empresas.php" class="waves-effect">Empresas</a></li>
	  <li><a href="cursos.php" class="waves-effect">Cursos</a></li>
	  <li><a href="visitas.php" class="waves-effect">Actividad Web</a></li>
	</ul>
	<ul id="dropdown3" class="dropdown-content" style="z-index: 30;">
	  <li><a href="miscursos.php" class="waves-effect">Mis Cursos</a></li>
	  <li><a href="misdatos.php" class="waves-effect">Mis Datos</a></li>
	  <li><a href="mensajes.php" class="waves-effect">Mensajes</a></li>
	</ul>
	<!-- Menú movil -->
    <ul class="side-nav" id="mobile-demo">
			<li class="logo red accent-4" id="menu-lateral-logo">
				<div style="height: 100%;width: 100%;background-color: rgba(255,255,255,.6);">
	            	<object width="220" height="150" data="js/teccam.html" style="margin-left: 30px;"></object>
	            </div>
	        </li>
        <?php
        	if(!empty($_SESSION['usuario'])){
        		echo "<li><a href=\"search.php\"><i class=\"material-icons\">search</i></a></li>";
            	echo "<li><a href=\"index.php\">Inicio</a></li>";
				if(in_array($auth->grupo->getNombre(), array("Administración","Docentes"))){
					echo "<li>
						        <ul class=\"collapsible collapsible-accordion\">
						          <li>
						            <a class=\"collapsible-header waves-effect\" style=\"background-image: none;\">Cursos<i class=\"material-icons\">arrow_drop_down</i></a>
						            <div class=\"collapsible-body\">
				        				<ul>
				        					<li><a href=\"clientes.php\" class=\"waves-effect\">Usuarios</a></li>
				        					<li><a href=\"empresas.php\" class=\"waves-effect\">Empresas</a></li>
				        					<li><a href=\"cursos.php\" class=\"waves-effect\">Cursos</a></li>
				        					<li><a href=\"visitas.php\" class=\"waves-effect\">Actividad Web</a></li>
				        				</ul>
				        			</div>
				        			</li>
				        		</ul>
				        	</li>";					
				}
				echo "<li>
							<ul class=\"collapsible collapsible-accordion\">
						        <li>
						            <a class=\"collapsible-header waves-effect\" style=\"background-image: none;\">Mi Cuenta<i class=\"material-icons\">arrow_drop_down</i></a>
						            <div class=\"collapsible-body\">
				       				<ul>
				       					<li><a href=\"miscursos.php\" class=\"waves-effect\">Mis Cursos</a></li>
				       					<li><a href=\"misdatos.php\" class=\"waves-effect\">Mis Datos</a></li>
				      					<li><a href=\"mensajes.php\" class=\"waves-effect\">Mensajes</a></li>
				       				</ul>
				       			</div>
				       			</li>
				       		</ul>
				      </li>";
            }
        ?>
        	
    </ul>
    <!-- Menú PC -->
    <div style="background-color: rgba(255,255,255,.65);">
    <div class="navbar-fixed">
        <nav class="grey darken-1">
          <div class="nav-wrapper">
            <a href="logout.php" title="Log Out" class="brand-logo"><img id="logo-cartel" src="img/favicon.png"></a>
            <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
                <?php
                	//echo "<li><a href=\"javascript:window.open('test.php','_blank')\">Test</a></li>";
                	if(!empty($_SESSION['usuario'])){
                		echo "<li><a href=\"search.php\"><i class=\"material-icons\">search</i></a></li>";
                 		echo "<li><a href=\"index.php\">Inicio</a></li>";
						if(in_array($auth->grupo->getNombre(), array("Administración","Docentes"))){
							echo "<li style=\"z-index: 10;\"><a class=\"dropdown-button waves-effect waves-light\" href=\"#\" data-activates=\"dropdown2\">Cursos<i class=\"material-icons right\">arrow_drop_down</i></a></li>";
						}
						echo "<li style=\"z-index: 10;\"><a class=\"dropdown-button waves-effect waves-light\" href=\"#\" data-activates=\"dropdown3\">Mi Cuenta<i class=\"material-icons right\">arrow_drop_down</i></a></li>";
					}
                ?>
                
            </ul>
          </div>
        </nav>
    </div>