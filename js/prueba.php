        <?php
        require_once "../config.php";
        ?>
                              	  				<select name="artId">
                        	  					<option value="">Elegir</option>
                        	  					<?php $obj = new Reparacion();
                        	  					$resRA = $obj->listaRepaArt();
												foreach ($resRA as $filaRA){
                        	  					?>
                        	  					<option value="<?php echo $filaRA['art_id']; ?>"
                        	  					<?php if($filaRA['art_id'] == $artId){ echo " SELECTED"; } ?>>
                        	  					<?php echo $filaRA['articulo']; ?>
                        	  					</option><?php } ?>
                        	  				</select>