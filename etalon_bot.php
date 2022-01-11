<?php
$data = json_decode(file_get_contents('php://input'), TRUE);
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
				'keyboard' => [
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

?>


<?php
include 'MyBot.php';
$data = json_decode(file_get_contents('php://input'), TRUE);
$data = $data['callback_query'] ? $data['callback_query'] : $data['message'];
$message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');

$DB=new DataBase;
$result = $DB->TestChatId($data['chat'] ['id']) ? $result = true : $result = $DB->CreateUser($data['chat'] ['id']);

$send_data = ['text'=>$result];
$method = 'sendMessage';

$send_data['chat_id'] = $data['chat'] ['id'];
$Bot = new MyBot;
$res = $Bot->sendMessage($method, $send_data, $Bot->botToken);
?>



case 'ch_Name_reg':
                    $this->UpdateUserData('Name',$chat_id,$message);
                    $this->UpdateUserData('Status',$chat_id,'ch_City_reg');
                    return 'ch_City_reg';
                    break;
                case 'ch_City_reg':
                    $this->UpdateUserData('City',$chat_id,$message);
                    $this->UpdateUserData('Status',$chat_id,'ch_Busines_reg');
                    return 'ch_Busines_reg';
                    break;    
                case 'ch_Busines_reg':
                        $this->UpdateUserData('Busines',$chat_id,$message);
                        $this->UpdateUserData('Status',$chat_id,'ch_AboutSelf_reg');
                    return 'ch_AboutSelf_reg';
                    break;        
                case 'ch_AboutSelf_reg':
                    $this->UpdateUserData('AboutSelf',$chat_id,$message);
                    $this->UpdateUserData('Status',$chat_id,'ch_WhoSearch_reg');
                    return 'ch_WhoSearch_reg';
                    break;
                case 'ch_WhoSearch_reg':
                    $this->UpdateUserData('WhoSearch',$chat_id,$message);
                    $this->UpdateUserData('Status',$chat_id,'ch_Img_reg');
                    return 'ch_Img_reg';
                    break;
                case 'ch_Img_reg':
                    $this->UpdateUserData('Img',$chat_id,$message);
                    $this->UpdateUserData('Status',$chat_id,'on');
                    return 'UserSingUp';
                    break;
                case 'on':
                    return 'UserSingUp';
                break;