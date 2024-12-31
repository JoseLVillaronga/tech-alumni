<?php
require_once "config.php";
if(!empty($_SESSION['usuario'])){
    $usu=new Usuario($_SESSION['usuario']);
}else{
	header("location: login.php");
	exit;
}
if($_SERVER['REQUEST_METHOD']=="POST"){
	$query="SELECT * FROM empresas ORDER BY emp_razon_social";
	$nPagina=$_POST['nPagina'];
	$filasPPagina=$_POST['filasPPagina'];
	$paginator=new Paginator($query,$nPagina,$filasPPagina);
}elseif($_SERVER['REQUEST_METHOD']=="GET"){
	$query="SELECT * FROM empresas ORDER BY emp_razon_social";
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
        <h4 style="text-align: center;">Empresas</h4>
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
        </div>
        <table class="highlight responsive-table">
        	<thead>
        		<tr>
        			<th>Razon Social</th>
        			<th>CUIT</th>
        			<th>Dirección</th>
        			<th>Teléfono</th>
        			<th>E-Mail</th>
        			<th>URL</th>
        			<th>Editar</th>
        			<th>Borrar</th>
        			<th>Agregar</th>
        		</tr>
        	</thead>
        	<tbody>
        		<?php foreach($paginator->getFetch() as $fila){ ?>
        		<tr>
        			<td><?php echo $fila['emp_razon_social']; ?></td>
        			<td><?php echo $fila['emp_cuit']; ?></td>
        			<td><?php echo $fila['emp_direccion']; ?></td>
        			<td><?php echo $fila['emp_telefono']; ?></td>
        			<td><?php echo $fila['emp_email']; ?></td>
        			<td><?php echo $fila['emp_web']; ?></td>
        			<td>
			          <a id="scale-demo" href="#!" class="btn-floating btn-small scale-transition green">
						 <i id="edit" class="material-icons waves-effect waves-light" onclick="location.href='empresa-e.php?id=<?php echo $fila['emp_id']; ?>'" title="Editar">edit</i>
					  </a>
        			</td>
        			<td>
			          <a id="scale-demo" href="#!" class="btn-floating btn-small scale-transition red">
						 <i id="del" class="material-icons waves-effect waves-light" ondblclick="location.href='empresa-d.php?id=<?php echo $fila['emp_id']; ?>'" title="borrar">delete</i>
					  </a>
        			</td>
        			<td>
			          <a id="scale-demo" href="#!" class="btn-floating btn-small scale-transition indigo">
						 <i id="add" class="material-icons waves-effect waves-light" onclick="location.href='empresa-a.php'" title="Agregar">add</i>
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
require_once "footer.php";
?>