<?php
require_once 'config.php';
if(!empty($_SESSION['usuario'])){
    $usu=new Usuario($_SESSION['usuario']);
}else{
	header("location: login.php");
	exit;
}
if($_SERVER['REQUEST_METHOD']=="POST"){
	$curso=$_POST['curso'];
	$alumno=$_POST['alumno'];
	$query="SELECT * FROM visitas WHERE vi_id <> 0";
	if(!empty($curso)){$query.=" AND vi_pagina LIKE '%/clases.php?id=".$curso."%'";}
	if(!empty($alumno)){$query.=" AND vi_usuario = '".$alumno."'";}
	$query.=" ORDER BY vi_id DESC";
	$nPagina=$_POST['nPagina'];
	$filasPPagina=$_POST['filasPPagina'];
	$paginator=new Paginator($query,$nPagina,$filasPPagina);
}elseif($_SERVER['REQUEST_METHOD']=="GET"){
	$curso="";
	$alumno="";
	$query="SELECT * FROM visitas ORDER BY vi_id DESC";
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
require_once 'header.php';
?>
	<main class="container" id="content">
		<h4 style="text-align: center">Actividad Web</h4>
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
        <table class="highlight responsive-table">
        	<thead>
        		<tr>
        			<th>Curso</th>
        			<th>Alumno</th>
        			<th>Buscar</th>
        		</tr>
        	</thead>
        	<tbody>
        		<tr>
        			<td>
						<select id="curso" name="curso">
							<option value="">Todos ...</option>
							<?php  
							foreach(Db::listar("SELECT * FROM cursos") as $filaC){ ?>
							<option value="<?php echo $filaC['cur_id']; ?>" <?php if($curso==$filaC['cur_id']){echo "SELECTED";} ?>>
								<?php echo $filaC['cur_designacion']." / ".$filaC['cur_fecha_inicio']; ?>
							</option>
							<?php } ?>
						</select>
        			</td>
        			<td>
						<select id="alumno" name="alumno">
							<option value="">Todos ...</option>
							<?php  
							foreach(Db::listar("SELECT * FROM clientes WHERE cli_grupo <> 2 ORDER BY cli_nombre,cli_apellido") as $filaA){ ?>
							<option value="<?php echo $filaA['cli_usuario']; ?>" <?php if($alumno==$filaA['cli_usuario']){echo "SELECTED";} ?>>
								<?php echo $filaA['cli_nombre']." ".$filaA['cli_apellido']; ?>
							</option>
							<?php } ?>
						</select>
        			</td>
        			<td>
				        <a id="scale-demo" href="#!" class="btn-floating btn-large scale-transition green">
							<i id="add" class="material-icons waves-effect waves-light" onclick="document.forms['formulario'].submit();" title="Grabar">send</i>
						</a>
        			</td>
        		</tr>
        	</tbody>
        </table>
        </div>
			<table class="responsive-table">
				<thead>
					<tr>
						<th>Nro.</th>
						<th>Dirección Cliente</th>
						<th>Página</th>
						<th>Datos Cliente</th>
						<th>Fecha y Hora</th>
						<th>Usuario</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$query="SELECT * FROM visitas ORDER BY vi_id DESC";
				foreach($paginator->getFetch() as $fila){
				?>
				<tr>
					<td><?php echo $fila['vi_id']; ?></td>
					<td><?php echo $fila['vi_ip']; ?></td>
					<td style="font-weight: bold;"><?php echo $fila['vi_pagina']; ?></td>
					<td><?php echo $fila['vi_nav']; ?></td>
					<td><?php echo $fila['vi_fecha']; ?></td>
					<td style="font-weight: bold;"><?php echo $fila['vi_usuario']; ?></td>
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
require_once 'footer.php';
?>