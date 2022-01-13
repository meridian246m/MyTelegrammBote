<?php

class DataBase
{
        private $servername =   "aa389941.mysql.tools";
        private $database =     "aa389941_botbase";
        private $username =     "aa389941_meridian246";
        private $password =     "96stYP8BMf7h";
        private $ImgUrl =       "https://telebot.tesovii.space/img/";

        private function DBConnect(){
            $conn = mysqli_connect($this->servername, $this->username, $this->password, $this->database);
            mysqli_set_charset($conn, "utf8");
            if (!$conn) {return false;} else {return $conn;}
        }
        private function DBDisconnect($link){
                mysqli_close($link);
        }
        private function CreateUser($UserChatId,$data){
            $link   =   $this->DBConnect();
            $chat_id =      $data['chat']['id'];
            $first_name =   $data['chat']['first_name'];
            $last_name =    $data['chat']['last_name'];
            $username =     $data['chat']['username'];
            $Status_reg = 'ch_Name_reg';
            $Status_ed  = 'on';
            ////////////////////////////////////////////
            $sql = "INSERT INTO users (chat_id,Name,City,Busines,AboutSelf,WhoSearch,ImgProfile,first_name,last_name,username,Status_reg,Status_ed) 
            VALUES 
            ('$chat_id','<0>','<0>','<0>','<0>','<0>','$first_name','$last_name','$username','$Status_reg','$Status_ed')";
            ////////////////////////////////////////////
            $result = mysqli_query($link, $sql);
            $user_id = mysqli_insert_id($link);
            $this->DBDisconnect( $link );
            return $result;

        }
        private function DeleteChat($chat_id){
            $link=    $this->DBConnect();
            $sql =    "DELETE FROM users WHERE chat_id=".$chat_id;
            $result = mysqli_query($link, $sql);
            return $result;
        }
        private function UpdateUser($field,$data)
        {
            $chat_id =      $data['chat']['id'];
            $message =      $data['text'];
            $link   =   $this->DataBaseConnect($field,$data);
            $sql = "UPDATE users SET ".$field."='".$message."' WHERE chat_id=".$chat_id;
            $result = mysqli_query($link, $sql);
            return $result;
        }
}