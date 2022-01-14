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