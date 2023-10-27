<?php
require_once "config.php";
if(!empty($_SESSION['usuario'])){
    $usu=new Usuario($_SESSION['usuario']);
}else{
	header("location: login.php");
	exit;
}
require_once "header.php";
?>
    <main class="container" id="content" style="background-color: rgba(255,255,255,.4);">
        <h4 style="text-align: center;">Busqueda ...</h4>
        <br>
        <h6 style="padding: 10px;">Bien venid@ <?php echo $usu->getNombre()." ".$usu->getApellido().", tenes ".$usu->getMensajesNoLeidos()." mensajes sin leer ..."; ?></h6>
        <br>
        <div class="row">
			<div class="input-field col s12 m6">
			   	<input placeholder="Buscar ..." id="search" type="search" class="validate" name="search">
			   	<label for="search">Buscar ...</label>
			</div>
			<div class="input-field col s12 m6" style="text-align: center;">
				<a href="javascript:void(0)" onkeypress="" 
				 onclick="location.href='tests-lista.php?mac='+document.getElementById('search').value" class="btn-floating btn-large waves-effect waves-light indigo"><i class="material-icons">search</i></a>
			</div>
		</div>
    </main>
<script>
	document.getElementById('search').focus();
</script>
<?php
require_once "footer.php";
?>