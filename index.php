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
        <h4 style="text-align: center;">Sistema de gestión de Cursos de Capacitación</h4>
        <br>
        <h6 style="padding: 10px;">Bien venid@ <?php echo $usu->getNombre()." ".$usu->getApellido().", tenes ".$usu->getMensajesNoLeidos()." mensajes sin leer ..."; ?></h6>
        <h5>Próximos Cursos</h5>
        <table class="highlight responsive-table">
        	<thead>
        		<tr>
        			<th>Designación</th>
        			<th>Descripción</th>
        			<th>Duración</th>
        			<th>Status</th>
        			<th>Docente</th>
        			<th>Fecha Inicio</th>
        			<th>Inscribirse</th>
        		</tr>
        	</thead>
        	<tbody>
        		<?php  
        		foreach(Db::listar("SELECT cur_id FROM cursos WHERE cs_id <> 3 AND cur_fecha_inicio > DATE(NOW()) ORDER BY cur_fecha_inicio") as $fila){
        		$obj=new Curso($fila['cur_id']);?>
        		<tr>
        			<td><?php echo $obj->getDesignacion(); ?></td>
        			<td><?php echo $obj->getDescripcion(); ?></td>
        			<td><?php echo $obj->getDuracion()." clases" ?></td>
        			<td><?php echo $obj->status->getDescripcion(); ?></td>
        			<td><?php echo $obj->docente->getNombre()." ".$obj->docente->getApellido(); ?></td>
        			<td><?php echo $obj->getFechaInicio(); ?></td>
        			<td>
			          <a id="scale-demo" href="#!" class="btn-floating btn-large scale-transition green">
						 <i id="add" class="material-icons waves-effect waves-light" onclick="location.href='signmeup.php?id=<?php echo $fila['cur_id']; ?>'" title="Inscribirse">assignment</i>
					  </a>
        			</td>
        		</tr>
        		<?php } ?>
        	</tbody>
        </table><br>
    </main>
<?php
require_once "footer.php";
?>