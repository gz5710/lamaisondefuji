<?php

require_once __DIR__.'/../../vendor/autoload.php';
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();
$app['debug'] = true;

$app->get('/hello', function() {
    return 'Hello!';
});

$app->post('/feedback', function (Request $request) {
    $message = $request->get('message');
    mail('gz5710@foxmail.com', '[YourSite] Feedback', $message);

    return new Response('Thank you for your feedback! '.$message, 201);
})
->bind('homepage');

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