<?php

header('Content-type: text/html; charset=utf-8');
mb_internal_encoding("UTF-8");
include 'BotClass.php';
$data = json_decode(file_get_contents('php://input'), TRUE); 
    $bot = new TeleBot();
    $chat_id = $data['message']['chat']['id'];
    $Status_reg = $bot->Registration($data);
    if($Status_reg=='close')
            {
                $bot->WorkIngBot($data);
            }



            /*  https://ru.stackoverflow.com/q/557656 */

            /* Теперь понятно, как работает callback-data */
/*
header('Content-type: text/html; charset=utf-8');
mb_internal_encoding("UTF-8");
include 'BotClass.php';
$bot = new TeleBot();


$access_token = '1238564789:AAF1kydnaZ_ZWXlBrCXVyKC5RVeOLynCMvg';
$api = 'https://api.telegram.org/bot' . $access_token;
$output = json_decode(file_get_contents('php://input'), TRUE);
$chat_id = $output['message']['chat']['id'];
$message = $output['message']['text'];
$callback_query = $output['callback_query'];
$data = $callback_query['data'];
$message_id = ['callback_query']['message']['message_id'];
$chat_id_in = $callback_query['message']['chat']['id'];
switch($message) {
    case '/test':  
    $inline_button1 = array("text"=>"Google url","url"=>"http://google.com");
    $inline_button2 = array("text"=>"work plz","callback_data"=>'/plz');
    $inline_keyboard = [[$inline_button1,$inline_button2]];
    $keyboard=array("inline_keyboard"=>$inline_keyboard);
    $replyMarkup = json_encode($keyboard); 
     sendMessage($chat_id, "ok", $replyMarkup);
    break;
}
switch($data){
    case '/plz':
    sendMessage($chat_id_in, "plz",$replyMarkup);
    break;
}
function sendMessage($chat_id, $message, $replyMarkup) {
  file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message) . '&reply_markup=' . $replyMarkup);
}

*/