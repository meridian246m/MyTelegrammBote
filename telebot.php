<?php
include 'MyBot.php';
$data = json_decode(file_get_contents('php://input'), TRUE);
$data = $data['callback_query'] ? $data['callback_query'] : $data['message'];
$message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');

$DB     = new DataBase;
$MyBot  = new MyBot;
$method = 'sendMessage';

$result = $DB->TestChatId($data['chat'] ['id']) ? $result = true : $result = $DB->CreateUser($data['chat'] ['id']);

if($result==true)
{
    $send_data = $MyBot->SwitchCaseStep_Name($message);
}

$send_data['chat_id'] = $data['chat'] ['id'];
$res = $Bot->sendMessage($method, $send_data, $Bot->botToken);



?>