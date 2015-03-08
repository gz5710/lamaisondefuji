// JavaScript Document
//load all the images from indicated folder.
window.onload=function(){
	var folder = '1 menu gourmet';
	selectCategorie(folder);
}

//define the event of checking the box.
var totalPrice = 0;
function CheckItem(e){
	var name = e.value;
	var price = parseFloat(e.getAttribute('price')).toFixed(2);
	var command = document.querySelector('#tabCommand');
	var path = e.getAttribute('path');
	if(e.checked==true)
	{
		addItem(name,price,1,command,path);
//		alert(tbQuantite.parentNode.parentNode.getAttribute('name'));
		}else{
			deleteItem(path, command);
		}		
}

//add a new item into table and update the total price.
function addItem(name, price, quantite, tabCommand, path)
{
	//command is the table of command
	var tr = document.createElement('tr');
	var td = document.createElement('td');
	var td2 = document.createElement('td');
	if(path.indexOf('plats')<0){  //the chinese food and thailand food needn't image. Their paths all include a substring 'plat'.
		var img = document.createElement('img');
		img.setAttribute('src', path);
		img.setAttribute('class', 'imgItemCommand');
		td.appendChild(img);
	}
	td2.style.textAlign = 'right';
	td2.style.font = '14px "Trebuchet MS", Arial, Helvetica, sans-serif';
	td2.innerHTML += name + " : " +price + ' € ';
	//add two bouton controling the quantity of item.
	var btnPlus = document.createElement('input');
	var btnMoins = document.createElement('input');
	btnPlus.setAttribute('type','button');
	btnPlus.setAttribute('id','plus');
	btnPlus.setAttribute('class','btnQuantite');
	btnPlus.setAttribute('value','+');
	btnMoins.setAttribute('type','button');
	btnMoins.setAttribute('id','moins');
	btnMoins.setAttribute('class','btnQuantite');
	btnMoins.setAttribute('value','-');
	btnMoins.style.marginLeft = '30px';
	//add a number field(textbox).
	//<input type="text" id="tbQuantite" value='1' readonly="readonly"/>
	var tbQuantite = document.createElement('input');
	tbQuantite.setAttribute('type','text');
	tbQuantite.setAttribute('id','tbQuantite');
	tbQuantite.setAttribute('readonly','readonly');
	tbQuantite.setAttribute('value',quantite);
	//add the link to delete item.
	//<a href="">Supprimer</a>
	var imgDel = document.createElement('img');
	imgDel.setAttribute('src', '../embededPanier/image/Others/delete.png');
	imgDel.style.marginLeft = '10px';
	imgDel.style.marginRight = '10px';
	imgDel.style.cursor ='pointer';
	//attach all the childNodes on table.
	td2.appendChild(btnMoins);
	td2.appendChild(tbQuantite);
	td2.appendChild(btnPlus);
	td2.appendChild(imgDel);
	tr.appendChild(td);
	tr.appendChild(td2);
	tr.setAttribute('name', name);
	tr.setAttribute('price', price);
	tr.setAttribute('quantite', quantite);
	tr.setAttribute('path', path);
	tabCommand.appendChild(tr);
	totalPrice += price*quantite;
	showPrice();
	//link the events of bouton.
	btnPlus.onclick = function(){modifyQuan(tr, 1)};
	btnMoins.onclick = function(){modifyQuan(tr, -1)};
	imgDel.onclick = function(){deleteItem(path,tabCommand)};
}

//delete a existing item in table and reduce the total price.
function deleteItem(path, command)
{
	var itemList = new Array();
	itemList = document.getElementsByName('item');
	
	//find the checkbox corresponding the deleted one.
	for(var i=0; i<itemList.length; i++)
	{
		var p = itemList[i].getAttribute('path');
		//console(p);
		if(p == path)
		{
			itemList[i].checked = false;
			}
		
		}
		
	//update the price
	for(var i = 0; i < command.rows.length; i++)
	{
		if(command.rows[i].getAttribute('path') == path)
		{
			var quantite = parseInt(command.rows[i].getAttribute('quantite'));
			var price = parseFloat(command.rows[i].getAttribute('price')).toFixed(2);
			command.deleteRow(i);
			totalPrice -= price*quantite;
		}
	}
	showPrice();
}

//modify the quantity of item.
function modifyQuan(tr, num)
{
	num = parseInt(num);
	var quantite = parseInt(tr.getAttribute('quantite'));
	var price = parseFloat(tr.getAttribute('price')).toFixed(2);
	if((quantite>0 && quantite<99 && num>0)||(quantite<100 && quantite>1 && num<0))
	{
		quantite += num;
		tr.setAttribute('quantite', quantite);
		tr.querySelector('#tbQuantite').value = quantite;
		totalPrice += price*num;
		showPrice();
	}else{
		alert('La quantité est impérativement entre 1 et 99.');
		}
}

//show the total price in real-time
function showPrice()
{
	var th = document.querySelector('#divTotalPrice');
	if(totalPrice < 0)
	{
		totalPrice = 0 - totalPrice;
	}
	th.innerHTML = 'Total:  '+ totalPrice.toFixed(2) + ' €';	
}

function locking(){
	//verify if the client chose sth.
	var command = document.querySelector('#tabCommand');
	if(command.rows.length == 0)
	{
		alert('Veuillez choisir un plat au moins !');
		return false;
	}
	document.querySelector('#divScreenLocker').style.width='100%';
	document.querySelector('#divScreenLocker').style.height='2000px';
	document.querySelector('#divScreenLocker').style.display='block';
	document.querySelector('#divEmailForm').style.display='block';
}

function closeForm(){
	document.querySelector('#divScreenLocker').style.display='none';
	document.querySelector('#divEmailForm').style.display='none';
	document.querySelector('#divFormLocker').setAttribute('class', 'divEmailForm');
	return false;
}

var modeC = 'E';
function EorL(msg)
{
	var tbAddress = document.querySelector('#tbAddress');
	var opTimeE = document.querySelector('#opHeureE');
	var opTimeL = document.querySelector('#opHeureL');
	var emailForm = document.querySelector('#divEmailForm');
	var formLocker = document.querySelector('#divFormLocker');
	if(msg=='E')
	{
		tbAddress.style.display = 'none';
		opTimeL.style.display = 'none';
		opTimeE.style.display = 'block';
		emailForm.style.height = '350px';
		formLocker.style.height = '350px';
		modeC = 'E';
		}else{
			tbAddress.style.display = 'block';
			opTimeL.style.display = 'block';
			opTimeE.style.display = 'none';
			emailForm.style.height = '380px';
			formLocker.style.height = '380px';
			modeC = 'L';
			}
}

function confirmForm(){
	//get all the info of command.	
	var nameClient = document.querySelector('#tbName').value;
	var telClient = document.querySelector('#tbTel').value;
	var emailClient = document.querySelector('#tbEmail').value;
	var messageClient = document.querySelector('#tbMessage').value;
	var addressClient = document.querySelector('#tbAddress').value;
	var opTimeE = document.querySelector('#opHeureE select');
	var opTimeL = document.querySelector('#opHeureL select');
	var modePay = document.getElementsByName('paiement');
	var waitTimeE='';
	var waitTimeL='';
	for(var i = 0; i<opTimeE.length; i++)
	{
		if(opTimeE[i].selected == true){
			waitTimeE = opTimeE[i].innerText;
			}
	}
	for(var i = 0; i<opTimeL.length; i++)
	{
		if(opTimeL[i].selected == true){
			waitTimeL = opTimeL[i].innerText;
			}
	}
	var paiement = '';
	for(var i = 0; i<modePay.length; i++)
	{
		if(modePay[i].checked){
			paiement += ' | ' + modePay[i].value;
			}
	}
	if(paiement == '')
	{
		paiement = 'Pas indiqué';
		}else{
			paiement +=' |';
			}
	
	//verify the infos gained.
	if(nameClient.trim()=='' && telClient.trim()=='')
	{
		alert('Veuillez remplir tous les champs obligatoirs !');
		return;
	}
	if(modeC == 'L' && addressClient.trim()=='')
	{
		alert("Veuillez remplir l'adresse !");
		return;
	}
	
	var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
	if(!myreg.test(emailClient))
	{
		alert('Email invalide! ');
		return false;
	}

	//pass a command to Resto
	var command = document.querySelector('#tabCommand');
	var modeInfo = '';		//emporter by defaut.
	var addrLivr = '';
	var waitTime = '';
	if(modeC == 'L')				//livraison.
	{
		modeInfo = 'Livraison';
		waitTime = '<strong>Livre vers :</strong>  ' +waitTimeL+' <br>'
		addrLivr = '<strong>Adresse :</strong> '+addressClient+'<br>';
		}else{
			modeInfo = 'Emporter';
			waitTime = '<strong>Cherchez après :</strong> ' +waitTimeE+'<br>'
			}
	var now     = new Date(); 
    var year    = now.getFullYear();
    var month   = now.getMonth()+1; 
    var day     = now.getDate();
    var hour    = now.getHours();
    var minute  = now.getMinutes();
    var second  = now.getSeconds(); 
    if(month.toString().length == 1) {
        var month = '0'+month;
    }
    if(day.toString().length == 1) {
        var day = '0'+day;
    }   
    if(hour.toString().length == 1) {
        var hour = '0'+hour;
    }
    if(minute.toString().length == 1) {
        var minute = '0'+minute;
    }
    if(second.toString().length == 1) {
        var second = '0'+second;
    }   
    var dateTime = year+'/'+month+'/'+day+' '+hour+':'+minute+':'+second;   
	var commandHTML = '<strong><h2>'+modeInfo+'</h2></strong><br><strong>Arrivé :</strong> '+ dateTime
												+'<br><strong>Nom :</strong> '+nameClient
												+'<br> <strong>Tel :</strong> '+telClient
												+'<br> <strong>Email :</strong> '+emailClient
												+'<br> '+waitTime	//optional
												+'<strong>Mode de paiement :</strong> '+paiement
												+'<br> ' + addrLivr 	//optional
												+'<strong>Message :</strong> '+messageClient+'<br>';
	commandHTML += '<strong>Details :</strong> <ul>';
	for(var i = 0; i < command.rows.length; i++)
	{
		var name = command.rows[i].getAttribute('name');
		var quantite = parseInt(command.rows[i].getAttribute('quantite'));
		var price = parseFloat(command.rows[i].getAttribute('price')).toFixed(2);
		commandHTML += '<li>'+name+' : '+price+' € * '+quantite+'</li>';		
	}
	commandHTML += '</ul><br> <h2>TOTAL : <font color="red">'+totalPrice.toFixed(2)+' € </font></h2><br>';
	console.log(commandHTML);
	
	//Ajax
	document.querySelector('#divFormLocker').setAttribute('class', 'divFormLocker');	//lock the form in order to stop user to manipulate plus.
	var xhr;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
	  xhr=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
	  xhr=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xhr.open("GET", "../index.php/valider?emailClient=" + emailClient + "&command=" + commandHTML);
	xhr.responseType = 'text';		//IE doesn't support 'JSON', Safari doesn't support 'Text';
	xhr.onload = function() {
		var result = xhr.responseText;
		console.log(result);
		if(result==undefined)
		{
			alert("Veuillez avoir l'acces du site par Chrome");
			}else{
				var res = JSON.parse(result);
				var result1 = res.result1;
				var result2 = res.result2;
				if(result1==1 && result2 == 1)
				{
					alert('Votre commande est bien pris en compte !');
					window.location.reload();
					}else{
						alert('Echec, Veuillez verifier votre email.');
						document.querySelector('#divFormLocker').setAttribute('class', 'divEmailForm');
						}
				}
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
	
	
function selectCategorie(folder)
{
	var xhr;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
	  xhr=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
	  xhr=new ActiveXObject("Microsoft.XMLHTTP");
	}
	var url = "../index.php/loadImages?folder="+folder;
	xhr.open("GET", url);
	xhr.responseType = 'text';		//IE doesn't support 'JSON', Safari doesn't support 'Text';
	xhr.onload = function() {
		var table = document.querySelector('#tabMenu');
		var html = xhr.responseText;			//recive the reponse in format Text, and transform it in JSON. Then get the details from generated JSON variable.
		//show all the items in the menu		// SPECIFIQUE for IE and Safari browsers.
		console.log(html);
		if(html==undefined)
		{
			html = "Veuillez avoir l'acces du site par Chrome";
			}else{
				var res = JSON.parse(html);
				html = res.html;
				}		
		table.innerHTML = html;
		//change the title picture
		//changeTitlePic(folder);		//needn't anymore.
		//check all the items already commanded.
		checkExistingItems();
		console.log(html);
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

function checkExistingItems()
{
	//check by path of image of dish
	var command = document.querySelector('#tabCommand');
	for(var i = 0; i < command.rows.length; i++)
	{
		var pathC = command.rows[i].getAttribute('path');
		var itemList = new Array();
		itemList = document.getElementsByName('item');	
		//console.log(itemList.length);
		//find the checkbox corresponding the deleted one.
		for(var j=0; j<itemList.length; j++)
		{
			var pathM = itemList[j].getAttribute('path');
			console.log(pathC +'   : '+pathM);
			if(pathC == pathM)//condition unstable.
			{
				//console.log(pathC +'  =  '+pathM);
				itemList[j].checked = true;
				}
			
			}
	}
}

function changeTitlePic(folder)
{
	var titlePic = document.querySelector('#titlePic img');
	var path = '../embededPanier/image/Title/'+folder+'.jpg';
	titlePic.setAttribute('src', path);
	titlePic.setAttribute('alt', folder);
}