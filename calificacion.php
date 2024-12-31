<?php
require_once "config.php";
if(!empty($_SESSION['usuario'])){
    $usu=new Usuario($_SESSION['usuario']);
}else{
	header("location: login.php");
	exit;
}
$id=$_GET['id'];
$curso=new Curso($id);
$usuario=$_SESSION['usuario'];
$calificacion=new Calificacion($usuario,$id);
require_once "header.php";
?>
    <main class="container" id="content" style="background-color: rgba(255,255,255,.4);">
        <h4 style="text-align: center;">Calificación / <?php echo $curso->getDesignacion(); ?> / <?php echo $curso->getFechaInicio(); ?></h4>
        <br>
        <h6 style="padding: 10px;">Bien venid@ <?php echo $usu->getNombre()." ".$usu->getApellido().", tenes ".$usu->getMensajesNoLeidos()." mensajes sin leer ..."; ?></h6>
		<br>
		<table class="highlight responsive-table">
			<thead>
				<tr>
					<th>Alumno</th>
					<th>Curso</th>
					<th>Departamento</th>
					<th>Fecha Inicio</th>
					<th>Calificación</th>
					<th>Fecha</th>
					<th>Docente</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $calificacion->usuario->getNombre()." ".$calificacion->usuario->getApellido(); ?></td>
					<td title="<?php echo $curso->getDescripcion(); ?>"><?php echo $curso->getDesignacion(); ?></td>
					<td><?php echo $calificacion->departamento->getNombre(); ?></td>
					<td><?php echo $curso->getFechaInicio(); ?></td>
					<td><?php echo $calificacion->value->getValue(); ?></td>
					<td><?php echo $calificacion->getFecha(); ?></td>
					<td><?php echo $curso->docente->getNombre()." ".$curso->docente->getApellido(); ?></td>
				</tr>
			</tbody>
		</table>
		<div class="row">
			<div class="input-field col s12 m12" style="text-align: center;">
			      <a id="scale-demo" href="#!" class="btn-floating btn-large scale-transition indigo">
					 <i id="add" class="material-icons waves-effect waves-light" onclick="location.href='miscursos.php'" title="Volver">arrow_back</i>
				  </a>
			</div>
		</div>
		<br>
    </main>
<?php
require_once "footer.php";
?>