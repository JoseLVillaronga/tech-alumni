<?php
require_once '../config.php';
?>
<br /><input type="text" name="serie" value="<?php echo $_SESSION['rrepid']; ?>" style="background-color: #CFDDE4;width: 200px;" /><br />
<input type="button" class="form_button" value="MAC" onclick="traerRepTitMac(); traerRepMac();" />
<input type="button" class="form_button" value="ID" onclick="traerRepTitId(); traerRepId();" />
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" value="<?php echo $mac; ?>" name="mac" />
<?php $_SESSION['rrepid']=""; ?>