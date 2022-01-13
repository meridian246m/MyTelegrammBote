<?php
include 'MyBot.php';
$data = json_decode(file_get_contents('php://input'), TRUE);
$data = $data['callback_query'] ? $data['callback_query'] : $data['message'];
$message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');

$DB     = new DataBase;
$NewBot = new MyBot;
$method = 'sendMessage';
$chat_id = $data['chat'] ['id'];


if($DB->TestChatId($data['chat'] ['id'])==false)
    {
        $send_data  =   $NewBot->StartRegister();
    }
    else
    {
        $send_data  =   $NewBot->RegFinal();                
    }

    if($DB->UpdateUserInfo($chat_id,$message)==true){$message='<профиль>';}
    $message = $NewBot->RegisterUserInfo($chat_id,$message);
    switch($message)
            {
                case '<регистрация!>':
                    $User = $DB->CreateUser($data['chat'] ['id']);
                    $send_data = $NewBot->SwitchCaseStep_Start();        
                break;

                case '<да>':
                    $User = $DB->CreateUser($chat_id);
                    $send_data  =   $NewBot->SwitchCaseStep_Name();
                break;

                case '<нет>':
                    $send_data =    $NewBot->DeleteChat($chat_id);
                break;

                case '/start':
                    if($DB->TestChatId($data['chat'] ['id'])==false)
                    {
                        $send_data  =   $NewBot->StartRegister();
                    }
                    else
                    {
                        $send_data  =   $NewBot->RegFinal();                
                    }
                break;

                    case '<правила нетворкинга>': 
                        $send_data = $NewBot->TimeLineShow();
                    break;   

                    case '<профиль>': 
                        $send_data = $NewBot->FormEditProfile($DB->GetUserOnChatID($chat_id));
                    break;   

                    case '<не буду пока ничего менять!>': 
                        $send_data  =   $NewBot->RegFinal();
                    break;   
            
                    case '<имя!>':
                        $DB->UpdateUserData('Status',$chat_id,'ch_Name');        
                        $send_data = $NewBot->SwitchCaseStep_Name();
                    break;

                    case '<компетенция!>':        
                        $DB->UpdateUserData('Status',$chat_id,'ch_AboutSelf');        
                        $send_data = $NewBot->SwitchCaseStep_AboutSelf();
                    break;

                    case '<запрос к аудитории!>':        
                        $DB->UpdateUserData('Status',$chat_id,'ch_WhoSearch');
                        $send_data = $NewBot->SwitchCaseStep_WhoSearch();        
                    break;

                    case '<фотография!>':        
                        $DB->UpdateUserData('Status',$chat_id,'ch_Img');        
                        $send_data = $NewBot->SwitchCaseStep_Photo();        
                    break;

                    case '<не буду пока ничего менять!>':        
                        $User =         $DB->GetUserOnChatID($chat_id);
                        $send_data =    $NewBot->TestReg($User,$message,$chat_id);
                    break;

                    case '<нетворкинг>':
                         
                    break;    

            }    

$send_data['chat_id'] = $data['chat'] ['id'];
$Bot = new MyBot;
$res = $Bot->sendMessage($method, $send_data, $Bot->botToken);
?>
