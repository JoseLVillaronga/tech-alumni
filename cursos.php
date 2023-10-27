<?php
require_once "config.php";
if(!empty($_SESSION['usuario'])){
    $usu=new Usuario($_SESSION['usuario']);
}else{
	header("location: login.php");
	exit;
}
if($_SERVER['REQUEST_METHOD']=="POST"){
	$status=$_POST['status'];
	$query="SELECT cur_id FROM cursos";
	if($status!=1){$query.=" WHERE cs_id <> 3";}
	$query.=" ORDER BY cur_fecha_inicio";
	$nPagina=$_POST['nPagina'];
	$filasPPagina=$_POST['filasPPagina'];
	$paginator=new Paginator($query,$nPagina,$filasPPagina);
}elseif($_SERVER['REQUEST_METHOD']=="GET"){
	$status;
	$query="SELECT cur_id FROM cursos WHERE cs_id <> 3 ORDER BY cur_fecha_inicio";
	if(empty($_GET['nPagina'])){
		$nPagina="1";
	}else{
		$nPagina=$_GET['nPagina'];
	}
	if(empty($_GET['filasPPagina'])){
		$filasPPagina="10";
	}else{
		$filasPPagina=$_GET['filasPPagina'];
	}
	$paginator=new Paginator($query,$nPagina,$filasPPagina);
}
require_once "header.php";
?>
    <main class="container" id="content" style="background-color: rgba(255,255,255,.4);">
        <h4 style="text-align: center;">Cursos</h4>
        <br>
        <h6 style="padding: 10px;">Bien venid@ <?php echo $usu->getNombre()." ".$usu->getApellido().", tenes ".$usu->getMensajesNoLeidos()." mensajes sin leer ..."; ?></h6>
        <form method="post" id="formulario">
        <div style="text-align: center;background-color: rgba(0,0,128,.1);">
	        <input type="button" title="Retroceso Página" id="atrasCSS" value="<<=" <?php $paginator->atrasCSS(); ?> />
	        <input type="button" title="Avance Página" id="adelanteCSS" value="=>>" <?php $paginator->adelanteCSS(); ?> />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	        Página Nro. 
	        <input type="text" id="nPagina" name="nPagina" onchange="document.forms['formulario'].action='<?php echo str_replace("/alumni/","",$_SERVER['SCRIPT_NAME']); ?>'; document.forms['formulario'].submit();" value="<?php echo $nPagina; ?>" style="width: 35px;" /> de 
	        <?php echo $paginator->getTotalPaginas(); ?>
	        &nbsp;&nbsp;&nbsp;&nbsp;
	        Cantidad registros p / Página <input type="text" name="filasPPagina" onchange="document.forms['formulario'].action='<?php echo str_replace("/alumni/","",$_SERVER['SCRIPT_NAME']); ?>'; document.forms['formulario'].submit();" value="<?php echo $paginator->getFilasPPagina(); ?>" style="width: 35px;" />
	        &nbsp;&nbsp;&nbsp;&nbsp;
	        <input type="checkbox" name="status" id="status" value="1" <?php if($status==1){echo "CHECKED";} ?>>
	        <label for="status">Todos ...</label>
	        <input type="submit" value="Filtrar">
        </div>
        <table class="highlight responsive-table">
        	<thead>
        		<tr>
        			<th>Designación</th>
        			<th>Descripción</th>
        			<th>Duración</th>
        			<th>Status</th>
        			<th>Docente</th>
        			<th>Fecha Inicio</th>
        			<th>Fecha Final</th>
        			<th>Insc.</th>
        			<th>Editar</th>
        			<th>Cont.</th>
        			<th>Borrar</th>
        			<th>Agregar</th>
        		</tr>
        	</thead>
        	<tbody>
        		<?php foreach($paginator->getFetch() as $fila){ ?>
        		<tr>
        			<?php $obj=new Curso($fila['cur_id']); ?>
        			<td><?php echo $obj->getDesignacion(); ?></td>
        			<td><?php echo $obj->getDescripcion(); ?></td>
        			<td><?php echo $obj->getDuracion()." clases"; ?></td>
        			<td><?php echo $obj->status->getDescripcion(); ?></td>
        			<td><?php echo $obj->docente->getNombre()." ".$obj->docente->getApellido(); ?></td>
        			<td><?php echo $obj->getFechaInicio(); ?></td>
        			<td><?php echo $obj->getFechaFinal(); ?></td>
        			<td>
			          <a id="scale-demo" href="#!" class="btn-floating btn-small scale-transition green">
						 <i id="edit" class="material-icons waves-effect waves-light" onclick="location.href='inscripciones.php?id=<?php echo $fila['cur_id']; ?>'" title="Inscripciones">assignment</i>
					  </a>
        			</td>
        			<td>
			          <a id="scale-demo" href="#!" class="btn-floating btn-small scale-transition green">
						 <i id="edit" class="material-icons waves-effect waves-light" onclick="location.href='curso-e.php?id=<?php echo $fila['cur_id']; ?>'" title="Editar">edit</i>
					  </a>
        			</td>
        			<td>
			          <a id="scale-demo" href="#!" class="btn-floating btn-small scale-transition green">
						 <i id="edit" class="material-icons waves-effect waves-light" onclick="location.href='contenido-e.php?id=<?php echo $fila['cur_id']; ?>'" title="Contenidos">folder_open</i>
					  </a>
        			</td>
        			<td>
			          <a id="scale-demo" href="#!" class="btn-floating btn-small scale-transition red">
						 <i id="del" class="material-icons waves-effect waves-light" ondblclick="location.href='curso-d.php?id=<?php echo $fila['cur_id']; ?>'" title="borrar">delete</i>
					  </a>
        			</td>
        			<td>
			          <a id="scale-demo" href="#!" class="btn-floating btn-small scale-transition indigo">
						 <i id="add" class="material-icons waves-effect waves-light" onclick="location.href='curso-a.php'" title="Agregar">add</i>
					  </a>
        			</td>
        		</tr>
        		<?php } ?>
        	</tbody>
        </table>
        </form>
		<div class="row">
			<div class="input-field col s12 m12" style="text-align: center;">
			      <a id="scale-demo" href="#!" class="btn-floating btn-large scale-transition indigo">
					 <i id="add" class="material-icons waves-effect waves-light" onclick="location.href='index.php'" title="Inicio">arrow_back</i>
				  </a>
			<?php //echo "Hola !!!<br><pre>";print_r($obj->errores);echo "</pre>"; ?>
			</div>
		</div>
    </main>
<script type="text/javascript">
$(document).ready(function()
    {
    if($("#nPagina").val() < 0){
    	$("#nPagina").val("1");
    	$("#formulario").attr("action","<?php echo str_replace("/alumni/","",$_SERVER['SCRIPT_NAME']); ?>");
    	$("#formulario").submit();
    }
    if($("#nPagina").val() > <?php echo $paginator->getTotalPaginas(); ?>){
    	$("#nPagina").val("<?php echo $paginator->getTotalPaginas(); ?>");
    	$("#formulario").attr("action","<?php echo str_replace("/alumni/","",$_SERVER['SCRIPT_NAME']); ?>");
    	$("#formulario").submit();
    }
    $("#atrasCSS").click(function () {
	    //Retroceso de Página
	    if($("#nPagina").val() > 1){
		    $("#nPagina").val("<?php echo $nPagina-1; ?>");
		    $("#formulario").attr("action","<?php echo str_replace("/alumni/","",$_SERVER['SCRIPT_NAME']); ?>");
		    $("#formulario").submit();	    	
	    }else{
	    	$("#nPagina").val("1");
	    }
    });
    $("#adelanteCSS").click(function () {
	    //Avance de Página
	    if($("#nPagina").val() < <?php echo $paginator->getTotalPaginas(); ?>){
		    $("#nPagina").val("<?php echo $nPagina+1; ?>");
		    $("#formulario").attr("action","<?php echo str_replace("/alumni/","",$_SERVER['SCRIPT_NAME']); ?>");
		    $("#formulario").submit();  	
	    }else{
	    	$("#nPagina").val("<?php echo $paginator->getTotalPaginas(); ?>");
	    }
    });    
});
</script>
<?php
//echo $_SERVER['SCRIPT_NAME'];
require_once "footer.php";
?>