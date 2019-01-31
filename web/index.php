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

      $thursday = array(
        1 => ' - / Информатика У601 Назина Н.Б / -',
        2 => ' Иностранный У507 Чеснокова Н.Е / - / Информатика У601 Назина Н.Б',
        3 => ' Информатика У704 Назина Н.Б',
        4 => ' Информатика У601 Назина Н.Б / - / Иностранный У508 Кузнецова С.В'
      );

      $message = 
      "1 пара - " . $thursday[1] . 
      "2 пара - " . $thursday[2] . 
      "3 пара - " . $thursday[3] . 
      "4 пара - " . $thursday[4];

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
