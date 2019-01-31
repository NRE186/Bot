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
      //...получаем id его автора 
      $user_id = $data->object->from_id; 
      //затем с помощью users.get получаем данные об авторе 
      $user_info = json_decode(file_get_contents("https://api.vk.com/method/users.get?user_ids={$user_id}&access_token={$token}&v=5.0")); 
      
      //и извлекаем из ответа его имя 
      $user_name = $user_info->response[0]->first_name; 
      
      //С помощью messages.send отправляем ответное сообщение 
      $request_params = array( 
      'message' => "Hello, {$user_name}!", 
      'user_id' => $user_id, 
      'access_token' => $token, 
      'v' => '5.0' 
      ); 
      
      $get_params = http_build_query($request_params); 
      
      file_get_contents('https://api.vk.com/method/messages.send?'. $get_params); 
      
      //Возвращаем "ok" серверу Callback API 
      
      echo('ok'); 
      
      break; 
  }


  return "3";
});

$app->run();
