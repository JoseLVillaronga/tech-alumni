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
        <h4 style="text-align: center;">Mis Mensajes</h4>
        <h6 style="padding: 10px;">Bien venid@ <?php echo $usu->getNombre()." ".$usu->getApellido().", tenes ".$usu->getMensajesNoLeidos()." mensajes sin leer ..."; ?></h6>
		<h5>Bandeja de entrada</h5>
		<table class="highlight responsive-table" style="border-top: solid 2px black;border-bottom: solid 1px grey;">
			<thead>
				<tr>
					<th>Sender</th>
					<th>Asunto</th>
					<th>Fecha</th>
					<th>Prioridad</th>
					<th style="width: 70px;border-left: solid 1px grey;text-align: center;">
						<a id="scale-demo" href="#!" class="btn-floating btn-small scale-transition blue">
							<i id="add" class="material-icons waves-effect waves-light" onclick="location.href='nuevomensaje.php'" title="Nuevo Mensaje">create</i>
						</a>
					</th>
					<th style="width: 70px;text-align: center;">Leer</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach(Db::listar("SELECT men_id FROM mensaje WHERE men_leido = 0 AND men_receiver = '".$_SESSION['usuario']."'") as $fila){ 
				$mensaje=new Mensaje($fila['men_id']); ?>
				<tr>
					<td><?php echo $mensaje->sender->getNombre()." ".$mensaje->sender->getApellido(); ?></td>
					<td title="<?php echo $mensaje->getCuerpo(); ?>"><?php echo $mensaje->getAsunto(); ?></td>
					<td><?php echo $mensaje->getFecha(); ?></td>
					<td><?php echo $mensaje->tipo->getValue(); ?></td>
					<td style="width: 70px;border-left: solid 1px grey;text-align: center;"></td>
					<td style="width: 70px;text-align: center;">
			          <a id="scale-demo" href="#!" class="btn-floating btn-small scale-transition green">
						 <i id="add" class="material-icons waves-effect waves-light" onclick="location.href='leermensaje.php?id=<?php echo $fila['men_id']; ?>'" title="Leer">markunread</i>
					  </a>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<div class="row">
			<div class="input-field col s12 m12" style="text-align: center;">
			      <a id="scale-demo" href="#!" class="btn-floating btn-large scale-transition indigo">
					 <i id="add" class="material-icons waves-effect waves-light" onclick="location.href='index.php'" title="Inicio">arrow_back</i>
				  </a>
			<?php //echo "Hola !!!<br><pre>";print_r($obj->errores);echo "</pre>"; ?>
			</div>
		</div>
    </main>
<?php
require_once "footer.php";
?>