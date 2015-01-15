<?php

require_once __DIR__.'/../vendor/autoload.php';
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\SwiftmailerServiceProvider());

$app->get('/', function() use ($app) {
    return $app->sendFile('View/homepage.html');
});

$app->get('/panier', function(Application $app, Request $req) use ($app) {
    return $app->sendFile('embededPanier/panier.html');
});

$app->get('/loadImages', function(Application $app, Request $req) use ($app) {
    $folder = $req->query->get('folder');
		$path = 'embededPanier/image/Commandez/'.$folder.'/';
		$filetype = '*.jpg';
		$html = '';
		$num = 0;
		$files = glob($path.$filetype, GLOB_NOSORT);
		natsort($files);	//sort the array by number.
		foreach ( $files as $file) {
			$filename = explode('/', $file)[4];		//modify according the level of path.
			$name = explode('-', $filename)[0];
			$price = explode('-', $filename)[1];
			
			//style by defaut.
			$colNum = 3;	//how many elements lying in one row. The table will allocate the space(Width and Height) automatically to the images.
			$isDisplay = '';
			$isNextLine = '';
			$isLeft = '';
			$itemImgClass = 'twoXthree';
			
			if($folder == '1-9 Menu midi')
			{
				$colNum = 2;
				$itemImgClass = 'threeXtwo';
			}else if($folder=='plats chinois' || $folder=='plats thailandais') //display words only by hiding the image.
				{
					//display=none;
					$colNum = 1;
					$isDisplay = 'style="Display:none;"';
					$isNextLine = ' - ';
					$isLeft = " align='left' ";
				}else if($folder=='1-10 Menu' || $folder == '2-23 Menu plateau')
					{
						$colNum = 1;
						$itemImgClass = 'sixXtwo';
					}else if($folder=='2-22 Menu bateaux')
						{
							$colNum = 1;
							$itemImgClass = 'sixXthree';
						}
			
			//a special rule of menu thailandais(fuck jardinbambou!)
			if($name == 'menu thailandais')
			{
				$isLeft = " colspan ='3' ";
				$itemImgClass = 'sixXthree';
			}
						
			$commenPart = "<td".$isLeft." class='item'>
								<a href='../"  . $file . "' class='highslide' onclick='return hs.expand(this)'>
									<img class='".$itemImgClass."' ".$isDisplay." src='../"  . $file . "'>
								</a><br>
								<label>
									<input name='item' type='checkbox' value='".$name."' price='".$price."' path='../"  . $file . "' onclick='CheckItem(this)'/> ".$name. $isNextLine.
									"<div>".$price." &euro;</div>
								</label>
								<div class='highslide-caption'>".$name."</div>
							</td>";
							
			/*$commenPart = "<td".$isLeft." class='item'>
								<a href='../"  . $file . "' data-lightbox='".$name."' data-title='".$name."'>
									<img class='".$itemImgClass."' ".$isDisplay." src='../"  . $file . "'>
								</a><br>
								<label>
									<input name='item' type='checkbox' value='".$name."' price='".$price."' path='../"  . $file . "' onclick='CheckItem(this)'/> ".$name. $isNextLine.
									"<div>".$price." &euro;</div>
								</label>
							</td>";*/
			
			//decide how many elements in one row.
			if($num==0)
			{
				$html = $html."<tr>".$commenPart;	
			}else if($num == $colNum-1){
				$html = $html.$commenPart."</tr>";	
				}
				else{
					$html = $html.$commenPart;	
					}
			$num = ($num+1)%$colNum;
		}
	$data = array('html' => $html);	//IE browser doesn't support.
	return $app->json($data);
});


$app->get('/valider', function(Application $app, Request $req) use ($app) {
	require_once('emailSender/gmailSender.php');
	
	//get the infos from request
    $command = $req->query->get('command');
    $emailClient = $req->query->get('emailClient');
	
	//Configurations
	$emailServer = 'atelier19.infos@gmail.com';//'bruce.gong.france@gmail.com';
	$pwdServer = '2014atelier19';
	$emailResto = 'jardinbambou.info@gmail.com';//'bruce.gong.tmax@gmail.com';
	$pwdResto = '20140702atelier19';
	$nicknameResto = 'Jardin Bambou Resto';
	$endWORD = '<br>Bien cordialement,<br>Atelier 19 <br>106 ave République, 75011 Paris';
	
	//send first email from Server to Resto
	$Account = $emailServer;
	$Pwd = $pwdServer;
	$From = array($Account => 'Atelier 19');
	$ToList = array($emailResto => $nicknameResto);
	$Subjet = 'Nouvelle commande';
	$Content = 'Bonjour,<br><br>Vous avez reçu une nouvelle commande:<br><br>'.$command.$endWORD;
	$result1 = sendGmail($app, $Account, $Pwd, $From, $ToList, $Subjet, $Content);
	
	//send second email from Resto to Client
	$Account = $emailResto;
	$Pwd = $pwdResto;
	$From = array($Account => $nicknameResto);
	$ToList = array($emailClient);
	$Subjet = 'Confirmation de commande - No reply';
	$Content = 'Bonjour,<br><br>Vous avez commandé en ligne:<br><br>'.$command.$endWORD;
	$result2 = sendGmail($app, $Account, $Pwd, $From, $ToList, $Subjet, $Content);
		
	$data = array('result1' => $result1,
					'result2' => $result2);
	return $app->json($data);
});


$app->post('/avis', function(Request $request) use ($app) {
	require_once('emailSender/gmailSender.php');
	//get all the infos of message
    $clientName = $request->get('clientName');
    $clientEmail = $request->get('email');
    $clientPhone = $request->get('phone');
    $clientMessage = $request->get('message');
	
	//configuration of email accounts
	$emailServer = 'atelier19.infos@gmail.com';//'bruce.gong.france@gmail.com';
	$pwdServer = '2014atelier19';
	$emailResto = 'jardinbambou.info@gmail.com';//'bruce.gong.tmax@gmail.com';
	$pwdResto = '20140702atelier19';
	$nicknameResto = 'Jardin Bambou Resto';
	$endWORD = '<br><br>Bien cordialement,<br>Atelier 19 <br>106 ave République, 75011 Paris';
	
	
	//send email from Server to Resto
	$Account = $emailServer;
	$Pwd = $pwdServer;
	$From = array($Account => 'Atelier 19');
	$ToList = array($emailResto => $nicknameResto);
	$Subjet = 'Nouveau MESSAGE';
	$Content = 'Bonjour,<br><br>Vous avez reçu une nouvelle MESSAGE:<br><br>'
				."Nom : $clientName<br>"
				."Email : $clientEmail<br>"
				."Phone : $clientPhone<br>"
				."Message : $clientMessage".$endWORD;
    $result = sendGmail($app, $Account, $Pwd, $From, $ToList, $Subjet, $Content);
	
	if($result === 1)
	{
		return $app->redirect('/View/contact.html');	//works well on the server, due to the server recognize the /web as the root.
		//return $app->redirect('/jardinbambou/web/View/contact.html');   //works well on localhost.
		}else{
			return new Response('We failed to send the message to Restaurant. Please try again later...', 201);
			}
});

$app->error(function (\Exception $e, $code) use ($app) {
	if($app['debug']){
		return;
		}
    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }

    return new Response($message);
});

$app->run();
?>