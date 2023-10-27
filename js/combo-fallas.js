var traerFallas = function(){
	var xhr = new XMLHttpRequest();
	var artId = document.getElementById('artId');
	xhr.open("GET","combo-fallas.php?p="+artId.value);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var cajaf = document.getElementById("falla");
				cajaf.innerHTML = xhr.responseText;
			}
		}
	};
	xhr.send(null);
};