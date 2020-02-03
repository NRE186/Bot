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
      '2' => '2 пара :  Физкультура <br>',
      '3' => '4 пара :  Математические модели в экономике У505 / Экономика и управление в ИС У505<br>',
      '4' => '5 пара :  Математические модели в экономике У601 / Экономика и управление в ИС У601'
    );
    $tuesday = array(
      '1' => '1 пара :  Вероятность и статистика У504 <br> Статистические методы и модели управления У503 <br> <br>',
      '2' => '2 пара :  Вероятность и статистика У504 <br> Статистические методы и модели управления У601 <br>',
      '3' => '3 пара :  Основы Web-инжиниринга У504 <br>',
      '4' => '4 пара :  Основы Web-инжиниринга У606 / '
    );
    $wednesday = array(
      '1' => '1 пара :  Информационные технологии У704 <br>',
      '2' => '2 пара :  Иностранный язык У507 / Информационные технологии У607 <br>',
      '3' => '3 пара :  Информационные технологии У607 / Иностранный язык У508',
      '4' => '4 пара :  Основы Web-инжиниринга У606 / '
    );
    $thursday = array(
      '2' => '2 пара :  Физкультура <br>',
      '3' => '4 пара :  Вычислительная математика У504 <br>',
      '5' => '5 пара :  Вычислительная математика У504'
    );
    $friday = array(
      '1' => '2 пара :  Дифференциальные уравнения У708 <br>',
      '2' => '3 пара :  Дифференциальные уравнения У708 <br>',
      '3' => '4 пара :  Русский язык и культура речи А404 <br>',
      '4' => '5 пара :  Русский язык и культура речи А404 / '
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
    Звонки - расписание звонков
    Сайт";

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
          $message ='Суббота <br> День самостоятельной работы';
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
        $message ='Суббота <br> День самостоятельной работы';
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
      else if($string == "сайт"){
        $message = "https://nremusic.ru";
      }
      else{
        $message = "Неизвестная команда! Список доступных команд : 
        Расписание - расписание на текущий день,
        Расписание на {день} - расписание на определенный день,
        Звонки - расписание звонков,
        Список команд, 
        Время,
        Сайт";
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
  }
  return "ok";
});

$app->run();
