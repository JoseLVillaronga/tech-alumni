var traerTxt = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","list-emp-menu-e.php");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById('respuesta');
				caja.innerHTML = xhr.responseText;
				//caja.className = "nuevaClase";
			}
		}
	};
	xhr.send(null);
};