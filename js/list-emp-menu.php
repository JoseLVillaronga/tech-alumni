<?php
require_once '../config.php';
require '../conection.php';
?>
<select name="rsocial" style="background-color: #CFDDE4;width: 200px;">
    <?php
    $queryRS = "SELECT * FROM empresas ORDER BY emp_razon_social";
    $resRS = mysqli_query($db,$queryRS);
    while($filaRS = mysqli_fetch_array($resRS)){
    ?>
    <option value="<?php echo $filaRS['emp_razon_social'] ?>"
    <?php if($filaRS['emp_razon_social'] == 'Teccam S.R.L.'){ echo " SELECTED"; } ?>>
    <?php echo $filaRS['emp_razon_social'] ?>
    </option><?php } ?>
</select>