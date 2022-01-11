
<?php
include 'MyBot.php';
$data = json_decode(file_get_contents('php://input'), TRUE);
$data = $data['callback_query'] ? $data['callback_query'] : $data['message'];
$message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');

$DB     = new DataBase;
$NewBot = new MyBot;
$method = 'sendMessage';
$chat_id = $data['chat'] ['id'];

$result = $DB->TestChatId($chat_id) ? $result = true : $result = $DB->CreateUser($chat_id);
if($result==true)
{
    $User = $DB->GetUserOnChatID($chat_id);
    if($User['Name']=='0' || $User['Name']==''){$info='Name';}    
    elseif($User['City']=='0' || $User['City']==''){$info='City';}    
    elseif($User['Busines']=='0' || $User['Busines']==''){$info='Busines';}    
    elseif($User['AboutSelf']=='0' || $User['AboutSelf']==''){$info='AboutSelf';}   
    elseif($User['WhoSearch']=='0' || $User['WhoSearch']==''){$info='WhoSearch';} 
    else {$info='AllDataOk';}  
    switch ($info)
    {
        case 'Name':
            $send_data = $NewBot->SwitchCaseStep_Name($message);      //ok!  
        break;
        case 'City':
            $DB->UpdateUserData('Name',$chat_id,$message);
            $send_data = $NewBot->SwitchCaseStep_City($message);      //ok!  
        break;
        case 'Busines':
            $DB->UpdateUserData('City',$chat_id,$message);
            $send_data = $NewBot->SwitchCaseStep_Busines($message);   //ok!
        break;
        case 'AboutSelf':
            $DB->UpdateUserData('Busines',$chat_id,$message);
            $send_data = $NewBot->SwitchCaseStep_AboutSelf($message); //ok! step 1 on 2
        break;
        case 'WhoSearch':
            $DB->UpdateUserData('AboutSelf',$chat_id,$message);
            $send_data = $NewBot->SwitchCaseStep_WhoSearch($message);   //ok! step 1 on 2
        break;    
        case 'AllDataOk':
            
            $send_data = $NewBot->RegFinal($message);                 //ok!
        break;    
        }
}


$send_data['chat_id'] = $data['chat'] ['id'];
$Bot = new MyBot;
$res = $Bot->sendMessage($method, $send_data, $Bot->botToken);
?>



