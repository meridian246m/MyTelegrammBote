<?php
include 'MyBot.php';
$data = json_decode(file_get_contents('php://input'), TRUE);
$data = $data['callback_query'] ? $data['callback_query'] : $data['message'];
$message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');
$DB     = new DataBase;
$NewBot = new MyBot;
$method = 'sendMessage';
$chat_id = $data['chat'] ['id'];
$first_start=false;
/////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////
if($DB->TestChatId($data['chat'] ['id'])==false)    // Если записи Чата нет, значит создаем пользователя принудительно
    {
        $User = $DB->CreateUser($data['chat'] ['id']); $first_start=true;
    }

    $Status  = $NewBot->TestRegisterUserData($chat_id); //Узнаем какие поля не заполнены on - значит все заполнили

    if($first_start==false) //Если это первый запуск то пока ничего не пишем в базу
    {
        if($message<>'/start' AND $message<>'<да, согласен!>' AND $message<>'<нет, мне не интересно!>')
        {
            $Status  = $NewBot->RegisterInfoStore($Status,$chat_id,$message);
        }    
    }    

    if($Status<>'on')
    {
        //Проходим необходимые или не достающие этапы регистрации
        switch($message)
        {
            case '/start': $send_data = $NewBot->Request_Start(); break;
            case '<нет, мне не интересно!>': $send_data = $DB->DeleteChat($chat_id); break;
            default : $send_data = $NewBot->RegisterTextShow($Status,$message); break;
        }
    } else
    {
        //Если регистрационная информация полная, значит тут уже работаем.
        $send_data = $NewBot->UserPanel();
    }
/////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////
$send_data['chat_id'] = $data['chat'] ['id'];
$Bot = new MyBot;
$res = $Bot->sendMessage($method, $send_data, $Bot->botToken);
?>


