<?php
header('Content-type: text/html; charset=utf-8');
mb_internal_encoding("UTF-8");
include 'MyBot.php';

//$photo_data = $NewBot->getData('php://input');
//$data = $photo_data;


$data = json_decode(file_get_contents('php://input'), TRUE);
$photo_data = $data;
$data = $data['callback_query'] ? $data['callback_query'] : $data['message'];
$message = mb_convert_encoding(($data['text'] ? $data['text'] : $data['data']), "UTF-8");
$DB     = new DataBase;
$NewBot = new MyBot;

$method = 'sendMessage';
$chat_id = $data['chat'] ['id'];
$first_start=false;
$User = $DB->CreateUser($data['chat'] ['id'],$data['message']['from']['id']); $first_start=true;
/////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////
if($DB->TestChatId($data['chat'] ['id'])==false)    // Если записи Чата нет, значит создаем пользователя принудительно
    {
        $User = $DB->CreateUser($data['chat'] ['id'],$data['message']['from']['id']); $first_start=true;
    }

    $Status  = $NewBot->TestRegisterUserData($chat_id); //Узнаем какие поля не заполнены on - значит все заполнили

    if($first_start==false) //Если это первый запуск то пока ничего не пишем в базу
    {
        if($message[0]<>'/' AND $message[0]<>'<')
        {
            $Botss = new MyBot;
            if($Status=='ch_Img_reg')
            {
                // если пришла картинка то сохраняем ее у себя
                    if (array_key_exists('photo', $photo_data['message']))
                    {
///////////////////////////////////////
$send_data =['text'=>'array_key_exists! начало--101'];
$send_data['chat_id'] = $data['chat'] ['id'];
$res = $Botss->sendMessage($method, $send_data, $Botss->botToken);                            
//////////////////////////////////////
                    $FotoBot = new Bot;
                    $re = $FotoBot->init('php://input');
                        if($re['boolean']==true)
                        {
                            $url = $NewBot->url.$re['name'];
                            $NewBot->StorePhotoUrl($re['name'],$chat_id);
                            $Status  = $NewBot->RegisterPhotoStore($Status,$chat_id,$data);
                            $send_data =['text'=>'Картинка отправлена!'];
                            $send_data['chat_id'] = $data['chat'] ['id'];
                            $res = $Botss->sendMessage($method, $send_data, $Botss->botToken);                            
                        }        
                        else
                        {
                            $send_data =['text'=>'Что то пошло не так!'];
                            $send_data['chat_id'] = $data['chat'] ['id'];
                            $res = $Botss->sendMessage($method, $send_data, $Botss->botToken);
                        }
//                        $str = json_encode($photo_data['message']['photo']);
//                        $str2 = json_encode($re); 
///////////////////////////////////////
$send_data =['text'=>'array_key_exists! После --101 '.$url];
$send_data['chat_id'] = $data['chat'] ['id'];
$res = $Botss->sendMessage($method, $send_data, $Botss->botToken);                            
//////////////////////////////////////

                    }    
                    //$Bots = new MyBot;
                    //$res = $Bots->sendMessage($method, $send_data, $Bot->botToken);
                    else {$send_data =['text'=>'Картинка не отправлена!'];}
            }
            $Status  = $NewBot->RegisterInfoStore($Status,$chat_id,$message);
        }    
    }    

    $Status  = $NewBot->TestRegisterUserData($chat_id); //Узнаем какие поля не заполнены on - значит все заполнили

    if($Status<>'on')
    {
        //Проходим необходимые или не достающие этапы регистрации
        switch($message)
        {
            case '/start': $send_data = $NewBot->Request_Start(); break;
            case '<Нет, мне не интересно!>': $send_data = $DB->DeleteChat($chat_id); break;
            default : $send_data = $NewBot->RegisterTextShow($Status,$message); break;
        }
    } 
    $Status  = $NewBot->TestRegisterUserData($chat_id); //Узнаем какие поля не заполнены on - значит все заполнили

    $Status_ed = $DB->GetStatus_ed($chat_id);

    if($message[0] <> '<' AND $message[0] <> '/') //mb_substr($myString, 0, 1)
    {
        if($Status_ed=='Name_ed')       {$DB->UpdateUserData('Name',     $chat_id,$message); $DB->UpdateStatus_ed($chat_id,'0');}
        if($Status_ed=='AboutSelf_ed')  {$DB->UpdateUserData('AboutSelf',$chat_id,$message); $DB->UpdateStatus_ed($chat_id,'0');}
        if($Status_ed=='WhoSearch_ed')  {$DB->UpdateUserData('WhoSearch',$chat_id,$message); $DB->UpdateStatus_ed($chat_id,'0');}
        if($Status_ed=='Img_ed')        
        {
            $FotoBott = new Bot;
            $res = $FotoBott->init('php://input');
            $DB->UpdateUserDataFoto('NewView',   $chat_id,$res); $DB->UpdateStatus_ed($chat_id,'0');
        }
    }

    $Status  = $NewBot->TestRegisterUserData($chat_id); //Узнаем какие поля не заполнены on - значит все заполнили


    if($Status=='on')
    {
        $send_data = ['text'=>$Status];
        //Если регистрационная информация полная, значит тут уже работаем.
        switch($message)
        {
            case '<Связаться с клиентским менеджером>': $send_data = $NewBot->ConnetcWithManager();  break;
            case '<Правила нетворкинга>':               $send_data = $NewBot->TimeLineShow();        break;
                ///////////////////////////////////////////////////////////////////////////////////////////////
            case '<Профиль>':                           $send_data = $NewBot->FormEditProfile($chat_id);     break;
                case '<Имя!>':                              $send_data = $NewBot->EditNameForm();       $DB->UpdateStatus_ed($chat_id,'Name_ed');       break;
                case '<Компетенция!>':                      $send_data = $NewBot->EditAboutSelfForm();  $DB->UpdateStatus_ed($chat_id,'AboutSelf_ed');  break;
                case '<Запрос к аудитории!>':               $send_data = $NewBot->EditRequestAudForm(); $DB->UpdateStatus_ed($chat_id,'WhoSearch_ed');  break;
                case '<Фотография!>':                       $send_data = $NewBot->EditPhotoForm();      $DB->UpdateStatus_ed($chat_id,'Img_ed');        break;
                case '<Не буду пока ничего менять!>':       $send_data = $NewBot->UserPanel($chat_id);           break;
                ///////////////////////////////////////////////////////////////////////////////////////////////    
            case '<Нетворкинг>':                        $send_data = $NewBot->NetworkingShow();         break;
                case '<Связаться>':                        $send_data = ['text'=>'Связаться с ...'];    break;
                case '<Следующий>':                       $send_data = $NewBot->NetworkingShow();      break;
                case '<Выйти>':                            $send_data = $NewBot->UserPanel($chat_id);                break;
                ///////////////////////////////////////////////////////////////////////////////////////////////    
            case 'Sochi farketing forum':               $send_data = $NewBot->MarketingPforum();     break;
            default :                                   $send_data = $NewBot->UserPanel($chat_id);           break;
        }
    }
/////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////
//$send_data = ['text'=>$message[0]];
$send_data['chat_id'] = $data['chat'] ['id'];
$Bot = new MyBot;
$res = $Bot->sendMessage($method, $send_data, $Bot->botToken);
?>


