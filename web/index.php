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
  
  if( !data ) 
    return "1";

  if( $data->secret !== getenv('VK_SECRET') && $data->type !== 'comfirmation' )
    return "2";

  switch( $data->type )
  {
    case 'confirmation':
      return getenv('VK_CONFIRM');
      break;
    case 'message_new':
   
   
      break;
  }


  return "3";
});

$app->run();
