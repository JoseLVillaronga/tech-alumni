<?php
require_once '../config.php';
?>
<br /><input type="text" name="mac" value="<?php echo $_SESSION['rrepid']; ?>" style="background-color: #CFDDE4;width: 200px;" /><br />
<input type="button" class="form_button" value="ID" onclick="traerRepTitId(); traerRepId();" />
<input type="button" class="form_button" value="Serie" onclick="traerRepTitSer(); traerRepSer();" />
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" value="<?php echo $serie; ?>" name="serie" />
<?php $_SESSION['rrepid']=""; ?>