<?php
    class MyBot 
{
            public           $botToken = '1238564789:AAF1kydnaZ_ZWXlBrCXVyKC5RVeOLynCMvg';
            public function  GetBotToken(){return $this->botToken;}
            public function  SetBotToken($bToken){$this->botToken = $bToken;}
            public function  sendMessage($method, $data, $token, $headers = [])
            {
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_POST => 1,
                    CURLOPT_HEADER => 0,
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => 'https://api.telegram.org/bot' . $token . '/' . $method,
                    CURLOPT_POSTFIELDS => json_encode($data),
                    CURLOPT_HTTPHEADER => array_merge(array("Content-Type: application/json"))
                ]);
                $result = curl_exec($curl);
                curl_close($curl);
                return (json_decode($result, 1) ? json_decode($result, 1) : $result);
            }
}

    class DataBase
    {
            private $servername = "aa389941.mysql.tools";
            private $database = "aa389941_botbase";
            private $username = "aa389941_meridian246";
            private $password = "96stYP8BMf7h";

        public function DataBaseExecute($sql)
        {
            $conn = mysqli_connect($this->servername, $this->username, $this->password, $this->database);
            if (!$conn) {die("Connection failed: " . mysqli_connect_error());} else {echo "Connected successfully";}
            $result = mysqli_query($conn, $sql); 
            mysqli_close($conn);
            return $result;
        }
        public function TestChatId($chat_id)
        {
            $sql="SELECT 1 FROM data_user WHERE chat_id=".$chat_id;
            $result = mysqli_fetch_assoc($this->DataBaseExecute($sql));
            if(count($result)>0){return true;}else{return false;}
        }
        public function InsertNewUser($chat_id)
        {
            $conn = mysqli_connect($this->servername, $this->username, $this->password, $this->database);
            if (!$conn) {die("Connection failed: " . mysqli_connect_error());} else {echo "Connected successfully";}
            $result = mysqli_query($conn, $sql); 
            mysqli_close($conn);
            return $result;
        }
        public function CreateUser($chat_id)
        {
            $sql = "INSERT INTO data_user (chat_id,Name,City,Busines,AboutSelf,WhoSearch,Img) VALUES ('$chat_id','na','na',0,'na',0,na)";
            $result = mysqli_fetch_assoc($this->DataBaseExecute($sql));
            return $result['chat_id'];
        }
    //    public function SetPhotoStore(){}


    }
?>

