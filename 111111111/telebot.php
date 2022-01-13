<?php
header('Content-type: text/html; charset=utf-8');
mb_internal_encoding("UTF-8");
include 'MyBot.php';

$data = json_decode(file_get_contents('php://input'), TRUE);
$photo_data = $data;
$data = $data['callback_query'] ? $data['callback_query'] : $data['message'];
$message = mb_convert_encoding(($data['text'] ? $data['text'] : $data['data']), "UTF-8");
$DB     = new DataBase;
$NewBot = new MyBot;
$method = 'sendMessage';
$chat_id = $data['chat'] ['id'];


$send_data = ['text'=>'Ожидайте бот работает в режиме отладки Шаг№6'];
$send_data['chat_id'] = $data['chat'] ['id'];
$Bot = new MyBot;
$res = $Bot->sendMessage($method, $send_data, $Bot->botToken);

$send_data =['text'=>json_encode($data)];
$send_data['chat_id'] = $chat_id;
$res = $Bot->sendMessage($method, $send_data, $Bot->botToken);       


$send_data =['text'=>$photo_data['message']['from']['id']];
$send_data['chat_id'] = $chat_id;
$res = $Bot->sendMessage($method, $send_data, $Bot->botToken);       




?>


