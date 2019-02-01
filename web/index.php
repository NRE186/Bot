<?php

date_default_timezone_set('Asia/Yekaterinburg');

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
    
    $timetable = "
    Расписание звонков 
    1 пара : 08.00 - 09.30 
    2 пара : 09.40 - 11.10
    3 пара : 11.20 - 12.50
    4 пара : 13.20 - 14.50
    5 пара : 15.00 - 16.30
    6 пара : 16.40 - 18.10
    7 пара : 18.20 - 19.50
    8 пара : 20.00 - 21.30

    Расписание звонков в Дружбе
    1 пара : 08.30 - 10.00 
    2 пара : 10.10 - 11.40
    3 пара : 11.50 - 13.20
    4 пара : 13.30 - 15.00
    5 пара : 15.05 - 16.35";

    $commands = "
    Список доступных команд : 
    Расписание - расписание на текущий день
    Расписание на всю неделю 
    Расписание на {день} - расписание на определенный день
    Звонки - расписание звонков";

      $string = mb_strtolower($data->object->text);

      if($string == "расписание")
      {
        if(date("w") == 1){
          $message = $monday;
        }
        else if(date("w") == 2){
          $message = $tuesday;
        }
        else if(date("w") == 3){
          $message = $wednesday;
        }
        else if(date("w") == 4){
          $message = $thursday;
        }
        else if(date("w") == 5){
          $message = $friday;
        }
        else if(date("w") ==6){
          $message = $saturday;
        }
      }
      else if(strpos($string, "понедельник") !== false){
        $message = $monday;
      }
      else if(strpos($string, "вторник") !== false){
        $message = $tuesday;
      }
      else if(strpos($string, "среду") !== false){
        $message = $wednesday;
      }
      else if(strpos($string, "четверг") !== false){
        $message = $thursday;
      }
      else if(strpos($string, "пятницу") !== false){
        $message = $friday;
      }
      else if(strpos($string, "субботу") !== false){
        $message = $saturday;
      }
      else if($string == "звонки"){
        $message = $timetable;
      }
      else if($string == "список команд"){
        $message = $commands;
      }
      else{
        $message = "Неизвестная команда! Список доступных команд : 
        Расписание - расписание на текущий день,
        Расписание на {день} - расписание на определенный день,
        Звонки - расписание звонков,,
        Список команд ";
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
