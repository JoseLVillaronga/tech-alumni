</div>
	<footer class="page-footer red darken-3">
		<div class="container">
			<div class="row">
				<div class="col s12 m8">
				  <h5 class="white-text">Teccam S.R.L.</h5>
		          <p class="grey-text text-lighten-4">Servicios de mantenimiento, diseño, asesoramiento, entrenamiento, reparación y logística ...</p>
		          <table class="responsive-table">
		          	<tr>
		          		<td>
		          			<a href="https://twitter.com/share" class="twitter-share-button" data-url="https://www.tech-support.com.ar" data-lang="es">Twittear</a>
		          			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
		          		</td>
		          		<td>
		          			<script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: es_ES</script><br />
						<script type="IN/Share" data-url="https://www.teccam.net<?php echo $redirecciona; ?>?<?php echo time(); ?>=1" data-counter="right"></script>
		          		</td>
		          		<td>
		          			<div
								  class="fb-like"
								  data-share="true"
								  data-width="260"
								  data-show-faces="true">
							</div>
		          		</td>
		          	</tr>
		          </table>
				</div>
				<div class="col s12 m2">
		          <h5 class="white-text">Conectarse</h5>
		          <ul id="pie">
		            <li><a class="grey-text text-lighten-3" href="http://www.teccam.net/contacto.php"><i class="material-icons">my_location</i> Contacto</a></li>
		            <?php if($_SESSION['paisC'] == 'UY'){echo "<li><a class=\"grey-text text-lighten-3\" href=\"mailto:logistica2@teccam.com.uy\"><i class=\"material-icons\">email</i> info@teccam.net</a></li>";}else{
		            	echo "<li><a class=\"grey-text text-lighten-3\" href=\"mailto:info@teccam.net\"><i class=\"material-icons\">email</i> info@teccam.net</a></li>";
		            } ?>
		            <?php if($_SESSION['paisC'] == 'UY'){echo "<li><a class=\"grey-text text-lighten-3\" href=\"tel:+59824007352\"><i class=\"material-icons\">call_end</i> 24007352</a></li>";}else{
		            	echo "<li><a class=\"grey-text text-lighten-3\" href=\"tel:+541145424511\"><i class=\"material-icons\">call_end</i> +54(11)4542-4511</a></li>";
		            } ?>
		            <?php if($_SESSION['paisC'] != 'UY'){echo "<li><a class=\"grey-text text-lighten-3\" href=\"tel:+541145422382\"><i class=\"material-icons\">print</i> +54(11)4542-2382</a></li>";} ?>
		          	<li><a class="grey-text text-lighten-3" href="https://www.facebook.com/teccamsrl" target="_blank"><img src="img/face.png" /> Facebook</a></li>
		          	<li><a class="grey-text text-lighten-3" href="javascript:void(0)" onclick="window.open('https://tech-support3.3cx.us/callus#click2talk569505','chat','toolbar=0,status=0,titlebar=no,width=600,height=350')"><i class="material-icons">chat</i> Chat y llamada desde la Web</a></li>
		          </ul>
				</div>
				<div class="col s12 m2" style="display: flex;justify-content: center;align-items: center;min-height: 240px;">
					<a href="http://qr.afip.gob.ar/?qr=034fh8EgMZPw6dCUWNeccA,," target="_blank"><img src="img/dfiscal.png" style="display: block;margin: auto;width: 99%;vertical-align: middle;"></a>
				</div>
			</div>
		</div>
			<div class="footer-copyright red darken-4">
				<div class="container">
					<?php
					if($_SESSION['paisC'] == 'UY' AND in_array($_SERVER['SERVER_NAME'], array('teccam.com.uy','www.teccam.com.uy'))){
						echo "© 2012";
					}else{
						echo "© 2010";
					}
					?>
					
			</div>
		</div>
	</footer>
</body>
</html>