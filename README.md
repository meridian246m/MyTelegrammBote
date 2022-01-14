# MyTelegrammBote
https://api.telegram.org/bot1238564789:AAF1kydnaZ_ZWXlBrCXVyKC5RVeOLynCMvg/setwebhook?url=https://telebot.tesovii.space/telebot.php

https://api.telegram.org/bot5026319207:AAE77UW_jQZQLeP2GywHKegwkZgvAj1_UgU/setwebhook?url=https://telebot.tesovii.space/devbot/devbot.php
/*
    1.Сообщения пользователю
    2.Упоминание пользователя tg://user?id=123456789
    Упоминание пользователя — текст, похожий на ссылку, клик по которому открывает профиль пользователя. 
    Если упомянуть в группе её участника, он получит уведомление.
    Чтобы вставить в сообщение упоминание пользователя, в Bot API нужно встроить ссылку на tg://user?id=123456789.

    Кнопки под сообщениями (они же inline keyboards / inline buttons) в основном бывают трёх видов:

    URL button — кнопка с ссылкой.
    
    Callback button. При нажатии на такую кнопку боту придёт апдейт. С созданием кнопки можно указать параметр, который будет указан в этом апдейте (до 64 байтов). Обычно после нажатий на такие кнопки боты изменяют исходное сообщение или показывают notification или alert.
    
    Switch to inline button. Кнопка для переключения в инлайн-режим (об инлайн-режиме см. ниже). Кнопка может открывать инлайн в том же чате или открывать меню для выбора чата. Можно указать в кнопке запрос, который появится рядом с никнеймом бота при нажатии на кнопку.
*/




Телеграмм Бот
    'reply_markup'=>
    [
        'resize_keyboard' => true, 
        'keyboard' =>   
        [
            [
                ['text' => '<Связаться>'],
                ['text' => '<Следующий>']
            ],
            [
                ['text' => '<Выйти>'],
            ]

        ]
    ],
    'reply_markup'=>['remove_keyboard'=>true]


{
  "message_id": 16621,
  "from": {
    "id": 987795322,
    "is_bot": false,
    "first_name": "Денис",
    "last_name": "Серов",
    "username": "meridian246",
    "language_code": "ru"
  },
  "chat": {
    "id": 987795322,
    "first_name": "Денис",
    "last_name": "Серов",
    "username": "meridian246",
    "type": "private"
  },
  "date": 1642068171,
  "text": "фвыфывафыв"
}

{
  "message_id": 16626,
  "from": {
    "id": 987795322,
    "is_bot": false,
    "first_name": "Денис",
    "last_name": "Серов",
    "username": "meridian246",
    "language_code": "ru"
  },
  "chat": {
    "id": 987795322,
    "first_name": "Денис",
    "last_name": "Серов",
    "username": "meridian246",
    "type": "private"
  },
  "date": 1642077549,
  "photo": [
    {
      "file_id": "AgACAgIAAxkBAAJA8mHgHW2iB-jGfrIm58FQgmDCtfDxAALAuDEbi9v5SkyWwc5aBx28AQADAgADcwADIwQ",
      "file_unique_id": "AQADwLgxG4vb-Up4",
      "file_size": 1622,
      "width": 90,
      "height": 50
    },
    {
      "file_id": "AgACAgIAAxkBAAJA8mHgHW2iB-jGfrIm58FQgmDCtfDxAALAuDEbi9v5SkyWwc5aBx28AQADAgADbQADIwQ",
      "file_unique_id": "AQADwLgxG4vb-Upy",
      "file_size": 26628,
      "width": 320,
      "height": 177
    },
    {
      "file_id": "AgACAgIAAxkBAAJA8mHgHW2iB-jGfrIm58FQgmDCtfDxAALAuDEbi9v5SkyWwc5aBx28AQADAgADeAADIwQ",
      "file_unique_id": "AQADwLgxG4vb-Up9",
      "file_size": 49700,
      "width": 470,
      "height": 260
    }
  ]
}


{
  "update_id": 246045329,
  "message": {
    "message_id": 17241,
    "from": {
      "id": 987795322,
      "is_bot": false,
      "first_name": "Денис",
      "last_name": "Серов",
      "username": "meridian246",
      "language_code": "ru"
    },
    "chat": {
      "id": 987795322,
      "first_name": "Денис",
      "last_name": "Серов",
      "username": "meridian246",
      "type": "private"
    },
    "date": 1642101949,
    "text": "<Нет, мне не интересно!>"
  }
}


//$send_data = ['text'=>'<Ожидайте бот работает в режиме отладки Тест 17 if>'];           
//$send_data['chat_id'] = $chat_id; $this->sendMessageEnd($send_data);  ///SEND Message test
/*
protected function InlineKeyBoard()
{
    $keyboard = array(
        array(
        array('text'=>':like:','callback_data'=>'{"action":"like","count":0,"text":":like:"}'),
        array('text'=>':joy:','callback_data'=>'{"action":"joy","count":0,"text":":joy:"}'),
        array('text'=>':hushed:','callback_data'=>'{"action":"hushed","count":0,"text":":hushed:"}'),
        array('text'=>':cry:','callback_data'=>'{"action":"cry","count":0,"text":":cry:"}'),
        array('text'=>':rage:','callback_data'=>'{"action":"rage","count":0,"text":":rage:"}')
        )
    );
}    




   $result = json_decode(file_get_contents('php://input')); // получаем результат нажатия кнопки
   $inline_keyboard = $result->callback_query->message->reply_markup->inline_keyboard; // текущее состояние кнопок при нажатии на одну из 5 кнопок
   $data = json_decode($result->callback_query->data, true); // получаем значение с кнопки, а именно с параметра callback_data нажатой кнопки
   $message_id = $result->callback_query->message->message_id; // ID сообщения в чате
   $callback_query_id = $result->callback_query->id; //ID полученного результата
   $user_id = $result->callback_query->from->id; // ID пользователя

   $db_message = $db->super_query("SELECT * FROM bot_like WHERE message_id={$message_id}"); //Ищем в БД ID сообщения



*/


   /*     public  function CallInlineBooton($data)
                    {
                        ///////////////////////
                        $callback_query = $data['callback_query'];
                        //$call = $callback_query['data'];
                        $chat_idd = $callback_query['message']['chat']['id'];
                        $this->sendMessage($chat_idd, $chat_idd);
                        $call = $callback_query['data'];
                        switch($call){
                            case '/test':
                            $this->sendMessage($chat_idd, "test");
                            break;
                            default:
                            $data=$data;
                        }                      
                        ///////////////////////       
                        return $data;         
                    }*/

                    //md246Devbot
//5026319207:AAE77UW_jQZQLeP2GywHKegwkZgvAj1_UgU
//t.me/md246Devbot
