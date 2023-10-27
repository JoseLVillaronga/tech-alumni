function modificar(){
	var h=window.innerHeight - 100;
	var i=window.innerHeight - 180;
	document.getElementById('content').style.minHeight=h+"px";
	document.getElementById('content2').style.minHeight=i+"px";
}
var hora = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","fecha.php?nc="+Math.random());
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("hora");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
setInterval(hora,60000);

var tiempo = function(){
	window.open('https://www.tutiempo.net/s-widget/tt_NXx8MzgyNjI2fG58bnxufDMzMzkxfDMwfDE1fDF8MXw1fDN8MjV8c3xzfG58RTg2RjZGfDcxQjlGMHx8fDAwMDAwMHw2NnwzfDg2fDYwfDE3MnwzMHwxMDJ8MHw2MjN8OTl8NzJ8NTF8MjB8MjB8Mzd8NzB8Mzh8Q3Z8MXw%2C&nc='+Math.random(),'TTF_FitwbhthY1Dc8F5UKAujjjjjj6uUL88lbtkt1syIakD');
}
setInterval(tiempo,900000);

var ax = function(){
	window.open('ax.php?nc='+Math.random(),'ax');
}
setInterval(ax,5000);

var cambiaFondoTitulo = function(){
	console.log(document.getElementById("titulo").style.backgroundColor);
	if(document.getElementById("titulo").style.backgroundColor === "rgba(124, 179, 66, 0.75)"){
		document.getElementById("titulo").style.backgroundColor = "rgba(174,213,129,.75)";
	}else if(document.getElementById("titulo").style.backgroundColor === "rgba(174, 213, 129, 0.75)"){
		document.getElementById("titulo").style.backgroundColor = "rgba(51,105,30,.75)";
	}else{
		document.getElementById("titulo").style.backgroundColor = "rgba(124,179,66,0.75)";
	}
}
setInterval(cambiaFondoTitulo,6000);

var videoOriginal = function(i){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","video.php?id=12&h="+i);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("tv");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
var videoOriginalFS = function(i){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","video.php?id=12&h="+i);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("content2");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
var marcoOriginal = function(){
	location.href = 'index.php';
}

var videoTN = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","video.php?id=7");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("tv");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
var videoTNFS = function(i){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","video.php?id=7&h="+i);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("content2");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}

var videoAmericaTV = function(i){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","video.php?id=1&h="+i);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("tv");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
var videoAmericaTVFS = function(i){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","video.php?id=1&h="+i);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("content2");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}

var videoLosSimpsons = function(i){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","video.php?id=9&h="+i);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("tv");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
var videoLosSimpsonsFS = function(i){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","video.php?id=9&h="+i);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("content2");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}

var videoVorterix = function(i){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","video.php?id=10&h="+i);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("tv");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
var videoVorterixFS = function(i){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","video.php?id=10&h="+i);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("content2");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}

var videoCanal7 = function(i){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","video.php?id=3&h="+i);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("tv");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
var videoCanal7FS = function(i){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","video.php?id=3&h="+i);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("content2");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}

var videoC5N = function(i){
	var xhr = new XMLHttpRequest(i);
	xhr.open("GET","video.php?id=2&h="+i);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("tv");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
var videoC5NFS = function(i){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","video.php?id=2&h="+i);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("content2");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}

var videoHispanTV = function(i){
	var xhr = new XMLHttpRequest(i);
	xhr.open("GET","video.php?id=5&h="+i);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("tv");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
var videoHispanTVFS = function(i){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","video.php?id=5&h="+i);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("content2");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}

var videoKFP3 = function(i){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","video.php?id=11&h="+i);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("tv");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
var videoKFP3FS = function(){
	var xhr = new XMLHttpRequest(i);
	xhr.open("GET","video.php?id=11&h="+i);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("content2");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}


var radioUnderground = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","radio/undergroundradio.php");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("radio");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
var radioNull = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","radio.php?id=1");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("radio");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
var radioER = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","radio.php?id=2");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("radio");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
var radioOeste = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","radio.php?id=5");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("radio");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
var radioFIX = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","radio.php?id=3");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("radio");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
var radioFMBlues = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","radio.php?id=4");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("radio");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}

function switchVideo(){
	if (event.keyCode == 113 && event.ctrlKey == false){
		var i = 474;
		videoOriginal();
	}else if (event.keyCode == 113 && event.ctrlKey == true){
		marcoOriginal();
	}else if (event.keyCode == 86 && event.ctrlKey == true){
		videoOriginalFS();
	}else if (event.keyCode == 117 && event.ctrlKey == false){
		var i = 474;
		videoTN(i);
	}else if (event.keyCode == 117 && event.ctrlKey == true){
		var i=window.innerHeight - 170;
		videoTNFS(i);
	}else if (event.keyCode == 121 && event.ctrlKey == false){
		var i = 474;
		videoAmericaTV(i);
	}else if (event.keyCode == 121 && event.ctrlKey == true){
		var i=window.innerHeight - 170;
		videoAmericaTVFS(i);
	}else if (event.keyCode == 119 && event.ctrlKey == false){
		var i = 474;
		videoC5N(i);
	}else if (event.keyCode == 119 && event.ctrlKey == true){
		var i=window.innerHeight - 170;
		videoC5NFS(i);
	}else if (event.keyCode == 120 && event.ctrlKey == false){
		var i = 474;
		videoHispanTV(i);
	}else if (event.keyCode == 120 && event.ctrlKey == true){
		var i=window.innerHeight - 170;
		videoHispanTVFS(i);
	}else if (event.keyCode == 118 && event.ctrlKey == false){
		var i = 474;
		videoCanal7(i);
	}else if (event.keyCode == 118 && event.ctrlKey == true){
		var i=window.innerHeight - 170;
		videoCanal7FS(i);
	}else if (event.keyCode == 49 && event.ctrlKey == true){
		radioER();
	}else if (event.keyCode == 50 && event.ctrlKey == true){
		radioUnderground();
	}else if (event.keyCode == 51 && event.ctrlKey == true){
		radioOeste();
	}else if (event.keyCode == 52 && event.ctrlKey == true){
		radioFIX();
	}else if (event.keyCode == 53 && event.ctrlKey == true){
		radioFMBlues();
	}else if (event.keyCode == 56 && event.ctrlKey == true){
		radioNull();
	}
}