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
  return mb_convert_encoding('ok', "ASCII");
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
    $monday = array(
      '2' => '2 пара :  Физика А314(числитель) <br>',
      '3' => '3 пара : Физика А314 <br>',
      '4' => '4 пара :  Алгебра и геометрия А304 <br>',
      '5' => '5 пара :  Алгебра и геометрия А304'
    );
    $tuesday = array(
      '1' => '1 пара :  История А436 <br>',
      '2' => '2 пара :  Безопастность жизнедеятельности А410 <br>',
      '3' => '3 пара :  Математический анализ А410 <br>',
      '4' => '4 пара :  Основы алгоритмизации и языки программирования У503'
    );
    $wednesday = array(
      '1' => '1 пара :  Физика А316,317 / Основы алгоритмизации и языки программирования У607 <br>',
      '2' => '2 пара :  Основы алгоритмизации и языки программирования У607 / Физика А316,317 <br>',
      '3' => '3 пара :  Физкультура С'
    );
    $thursday = array(
      '1' => '1 пара :  - / Информатика У601 / - <br>',
      '2' => '2 пара :  Иностранный У507  / - / Информатика У601 <br>',
      '3' => '3 пара :  Информатика У704 <br>',
      '4' => '4 пара :  Информатика У601 / - / Иностранный У508 '
    );
    $friday = array(
      '2' => '2 пара : История А410 Кузнецова А.А <br>',
      '3' => '3 пара :  Безопастность жизнедеятельности А436 <br>',
      '5' => '5 пара :  Элективные дисциплины по ф/к С'
    );
    $saturday = array(
      '1' => '1 пара :  Программирование на языках высокого уровня У506 <br>',
      '2' => '2 пара :  Программирование на языках высокого уровня У607 <br>',
      '3' => '3 пара :  Физическая культура А436  <br>',
      '4' => '4 пара :  Математический анализ У504'
    );
    
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
        if(date("w") == 1 || date("w") == 0){
          $message = 'Понедельник <br>' . $monday[2] . $monday[3] . $monday[4] . $monday[5];
        }
        else if(date("w") == 2){
          $message ='Вторник <br>' . $tuesday[1] . $tuesday[2] . $tuesday[3] . $tuesday[4];
        }
        else if(date("w") == 3){
          $message ='Среда <br>' . $wednesday[1] . $wednesday[2] . $wednesday[3];
        }
        else if(date("w") == 4){
          $message ='Четверг <br>' . $thursday[1] . $thursday[2] . $thursday[3] . $thursday[4];
        }
        else if(date("w") == 5){
          $message ='Пятница <br>' . $friday[2] . $friday[3] . $friday[5];
        }
        else if(date("w") ==6){
          $message ='Суббота <br>' . $saturday[1] . $saturday[2] . $saturday[3] . $saturday[4];
        }
      }
      else if(strpos($string, "понедельник") !== false){
        $message = 'Понедельник <br>' . $monday[1] . $monday[2] . $monday[3] . $monday[4];
      }
      else if(strpos($string, "вторник") !== false){
        $message ='Вторник <br>' . $tuesday[1] . $tuesday[2] . $tuesday[3] . $tuesday[4];
      }
      else if(strpos($string, "среду") !== false){
        $message ='Среда <br>' . $wednesday[1] . $wednesday[2] . $wednesday[3];
      }
      else if(strpos($string, "четверг") !== false){
        $message ='Четверг <br>' . $thursday[1] . $thursday[2] . $thursday[3] . $thursday[4];
      }
      else if(strpos($string, "пятницу") !== false){
        $message ='Пятница <br>' . $friday[2] . $friday[3] . $friday[5];
      }
      else if(strpos($string, "субботу") !== false){
        $message ='Суббота <br>' . $saturday[1] . $saturday[2] . $saturday[3] . $saturday[4];
      }
      else if($string == "звонки"){
        $message = $timetable;
      }
      else if($string == "список команд"){
        $message = $commands;
      }
      else if($string == "время"){
        $message = date('H:i:s');
      }
      else{
        $message = "Неизвестная команда! Список доступных команд : 
        Расписание - расписание на текущий день,
        Расписание на {день} - расписание на определенный день,
        Звонки - расписание звонков,
        Список команд, 
        Время";
      }
      
      $request_params = array(
        'random_id' => rand(0, 100000000000000000),
        'peer_id'    => $data->object->from_id,
        'message'    => $message,
        'access_token' => getenv('VK_TOKEN'),
        'v' => '5.92'
      );
    
      file_get_contents('https://api.vk.com/method/messages.send?' . http_build_query($request_params));
      return "ok";
      http_response_code(200);
      break;

      case 'message_reply' : 

      $x = 0;
      if(strtotime(date('H:i')) == strtotime('20:34') && $x == 0){
        $request_params = array(
          'random_id' => rand(0, 100000000000000000),
          'peer_id'    => '104268893',
          'message'    => 'Тест',
          'access_token' => getenv('VK_TOKEN'),
          'v' => '5.92'
        );
      
        file_get_contents('https://api.vk.com/method/messages.send?' . http_build_query($request_params));
        $x = 1;
        return "ok";
        http_response_code(200);
      }
      break;
  }
  return "ok";
});

$app->run();
