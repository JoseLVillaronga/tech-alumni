<?php
require_once "config.php";
if(!empty($_SESSION['usuario'])){
    $usu=new Usuario($_SESSION['usuario']);
}else{
	header("location: login.php");
	exit;
}
if(isset($_GET['mensaje'])){
	$mensaje=$_GET['mensaje'];
}else{
	$mensaje="";
}
require_once "header.php";
?>
    <main class="container" id="content" style="background-color: rgba(255,255,255,.4);">
        <h4 style="text-align: center;">Mis Cursos</h4>
        <h4 style="color: red;"><?php echo $mensaje; ?></h4>
        <h6 style="padding: 10px;">Bien venid@ <?php echo $usu->getNombre()." ".$usu->getApellido().", tenes ".$usu->getMensajesNoLeidos()." mensajes sin leer ..."; ?></h6>
        <table class="highlight responsive-table">
        	<thead>
        		<tr>
        			<th>Designación</th>
        			<th>Duración</th>
        			<th>Status</th>
        			<th>Docente</th>
        			<th>Fecha Inicio</th>
        			<th>Fecha Cierre</th>
        			<th style="text-align: center;">Cancelar Ins.</th>
        			<th style="text-align: center;">Clases</th>
        			<th style="text-align: center;">Calific.</th>
        			<th style="text-align: center;">Diploma</th>
        			<th style="text-align: center;">Encuesta</th>
        		</tr>
        	</thead>
        	<tbody>
        		<?php  
        		foreach(Db::listar("SELECT cur_id FROM inscripcion WHERE cli_usuario = '".$_SESSION['usuario']."'") as $fila){
        		$obj=new Curso($fila['cur_id']);?>
        		<tr>
        			<td title="<?php echo $obj->getDescripcion(); ?>"><?php echo $obj->getDesignacion(); ?></td>
        			<td><?php echo $obj->getDuracion()." clases" ?></td>
        			<td><?php echo $obj->status->getDescripcion(); ?></td>
        			<td><?php echo $obj->docente->getNombre()." ".$obj->docente->getApellido(); ?></td>
        			<td><?php echo $obj->getFechaInicio(); ?></td>
					<td><?php echo $obj->getFechaFinal(); ?></td>
        			<td style="text-align: center;">
			          <a id="scale-demo" href="#!" class="btn-floating btn-small scale-transition red">
						<i id="add" class="material-icons waves-effect waves-light" onclick="location.href='signmedown.php?id=<?php echo $fila['cur_id']; ?>'" title="Cancelar Inscripción">assignment_returned
						</i>
					  </a>
        			</td>
       				<td style="text-align: center;">
			          <a id="scale-demo" href="#!" class="btn-floating btn-small scale-transition green">
						<i id="add" class="material-icons waves-effect waves-light" onclick="location.href='clases.php?id=<?php echo $fila['cur_id']; ?>'" title="Clases">folder_open
						</i>
					  </a>
        			</td>
       				<td style="text-align: center;">
			          <a id="scale-demo" href="#!" class="btn-floating btn-small scale-transition green">
						<i id="add" class="material-icons waves-effect waves-light" onclick="location.href='calificacion.php?id=<?php echo $fila['cur_id']; ?>'" title="Calificación">border_color
						</i>
					  </a>
        			</td>
       				<td style="text-align: center;">
			          <a id="scale-demo" href="#!" class="btn-floating btn-small scale-transition green">
						<i id="add" class="icofont-certificate-alt-2 waves-effect waves-light" onclick="location.href='diploma.php?id=<?php echo $fila['cur_id']; ?>'" title="Diploma">
						</i>
					  </a>
        			</td>
       				<td style="text-align: center;">
			          <a id="scale-demo" href="#!" class="btn-floating btn-small scale-transition green">
						<i id="add" class="material-icons waves-effect waves-light" onclick="location.href='encuesta.php?id=<?php echo $fila['cur_id']; ?>'" title="Encuesta">assignment_turned_in
						</i>
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
        <br>
    </main>
<?php
require_once "footer.php";
?>