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
//$calificacion=new Calificacion($usuario,$id);
//$fechaFinal=$curso->getFechaFinal();
if($curso->status->getDescripcion()!="Terminado"){
	header("location: miscursos.php?mensaje=Espere al final del curso antes de contestar la encuesta ...");
	exit;
}
$i=0;
$res=Db::listar("SELECT * FROM encuesta_preguntas");
if($_SERVER['REQUEST_METHOD']=="POST"){
	$encuesta=new Encuesta($curso,$usuario);
	$enc=$_POST['enc'];
	foreach($enc as $lista){
		$check[]=$lista['valor'];
	}
	$encuesta->loteEncuesta->setFecha(date("Y-m-d H:i:s"));
	$encuesta->loteEncuesta->setUsuario($usuario);
	if(!in_array("0", $check)){
		foreach($enc as $filaG){
			$encuesta->loteEncuesta->setPreguntaId($filaG['pregunta']);
			$encuesta->loteEncuesta->setPreguntaValueId($filaG['valor']);
			$encuesta->loteEncuesta->agregaADb();
		}
		$encuesta->__construct($curso,$usuario);
		if(count($encuesta->loteEncuesta->lote)>0){
			header("location: encuesta.php?id=".$id);
			exit;
		}
	}
}elseif($_SERVER['REQUEST_METHOD']=="GET"){
	$encuesta=new Encuesta($curso,$usuario);
	$encuestaId=$encuesta->getId();
	if(empty($encuestaId)){
		$encuesta->setFecha(date("Y-m-d H:i:s"));
		$encuesta->agregaADb();
	}
	if(count($encuesta->loteEncuesta->lote)>0){
		header("location: miscursos.php?mensaje=Ya contestaste la encuesta, muchas gracias !!!");
		exit;
	}
	$x=0;
	foreach($res as $variable){
		$enc[$x]['valor']=0;
		$x=$x+1;
	}
	$x=0;
}
require_once "header.php";
?>
    <main class="container row" id="content" style="background-color: rgba(255,255,255,.4);">
        <h4 style="text-align: center;">Encuesta / <?php echo $curso->getDesignacion(); ?> / <?php echo $curso->getFechaInicio(); ?></h4>
        <h6 style="padding: 10px;font-weight: bold;">Bien venid@ <?php echo $usu->getNombre()." ".$usu->getApellido().", tenes ".$usu->getMensajesNoLeidos()." mensajes sin leer ..."; ?></h6>
		<hr />
		<form method="post" id="formulario">
		<?php foreach ($res as $fila) { ?>
		<div class="input-field col s12 m9">
			<p><?php echo $fila['encp_descripcion']; ?></p>
			<input type="hidden" value="<?php echo $fila['encp_id'] ?>" name="enc[<?php echo $i; ?>][pregunta]">
		</div>
		<div class="input-field col s12 m3">
			<select id="valor<?php echo $i; ?>" name="enc[<?php echo $i; ?>][valor]">
				<option value="0">Seleccionar ...</option>
				<?php foreach(Db::listar("SELECT * FROM encuesta_values") as $value){ ?>
				<option value="<?php echo $value['encv_id']; ?>" <?php if($enc[$i]['valor']==$value['encv_id']){echo "SELECTED";} ?>><?php echo $value['encv_descripcion']; ?></option>
				<?php } ?>
			</select>
			<label for="valor<?php echo $i; ?>">Valor ...</label>
		</div>
		<hr class="input-field col s12 m12" style="border: none;border-bottom: solid 1px rgba(0,0,0,.3);">
		<?php
			$i=$i+1; 
		} ?>
		<div class="input-field col s12 m12">
			<div class="input-field col s12 m6" style="text-align: center;">
		          <a id="scale-demo" href="#!" class="btn-floating btn-large scale-transition green">
					 <i id="add" class="material-icons waves-effect waves-light" onclick="document.forms['formulario'].submit();" title="Grabar">send</i>
				  </a>
			</div>
			<div class="input-field col s12 m6" style="text-align: center;">
			      <a id="scale-demo" href="#!" class="btn-floating btn-large scale-transition indigo">
					 <i id="add" class="material-icons waves-effect waves-light" onclick="location.href='miscursos.php'" title="Volver">arrow_back</i>
				  </a>
			</div>
		</div>
		</form>
		<br>
    </main>
<?php
//echo "<pre>";
//print_r($encuesta->loteEncuesta->lote);
//echo "</pre>";
//echo "<h4>".$mensaje."</h4>";
require_once "footer.php";
?>