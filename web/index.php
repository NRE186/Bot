<?php

require('../vendor/autoload.php');

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('index.twig');
});


$app->post('/bot', function() use($app) {
  $data = json_decode(file_get_contents('php://input'));
  
  if(!data) 
    return "bad";

  if($data->secret !== getenv('VK_SECRET') && $data->type !== 'comfirmation' )
    return "bad";

  switch($data->type)
  {
    case 'comfirmation': 
      return getenv('VK_SECRET');
      break;
    case 'message_new': 

      break;
  }


  return "bad";
});

$app->run();
