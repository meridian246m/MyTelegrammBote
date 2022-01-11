
<?php
include 'MyBot.php';
$data = json_decode(file_get_contents('php://input'), TRUE);
$data = $data['callback_query'] ? $data['callback_query'] : $data['message'];
$message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');

$DB     = new DataBase;
$NewBot = new MyBot;
$method = 'sendMessage';

$result = $DB->TestChatId($data['chat'] ['id']) ? $result = true : $result = $DB->CreateUser($data['chat'] ['id']);

if($result==true)
{
    $User = $DB->GetUserOnChatID($data['chat'] ['id']);
    if($User['Name']=='0' || $User['Name']==''){$info='Name';}    
    elseif($User['City']=='0' || $User['City']==''){$info='City';}    
    elseif($User['Busines']=='0' || $User['Busines']==''){$info='Busines';}    
    elseif($User['AboutSelf']=='0' || $User['AboutSelf']==''){$info='AboutSelf';}   
    elseif($User['WhoSearch']=='0' || $User['WhoSearch']==''){$info='WhoSearch';}   
    switch ($info)
    {
        case 'Name':
            $send_data = $NewBot->SwitchCaseStep_Name($message);      //ok!  
        break;
        case 'City':
        //    $send_data = $NewBot->SwitchCaseStep_City($message);      //ok!  
        break;
        case 'Busines':
        //    $send_data = $NewBot->SwitchCaseStep_Busines($message);   //ok!
        break;
        case 'AboutSelf':
        //    $send_data = $NewBot->SwitchCaseStep_AboutSelf($message); //ok! step 1 on 2
        break;
        case 'WhoSearch':
        //    $send_data = $NewBot->SwitchCaseStep_WhoSearch($message);   //ok! step 1 on 2
        break;    
        case 'AllDataOk':
        //    $send_data = $NewBot->RegFinal($message);                 //ok!
        break;    
        }
}


$send_data['chat_id'] = $data['chat'] ['id'];
$Bot = new MyBot;
$res = $Bot->sendMessage($method, $send_data, $Bot->botToken);
?>



