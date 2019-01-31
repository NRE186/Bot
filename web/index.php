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
      //Расписание
      $monday = "
        Расписание на понедельник 
        1,2 пара :  Физика А314
        3,4 пара :  Алгебра и геометрия А304 ";
      $tuesday = "
        Расписание на вторник 
        1 пара :  История А436
        2 пара :  Безопастность жизнедеятельности А410
        3 пара :  Математический анализ А410 
        4 пара :  Основы алгоритмизации и языки программирования У503";
      $wednesday = "
        Расписание на среду 
        1 пара :  Физика А316,317 / Основы алгоритмизации и языки программирования У607
        2 пара :  Основы алгоритмизации и языки программирования У607 / Физика А316,317
        3 пара :  Физкультура С";
      $thursday = "
        Расписание на четверг 
        1 пара :  - / Информатика У601 / -
        2 пара :  Иностранный У507  / - / Информатика У601 
        3 пара :  Информатика У704
        4 пара :  Информатика У601 / - / Иностранный У508";
      $friday = "
        Расписание на пятницу 
        2 пара : История А410 Кузнецова А.А
        3 пара :  Безопастность жизнедеятельности А436 
        5 пара :  Элективные дисциплины по ф/к С";
      $saturday = "
        Расписание на субботу 
        1,2 пара :  Программирование на языках высокого уровня У506,У607
        3 пара :  Физическая культура А436 
        4 пара :  Математический анализ У504";
      
      $string = strtolower($data->object->text);

      if($string == "расписание")
      {
        $message = $thursday;
      }
      else if($string == "звонки"){

      }
      else{
        $message = "Неизвестная команда! Список доступных команд : 
        Расписание - расписание на текущий день
        Расписание на всю неделю 
        Расписание на {день} - расписание на определенный день
        Звонки - расписание звонков";
      }
      
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
