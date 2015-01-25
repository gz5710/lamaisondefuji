// JavaScript Document
function addProType()
{
	//get all the necessary infos
	var Type = document.querySelector('#Type').value;
	var xhr;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
	  xhr=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
	  xhr=new ActiveXObject("Microsoft.XMLHTTP");
	}
	var url = "../admin.php/addProType?Type="+Type;
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
					alert("添加成功！");
					window.location.href='../admin.php/protype';
				}else{
					alert("添加失败！");
					}
		}
		console.log(result);
	}
	xhr.send();
}

function loadProType()
{
	var Request = new Object();
	Request = GetRequest();
	document.querySelector('#Type').ID = Request['ID'];
	document.querySelector('#Type').value = Request['Type'];
}

function saveProType()
{
	//get all the necessary infos
	var ID = document.querySelector('#Type').ID;
	var Type = document.querySelector('#Type').value;
	var xhr;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
	  xhr=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
	  xhr=new ActiveXObject("Microsoft.XMLHTTP");
	}
	var url = "../admin.php/saveProType?ID="+ID+"&Type="+Type;
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
					alert("更新成功！");
					window.location.href='../admin.php/protype';
				}else{
					alert("更新失败！");
					}
		}
		console.log(result);
	}
	xhr.send();
}