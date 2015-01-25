// JavaScript Document
function addPartner()
{
	//get all the necessary infos
	var Name = document.querySelector('#Name').value;
	var Resume = document.querySelector('#Resume').value;
	var Link = document.querySelector('#Link').value;
	var Photo = document.querySelector('#Photo').src;
	var xhr;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
	  xhr=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
	  xhr=new ActiveXObject("Microsoft.XMLHTTP");
	}
	var url = "../admin.php/addPartner?Name="+Name+"&Resume="+Resume+"&Link="+Link+"&Photo="+Photo;
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
					window.location.href='../admin.php/partner';
				}else{
					alert("添加失败！");
					}
		}
		console.log(result);
	}
	xhr.send();
}

function loadPartner()
{
	var Request = new Object();
	Request = GetRequest();
	document.querySelector('#Name').ID = Request['ID'];
	document.querySelector('#Name').value = Request['Name'];
	document.querySelector('#Resume').value = Request['Resume'];
	document.querySelector('#Link').value = Request['Link'];
	document.querySelector('#Photo').src = Request['Photo'];
}

function saveProject()
{
	//get all the necessary infos
	var ID = document.querySelector('#Name').ID;
	var Name = document.querySelector('#Name').value;
	var Resume = document.querySelector('#Resume').value;
	var Link = document.querySelector('#Link').value;
	var Photo = document.querySelector('#Photo').src;
	var xhr;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
	  xhr=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
	  xhr=new ActiveXObject("Microsoft.XMLHTTP");
	}
	var url = "../admin.php/savePartner?ID="+ID+"&Name="+Name+"&Resume="+Resume+"&Link="+Link+"&Photo="+Photo;
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
					window.location.href='../admin.php/partner';
				}else{
					alert("更新失败！");
					}
		}
		console.log(result);
	}
	xhr.send();
}