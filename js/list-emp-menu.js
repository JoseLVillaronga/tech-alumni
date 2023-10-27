var traerTxt = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","js/list-emp-menu.php");
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
var traerMarca = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","js/list-art-marca.php");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById('artmarca');
				caja.innerHTML = xhr.responseText;
				//caja.className = "nuevaClase";
			}
		}
	};
	xhr.send(null);
};
var traerNombre = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","js/list-art-nombre.php");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById('nombre');
				caja.innerHTML = xhr.responseText;
				//caja.className = "nuevaClase";
			}
		}
	};
	xhr.send(null);
};
var traerModelo = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","js/list-art-modelo.php");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById('artmodelo');
				caja.innerHTML = xhr.responseText;
				//caja.className = "nuevaClase";
			}
		}
	};
	xhr.send(null);
};
var traerFiltro = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","js/list-rep-filtro.php");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById('filtro');
				caja.innerHTML = xhr.responseText;
				//caja.className = "nuevaClase";
			}
		}
	};
	xhr.send(null);
};
var traerRepTitSer = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","js/list-rep-rrnts.php");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById('tituloId');
				caja.innerHTML = xhr.responseText;
				//caja.className = "nuevaClase";
			}
		}
	};
	xhr.send(null);
};
var traerRepSer = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","js/list-rep-rrncs.php");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById('campoId');
				caja.innerHTML = xhr.responseText;
				//caja.className = "nuevaClase";
			}
		}
	};
	xhr.send(null);
};
var traerRepTitMac = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","js/list-rep-rrntm.php");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById('tituloId');
				caja.innerHTML = xhr.responseText;
				//caja.className = "nuevaClase";
			}
		}
	};
	xhr.send(null);
};
var traerRepMac = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","js/list-rep-rrncm.php");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById('campoId');
				caja.innerHTML = xhr.responseText;
				//caja.className = "nuevaClase";
			}
		}
	};
	xhr.send(null);
};
var traerRepTitId = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","js/list-rep-rrnti.php");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById('tituloId');
				caja.innerHTML = xhr.responseText;
				//caja.className = "nuevaClase";
			}
		}
	};
	xhr.send(null);
};
var traerRepId = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","js/list-rep-rrnci.php");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById('campoId');
				caja.innerHTML = xhr.responseText;
				//caja.className = "nuevaClase";
			}
		}
	};
	xhr.send(null);
};
