<?php
header('Content-type: text/html; charset=utf-8');
mb_internal_encoding("UTF-8");
include 'BotClass.php';

$data = json_decode(file_get_contents('php://input'), TRUE); 
$bot = new TeleBot();
$chat_id = $data['message']['chat']['id'];
$Status_reg = $bot->Registration($data);
$bot->sendMessage($chat_id,$Status_reg);
if($Status_reg=='close')
{
    $bot->sendMessage($chat_id,$Status_reg);
    $bot->WorkIngBot($data);
}


//$chat_id = $data['message']['chat']['id'];
//$send_data = ['text'=>'<Ожидайте бот работает в режиме отладки Тест 30>'];           
