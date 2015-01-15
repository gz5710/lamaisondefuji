<?php
function sendGmail($app, $Account, $Pwd, $From, $ToList, $Subjet, $Content)
{
	$app['swiftmailer.options'] = array(
		'host' => 'ssl://smtp.gmail.com',
		'port' => '465',
		'username' => $Account,  //this place needs a String
		'password' => $Pwd,
		'encryption' => null,
		'auth_mode' => null
	);
	
	$message = \Swift_Message::newInstance()
        ->setSubject($Subjet)
        ->setFrom($From)		//this place needs a array
        ->setTo($ToList)
        ->setBody($Content)
        ->setContentType('text/html');

    return $app['mailer']->send($message);
}
?>