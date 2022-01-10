<?php

    class DataBase
    {
            private $servername = "aa389941.mysql.tools";
            private $database = "aa389941_botbase";
            private $username = "aa389941_meridian246";
            private $password = "96stYP8BMf7h";

        public function DataBaseConnect()
        {
            $conn = mysqli_connect($this->servername, $this->username, $this->password, $this->database);
            if (!$conn) {return false;} else {return $conn;}
        }
        public function DataBaseDisconect($conn)
        {
            mysqli_close($conn);
        }
        public function TestChatId($chat_id)
        {
            $conn   =   $this->DataBaseConnect();
            $sql    =   "SELECT 1 FROM data_user WHERE chat_id=".$chat_id;
            $result =   mysqli_fetch_assoc( $result = mysqli_query($conn, $sql) );
            $this->DataBaseDisconect( $conn );
            if(count($result)>0){return true;}else{return false;}
        }
        public function GetUserOnID($id)
        {
            $conn   =   $this->DataBaseConnect();
            $sql    =   "SELECT 1 FROM data_user WHERE id=".$id;
            $result =   mysqli_fetch_assoc( $result = mysqli_query($conn, $sql) );
            $this->DataBaseDisconect( $conn );
            return $result;
        }
        public function GetUserOnChatID($chat_id)
        {
            $conn   =   $this->DataBaseConnect();
            $sql    =   "SELECT 1 FROM data_user WHERE chat_id=".$chat_id;
            $result =   mysqli_fetch_assoc( $result = mysqli_query($conn, $sql) );
            $this->DataBaseDisconect( $conn );
            return $result;
        }

        public function CreateUser($chat_id)
        {
            $conn   =   $this->DataBaseConnect();
            $sql = "INSERT INTO data_user (chat_id,Name,City,Busines,AboutSelf,WhoSearch,Img) VALUES ('$chat_id','na','na',0,'na',0,'na')";
            mysqli_query($conn, $sql);
            $result = mysqli_insert_id($conn);
            $this->DataBaseDisconect( $conn );
            return $result;
        }
        public function UpdateUserData($item,$message)
        {
            return 'Тест!!!';
        }
    //    public function SetPhotoStore(){}
    }


    class MyBot extends DataBase
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
            public function SwitchCaseStep_Name($message)
            {
                    switch ($message) 
                {
                    case 'нет':
                        $send_data = ['text' => 'Приходите еще!'];
                        break;
                    case 'да':
                        $send_data = ['text' => 'Ваше имя?'];
                        break;
                    default: //---------------------------------------------------//
                    $send_data = 
                    [
                        'text'=> 'Для использования бота необходимо ответить на несколько вопросов. Ваши данные не будут использованы для рекламной рассылки организаторами конференции и её участниками. Бот обеспечивает анонимность и использовать ваши контакты в корыстных целях не получится. Если вы согластны, нажмите ДА.',
                        'reply_markup'=>
                        [
                            'resize_keyboard' => true, 
                            'keyboard' =>   
                            [
                                [
                                    ['text' => 'Да'],
                                    ['text' => 'Нет'],
                                ]
                            ]
                        ]
                    ];
                }        
                return $send_data;
            }        
            public function SwitchCaseStep_City($message)
            {
                $send_data = ['text' => 'Здравствуйте, '.$$message.'! Напишите из какого Вы города'];
                return $send_data;
            }        
            public function SwitchCaseStep_Busines($message)
            {
                $this->UpdateUserData('City',$message);
                $send_data = 
                [
                    'text'=> $message.' Классный город! А кем Вы являетесь? Выберите один из вариантов',
                    'reply_markup'=>
                    [
                        'resize_keyboard' => true, 
                        'keyboard' =>   
                        [
                            [
                                ['text' => 'Работаю маркетологом'],
                                ['text' => 'Владею Маркетинговым агенством'],
                            ],
                            [
                                ['text' => 'Занимаюсь другим бизнесом'],
                                ['text' => 'Другая сфера деятельности'],
                            ]
                        ]
                    ]
                ];
                return $send_data;
            }        
            public function SwitchCaseStep_AboutSelf($message)
            {
                $this->UpdateUserData('Busines',$message);
                $UserName = 'Тест!!!';
                $send_data = 
                [
                    'text'=> $UserName.', а теперь напишите какими навыками и компетенциями вы обладаете! Укажите кратко ваши самые сильные стороны и достижения',
                ];
                return $send_data;
            }        
            public function SwitchCaseStep_WhoSearch($message)
            {
                $this->UpdateUserData('Busines',$message);
                $UserName = 'Тест!!!';
                $send_data = 
                [
                    'text'=> $UserName.', Здорово! А кого бы вы хотели найти на конференции?',
                    'reply_markup'=>
                    [
                        'resize_keyboard' => true, 
                        'keyboard' =>   
                        [
                            [
                                ['text' => 'Сотрудника'],
                                ['text' => 'Инвестора'],
                            ],
                            [
                                ['text' => 'Новые знакомства'],
                                ['text' => 'Заказчика'],
                            ],
                            [
                                ['text' => 'Партнера'],
                                ['text' => 'Приключение'],
                            ],
                        ]
                    ]
                ];
                return $send_data;
            }        
            public function SwitchCaseStep_Photo($message)
            {
                $this->UpdateUserData('Busines',$message);
                $UserName = 'Тест!!!';
                $send_data = 
                [
                    'text'=> $UserName.', Круто! Пришлите Вашу фотографию на аватар профиля в боте',
                ];
                return $send_data;
            }        
            public function RegFinal($message)
            {
                $send_data = 
                [
                    'text'=> 'Добро пожаловать на 💙 Сочинский Маркетинг Форум – главное мероприятие про продажи и маркетинг в Сочи! Здесь Вы найдете тех, кто поможет Вам перейти на новый уровень!',
                ];
                return $send_data;
            }

    }


?>

