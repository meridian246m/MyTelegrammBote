<?php
    class MyBot 
    {
        private     $botName =          '@md246bot';
        protected   $botToken =       '1238564789:AAF1kydnaZ_ZWXlBrCXVyKC5RVeOLynCMvg';
        //method sendMessage
    public function  GetBotName(){
        return $this->botName;
    }
    public function  GetBotToken(){
        return $this->botToken;
    }
    public function  SetBotName($bName){
        $this->botName = $bName;
    }
    public function  SetBotToken($bToken){
        $this->botName = $bToken;
    }
    protected function query($method,$params=[])
    {
       $url = 'https://api.telegram.org/bot';
       $url.= $this->botToken.'/'.$method;
    }

    public function GetUpdates()
    {
        $data = $data['callback_query'] ? $data['callback_query'] : $data['message'];
        $message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');
    }

    public function SendMessage($method, $data, $headers = []){
        $TOKEN = $this->botToken;
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.telegram.org/bot' . $TOKEN . '/' . $method,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array_merge(array("Content-Type: application/json"))
        ]);
        $result = curl_exec($curl);
        curl_close($curl);
        return (json_decode($result, 1) ? json_decode($result, 1) : $result);
    }

    function sendTelegram($method, $data, $headers = [])
    {
        $TOKEN = $this->botToken;
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.telegram.org/bot' . $TOKEN . '/' . $method,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array_merge(array("Content-Type: application/json"))
        ]);
        $result = curl_exec($curl);
        curl_close($curl);
        return (json_decode($result, 1) ? json_decode($result, 1) : $result);
    }
    }
?>


//@md246bot
//1238564789:AAF1kydnaZ_ZWXlBrCXVyKC5RVeOLynCMvg
//https://api.telegram.org/bot+ТокенБота+/setwebhook?url=+СсылкаНаБота
//Связать
//https://api.telegram.org/bot123asd123asd123asd/setwebhook?url=https://test123123.ru/telegram_bot.php
//https://api.telegram.org/bot1238564789:AAF1kydnaZ_ZWXlBrCXVyKC5RVeOLynCMvg/setwebhook?url=https://telebot.tesovii.space/telebot.php

//Отвязать
//https://api.telegram.org/bot+ТокенБота+/deleteWebhook
//https://api.telegram.org/bot1238564789:AAF1kydnaZ_ZWXlBrCXVyKC5RVeOLynCMvg/deleteWebhook

<?php
//$data = json_decode(file_get_contents('php://input'), TRUE);
//пишем в файл лог сообщений
//file_put_contents('file.txt', '$data: '.print_r($data, 1)."\n", FILE_APPEND);

$data = $data['callback_query'] ? $data['callback_query'] : $data['message'];

define('TOKEN', '1238564789:AAF1kydnaZ_ZWXlBrCXVyKC5RVeOLynCMvg');

$message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');


switch ($message) {
    case 'да':
        $method = 'sendMessage';
		$send_data = [
                        'text' => 'Что вы хотите заказать?',
                        'reply_markup'  => [
                                                'resize_keyboard' => true,
                                                'keyboard' =>   [
                                                                    [
                                                                        ['text' => 'Яблоки'],
                                                                        ['text' => 'Груши'],
                                                                    ],
                                                                    [
                                                                        ['text' => 'Лук'],
                                                                        ['text' => 'Чеснок'],
                                                                    ]
                                                                ]
                                            ]
        			];
    break;
	case 'нет':
        $method = 'sendMessage';
		$send_data = ['text' => 'Приходите еще'];
    break;
	case 'яблоки':
        $method = 'sendMessage';
		$send_data = ['text' => 'заказ принят!'];
    break;
	case 'груши':
        $method = 'sendMessage';
		$send_data = ['text' => 'заказ принят!'];
    break;
	case 'лук':
        $method = 'sendMessage';
		$send_data = ['text' => 'заказ принят!'];
    break;
	case 'чеснок':
        $method = 'sendMessage';
		$send_data = ['text' => 'заказ принят!'];
    break;
	default:
		$method = 'sendMessage';
		$send_data = [
			'text' => 'Вы хотите сделать заказ?',
			'reply_markup'  => [
				'resize_keyboard' => true,
				'keyboard' => [
						[
							['text' => 'Да'],
							['text' => 'Нет'],
						]
					]
				]
			];
}

$send_data['chat_id'] = $data['chat'] ['id'];

$res = sendTelegram($method, $send_data);




function sendTelegram($method, $data, $headers = [])
{
	$curl = curl_init();
	curl_setopt_array($curl, [
		CURLOPT_POST => 1,
		CURLOPT_HEADER => 0,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => 'https://api.telegram.org/bot' . TOKEN . '/' . $method,
		CURLOPT_POSTFIELDS => json_encode($data),
		CURLOPT_HTTPHEADER => array_merge(array("Content-Type: application/json"))
	]);
	$result = curl_exec($curl);
	curl_close($curl);
	return (json_decode($result, 1) ? json_decode($result, 1) : $result);
}





<?php
//include "MyBot.php";

//$data = $data['callback_query'] ? $data['callback_query'] : $data['message'];
//$message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');

//$WorkBot = New MyBot;
//$send_data = $WorkBot->SwitchCaseBot($message);
//$send_data = 'lf';
//$send_data['chat_id'] = $data['chat'] ['id'];
//$res = $WorkBot->SendMessage('sendMessage', $send_data);

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

$data = $data['callback_query'] ? $data['callback_query'] : $data['message'];

define('TOKEN', '1238564789:AAF1kydnaZ_ZWXlBrCXVyKC5RVeOLynCMvg');

$message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');


switch ($message) {
    case 'да':
        $method = 'sendMessage';
		$send_data = [
                        'text' => 'Что вы хотите заказать?',
                        'reply_markup'  => [
                                                'resize_keyboard' => true,
                                                'keyboard' =>   [
                                                                    [
                                                                        ['text' => 'Яблоки'],
                                                                        ['text' => 'Груши'],
                                                                    ],
                                                                    [
                                                                        ['text' => 'Лук'],
                                                                        ['text' => 'Чеснок'],
                                                                    ]
                                                                ]
                                            ]
        			];
    break;
	case 'нет':
        $method = 'sendMessage';
		$send_data = ['text' => 'Приходите еще'];
    break;
	case 'яблоки':
        $method = 'sendMessage';
		$send_data = ['text' => 'заказ принят!'];
    break;
	case 'груши':
        $method = 'sendMessage';
		$send_data = ['text' => 'заказ принят!'];
    break;
	case 'лук':
        $method = 'sendMessage';
		$send_data = ['text' => 'заказ принят!'];
    break;
	case 'чеснок':
        $method = 'sendMessage';
		$send_data = ['text' => 'заказ принят!'];
    break;
	default:
		$method = 'sendMessage';
		$send_data = [
			'text' => 'Вы хотите сделать заказ?',
			'reply_markup'  => [
				'resize_keyboard' => true,
				'keyboard' => [
						[
							['text' => 'Да'],
							['text' => 'Нет'],
						]
					]
				]
			];
}

$send_data['chat_id'] = $data['chat'] ['id'];

$res = sendTelegram($method, $send_data);




function sendTelegram($method, $data, $headers = [])
{
	$curl = curl_init();
	curl_setopt_array($curl, [
		CURLOPT_POST => 1,
		CURLOPT_HEADER => 0,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => 'https://api.telegram.org/bot' . TOKEN . '/' . $method,
		CURLOPT_POSTFIELDS => json_encode($data),
		CURLOPT_HTTPHEADER => array_merge(array("Content-Type: application/json"))
	]);
	$result = curl_exec($curl);
	curl_close($curl);
	return (json_decode($result, 1) ? json_decode($result, 1) : $result);
}