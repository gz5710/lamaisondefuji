// JavaScript Document

function login(){
	var username = document.querySelector('#username');
	var password = document.querySelector('#password');
	if(username.value == 'lamaisondefuji@gmail.com' && password.value == 'lamaisondefujihuang')
	{
		window.location.href='emailSender.html';
	}else{
		var tag = document.querySelector('#tag');
		tag.style.display = 'block';
	}
}