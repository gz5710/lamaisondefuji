// JavaScript Document

/* Reference of jQuery */
document.write("<script src='../js/jquery-1.3.2.min.js' type='text/javascript'></script>");


function uploadPic(){
	//创建一个iframe，把他的name值拼接一个随机数，并将该iframe追加到body节点后面
	var ifname="up"+Math.random();
	$('<iframe name="'+ifname+'" width="0" height="0" frameBorder="0"></iframe>').appendTo($('body'));
	//选择第一个form节点，并将其target属性指向iframe
	$('form:formPic').attr('target',ifname);
}

//the server code(PHP) will assign the value of 'newFilePath'
function showPic(newFilePath)
{
	var img = document.querySelector('#Photo');
	img.src = newFilePath;
	img.value = newFilePath;
}

function deleteRecord(Table, ID, Photo)
{
	if(!confirm("删除后就没了，你确定？"))
	{
		return;
	}
	var xhr;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
	  xhr=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
	  xhr=new ActiveXObject("Microsoft.XMLHTTP");
	}
	var url = "../admin.php/delete?Table="+Table+"&ID="+ID+"&Photo="+Photo;
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
					window.location.reload();
				}else{
					alert('删除失败！');
					}
		}
		console.log(result);
	}
	xhr.send();
}

function GetRequest() {
   var url = location.search; //获取url中"?"符后的字串
   var theRequest = new Object();
   if (url.indexOf("?") != -1) {
      var str = url.substr(1);
      strs = str.split("&");
      for(var i = 0; i < strs.length; i ++) {
		  var key = strs[i].split("=")[0];
         theRequest[key]=decodeURIComponent(strs[i].split(key + "=")[1]);
		 //alert(key + "=" + decodeURIComponent(strs[i].split(key + "=")[1]));
		 console.log(key + "=" + decodeURIComponent(strs[i].split(key + "=")[1]));
      }
   }   
   return theRequest;
}