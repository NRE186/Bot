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
    return;

  switch( $data->type )
  {
    case 'confirmation':
      return getenv('VK_CONFIRM');
      break;
    case 'message_new':
      $message = 'Ошибка';

      $thursday = "
        1 пара -  - / Информатика У601 / -
        2 пара -  Иностранный У507  / - / Информатика У601 
        3 пара -  Информатика У704
        4 пара -  Информатика У601 / - / Иностранный У508
      ";

      $message = $thursday;

      $request_params = array(
        'random_id' => rand(0, 100000000000000000),
        'peer_id'    => $data->object->from_id,
        'message'    => $message,
        'access_token' => getenv('VK_TOKEN'),
        'v' => '5.92'
      );
      
      file_get_contents('https://api.vk.com/method/messages.send?' . http_build_query($request_params));
      return 'ok';
      break;
  }
});

$app->run();
