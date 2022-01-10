<?php
include 'MyBot.php';
$data = json_decode(file_get_contents('php://input'), TRUE);
$data = $data['callback_query'] ? $data['callback_query'] : $data['message'];
$message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');

$DB=new DataBase;
$result = $DB->TestChatId($data['chat'] ['id']) ? $send_data = ['text'=>'true'] : $Bot->CreateUser($data['chat'] ['id']);
$method = 'sendMessage';
/*
switch ($message) 
    {
        case 'нет':
            $method = 'sendMessage';
            $send_data = ['text' => 'Приходите еще!'];
            break;
        case 'да':
            $method = 'sendMessage';			
			$send_data = ['text' => 'Ваше имя?'];
            break;
        default: //---------------------------------------------------//
        $method = 'sendMessage';
        $send_data = 
        [
            'text'=> 'Для использования бота необходимо ответить на несколько вопросов. Ваши данные не будут использованы для рекламной рассылки организаторами конференции и её участниками. Бот обеспечивает анонимность и использовать ваши контакты в корыстных целях не получится. Если вы согластны, нажмите ДА.',
            'reply_markup'=>
            [
                'resize_keyboard' => true, 
                'keyboard' =>   
                [
                    [
                        ['text' => 'Да'],
                        ['text' => 'Нет'],
                    ]
                ]
            ]
        ];
    }        
*/
$send_data['chat_id'] = $data['chat'] ['id'];
$Bot = new MyBot;
$res = $Bot->sendMessage($method, $send_data, $Bot->botToken);
?>