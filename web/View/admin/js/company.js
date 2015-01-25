// JavaScript Document
function saveCompany()
{
	//get all the necessary infos
	var title = document.querySelector('#Title').value;
	var content = encodeURIComponent(editor.getData());
	/*var Photo = document.querySelector('#Photo').src;*/
	var xhr;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
	  xhr=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
	  xhr=new ActiveXObject("Microsoft.XMLHTTP");
	}
	/*var url = "../admin.php/saveCompany?Name="+Name+"&Introduction="+Introduction+"&Photo="+Photo;*/
	var url = "../admin/admin.php/saveCompany?Title="+title+"&Content="+content;
	xhr.open("POST", url);
	xhr.responseType = 'text';		//IE doesn't support 'JSON', Safari doesn't support 'Text';
	xhr.onload = function() {
		var result = xhr.responseText;			//recive the reponse in format Text, and transform it in JSON. Then get the details from generated JSON variable.
		//show all the items in the menu		// SPECIFIQUE for IE and Safari browsers.
		console.log(result);
		if(result==undefined)
		{
			html = "Veuillez avoir l'acces du site par Chrome";
			}else{
				var res = JSON.parse(result);
				result = res.result;
				if(result == 1)
				{
					alert("发送成功！");
					document.querySelector('#Title').value = "";
					editor.setData("");
				}else{
					alert("发送失败！");
					}
		}
		console.log(result);
	}
	xhr.onerror = function(event) {
	  console.log('Erreur');
	}
	xhr.onabort = function(event) {
	  console.log("Annulé par l'utilisateur");
	}
	xhr.onprogress = function(event) {
	  console.log('Téléchargement...');
	}
	xhr.send();
}