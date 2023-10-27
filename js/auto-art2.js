/**
 * @author José Luis Villaronga
 */
$(function(){
	$('#artId').autocomplete({
		'source':'traer-art2.php'
	});
});
var historial = function(id){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","cdr-precios-h.php?id="+id);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById('p'+id);
				caja.innerHTML = xhr.responseText;
				//caja.className = "nuevaClase";
			}
		}
	};
	xhr.send(null);
};
var cancelar = function(id){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","cdr-precios-b.php");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById('p'+id);
				caja.innerHTML = xhr.responseText;
				//caja.className = "nuevaClase";
			}
		}
	};
	xhr.send(null);
};