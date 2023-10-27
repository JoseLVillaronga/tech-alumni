<?php
require_once '../config.php';
?>
<br /><input type="text" name="id" value="<?php echo $_SESSION['rrepid']; ?>" style="background-color: #CFDDE4;width: 200px;" /><br />
<input type="button" class="form_button" value="Serie" onclick="traerRepTitSer(); traerRepSer();" />
<input type="button" class="form_button" value="MAC" onclick="traerRepTitMac(); traerRepMac();" />
<input type="hidden" value="<?php echo $serie; ?>" name="serie" />
<input type="hidden" value="<?php echo $mac; ?>" name="mac" />
<?php $_SESSION['rrepid']=""; ?>