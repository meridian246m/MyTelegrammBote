<?php
include "MyBot.php";

$data = $data['callback_query'] ? $data['callback_query'] : $data['message'];
$message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');




/*
 if (array_key_exists('message', $data))// проверяем если пришло сообщение
{
    if ($data['message']['text'] == "/start")    //если пришла команда /start
    {
        $this->sendMessage($chat_id, "Приветствую! Загрузите картинку.");
    } 
    elseif 
    
    (array_key_exists('photo', $data['message'])) 
    {
        // если пришла картинка то сохраняем ее у себя
        $text = $this->getPhoto($data['message']['photo'])
            ? "Спасибо! Можете еще загрузить мне понравилось их сохранять."
            : "Что-то пошло не так, попробуйте еще раз";
        // отправляем сообщение о результате   
        $this->sendMessage($chat_id, $text);

    } else 
    {
        // если пришло что-то другое
        $this->sendMessage($chat_id, "Не понимаю команду! Просто загрузите картинку.");
    }
}
*/