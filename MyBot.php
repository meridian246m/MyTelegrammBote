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
            $sql    =   "SELECT * FROM data_user WHERE chat_id=".$chat_id." LIMIT 1";
            $result =   mysqli_fetch_assoc( $result = mysqli_query($conn, $sql) );
            $this->DataBaseDisconect( $conn );
            return $result;
        }
        public function CreateUser($chat_id)
        {
            $conn   =   $this->DataBaseConnect();
            $sql = "INSERT INTO data_user (chat_id,Name,City,Busines,AboutSelf,WhoSearch,Img,Status) VALUES ('$chat_id','0','0','0','0','0','0','ch_Name_reg')";
            mysqli_query($conn, $sql);
            $user_id = mysqli_insert_id($conn);
            $result = $this->GetUserOnID($user_id);
            $this->DataBaseDisconect( $conn );
            return $result;
        }
        public function UpdateUserData($item,$chat_id,$message)
        {
            $conn   =   $this->DataBaseConnect();
            $sql = "UPDATE data_user SET ".$item."='".$message."' WHERE chat_id=".$chat_id;
            mysqli_query($conn, $sql);
            $result = $this->GetUserOnChatID($chat_id);
            return $result;
        }
        public function DeleteChat($chat_id)
        {
            $conn   =   $this->DataBaseConnect();
            $sql = "DELETE FROM data_user WHERE chat_id=".$chat_id;
            mysqli_query($conn, $sql);
            $result = ['text'=>'Приходите еще!'];
            return $result;
        }
        public function RegisterInfoStore($Status,$chat_id,$data)
        {
            $User = $this->GetUserOnChatID($chat_id);
            switch($Status)
            {
                case 'ch_Name_reg':
                    $this->UpdateUserData('Name',$chat_id,$data);
                    $this->UpdateUserData('Status',$chat_id,'ch_City_reg');
                return 'ch_City_reg';
                break;
                case 'ch_City_reg':
                    $this->UpdateUserData('City',$chat_id,$data);
                    $this->UpdateUserData('Status',$chat_id,'ch_Busines_reg');
                return 'ch_Busines_reg';
                break;
                case 'ch_Busines_reg':
                    $this->UpdateUserData('Busines',$chat_id,$data);
                    $this->UpdateUserData('Status',$chat_id,'ch_AboutSelf_reg');
                return 'ch_AboutSelf_reg';
                break;
                case 'ch_AboutSelf_reg':
                    $this->UpdateUserData('AboutSelf',$chat_id,$data);
                    $this->UpdateUserData('Status',$chat_id,'ch_WhoSearch_reg');
                return 'ch_WhoSearch_reg';
                break;
                case 'ch_WhoSearch_reg':
                    $this->UpdateUserData('WhoSearch',$chat_id,$data);
                    $this->UpdateUserData('Status',$chat_id,'ch_Img_reg');
                return 'ch_Img_reg';
                break;
                case 'ch_Img_reg':
                    $this->UpdateUserData('Img',$chat_id,$data);
                    $this->UpdateUserData('Status',$chat_id,'on');
                return 'on';
                break;      
                case 'on':
                return false;
                break;      
            }
        }
        public function UpdateUserInfo($chat_id,$message)
        {
            $User = $this->GetUserOnChatID($chat_id);
            switch($User['Status'])
            {
                case 'ch_Name':
                    $this->UpdateUserData('Name',$chat_id,$message);
                    $this->UpdateUserData('Status',$chat_id,'on');
                    return true;
                    break;
                case 'ch_AboutSelf':
                    $this->UpdateUserData('AboutSelf',$chat_id,$message);
                    $this->UpdateUserData('Status',$chat_id,'on');
                    return true;
                    break;
                case 'ch_WhoSearch':
                    $this->UpdateUserData('WhoSearch',$chat_id,$message);
                    $this->UpdateUserData('Status',$chat_id,'on');
                    return true;
                    break;
                case 'ch_Img':
                    $this->UpdateUserData('Img',$chat_id,$message);
                    $this->UpdateUserData('Status',$chat_id,'on');
                    return true;
                    break;
                case 'on':
                    return false;
                break;
            }    
        }
        public function TestRegisterUserData($chat_id)
        {
           $User = $this->GetUserOnChatID($chat_id);
           if($User['Name']=='0'        || $User['Name']=='')     {return 'ch_Name_reg';}
           if($User['City']=='0'        || $User['City']=='')     {return 'ch_City_reg';}
           if($User['Busines']=='0'     || $User['Busines']=='')  {return 'ch_Busines_reg';}
           if($User['AboutSelf']=='0'   || $User['AboutSelf']==''){return 'ch_AboutSelf_reg';}
           if($User['WhoSearch']=='0'   || $User['WhoSearch']==''){return 'ch_WhoSearch_reg';}
           if($User['Img']=='0'         || $User['Img']=='')      {return 'ch_Img_reg';}
           return 'on';
        }
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
            public function Request_Start() //ok!
            {
                $send_data = 
                    [
                        'text'=> 'Для использования бота необходимо ответить на несколько вопросов. Ваши данные не будут использованы для рекламной рассылки организаторами конференции и её участниками. Бот обеспечивает анонимность и использовать ваши контакты в корыстных целях не получится. Если вы согластны, нажмите ДА.',
                        'reply_markup'=>
                        [
                            'resize_keyboard' => true, 
                            'keyboard' =>   
                            [
                                [
                                    ['text' => '<Да, Согласен!>'],
                                    ['text' => '<Нет, мне не интересно!>'],
                                ]
                            ]
                        ]
                    ];
                return $send_data;
            }
            public function Request_Name() //ok!
            {
                $send_data = 
                [
                    'text'=> 'Напишите ваше Имя'
                ];
                return $send_data;
            }                      
            public function Request_City($UserName) //ok!
            {
                $send_data = ['text' => 'Здравствуйте, '.$UserName.'! Напишите из какого Вы города'];
                return $send_data;
            }        
            public function Request_Busines() //ok!
            {
                $send_data = 
                [
                    'text'=> 'Классный город! А кем Вы являетесь? Выберите один из вариантов',
                    'reply_markup'=>
                    [
                        'resize_keyboard' => true, 
                        'keyboard' =>   
                        [
                            [
                                ['text' => '<Работаю маркетологом>'],
                                ['text' => '<Владею Маркетинговым агенством>'],
                            ],
                            [
                                ['text' => '<Занимаюсь другим бизнесом>'],
                                ['text' => '<Другая сфера деятельности>'],
                            ]
                        ]
                    ]
                ];
                return $send_data;
            }                    
            public function Request_AboutSelf() //ok! step 1 on 2
            {
                $send_data = 
                [
                    'text'=> 'А теперь напишите какими навыками и компетенциями вы обладаете! Укажите кратко ваши самые сильные стороны и достижения'
                ];
                return $send_data;
            }                    
            public function Request_WhoSearch() //ok!
            {
                $send_data = 
                [
                    'text'=> 'Здорово! А кого бы вы хотели найти на конференции?',
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
            public function Request_Img()
            {
                $send_data = 
                [
                    'text'=>'Круто! Пришлите Вашу фотографию на аватар профиля в боте',
                    'reply_markup'=>
                    [
                        'resize_keyboard' => true, 
                        'keyboard' =>   
                        [
                            [
                                ['text' => ''],
                            ]
                        ]
                    ]
                ];
                return $send_data;
            }        
            public function UserPanel()
            {
                $send_data = 
                [
                    'text'=> 'Добро пожаловать на 💙 международную нетворкинг платформу OPENATOR! Здесь Вы найдете тех, кто поможет Вам перейти на новый уровень!',
                    'reply_markup'=>
                    [
                        'resize_keyboard' => true, 
                        'keyboard' =>   
                        [
                            [
                                ['text' => 'Sochi Marketing Forum'],
                            ],
                            [
                                ['text' => '<Связаться с клиентским менеджером>'],
                                ['text' => '<Правила нетворкинга>'],
                            ],
                            [
                                ['text' => '<Профиль>'],
                                ['text' => '<Нетворкинг>'],
                            ],
                        ]
                    ]
                ];
                return $send_data;
            }
            public function StartRegister()
            {
                $send_data = 
                [
                    'text'=> 'Зарегистрируйтесь!',
                    'reply_markup'=>
                    [
                        'resize_keyboard' => true, 
                        'keyboard' =>   
                        [
                            [
                                ['text' => '<Регистрация!>'],
                            ]
                        ]
                    ]
                ];
                return $send_data;

            }
            public function TestReg($User,$message,$chat_id)
            {
                        if($User['Name']=='0' || $User['Name']=='')
                        {$this->UpdateUserData('Name',$chat_id,$message);       return $this->SwitchCaseStep_City($message);}

                        elseif($User['City']=='0' || $User['City']=='')
                        {$this->UpdateUserData('City',$chat_id,$message);       return $this->SwitchCaseStep_Busines($message);}

                        elseif($User['Busines']=='0' || $User['Busines']=='')
                        {$this->UpdateUserData('Busines',$chat_id,$message);    return $this->SwitchCaseStep_AboutSelf($message);}

                        elseif($User['AboutSelf']=='0' || $User['AboutSelf']=='')
                        {$this->UpdateUserData('AboutSelf',$chat_id,$message);  return $this->SwitchCaseStep_WhoSearch($message);}

                        elseif($User['WhoSearch']=='0' || $User['WhoSearch']=='')
                        {$this->UpdateUserData('WhoSearch',$chat_id,$message);  return $this->SwitchCaseStep_Photo($message);}

                        elseif($User['Img']=='0' || $User['Img']=='')
                        {$this->UpdateUserData('Img',$chat_id,$message);        return $this->RegFinal();}

                        else {$this->RegFinal();}
            }
            public function TimeLineShow()
            {
                $result = ['text'=>"Регистрация 9:00-10:00\r\nОткрытие 10:00-10:30\r\n\r\nГрибов 10:30-11:15\r\nУшенин 11:15-11:45\r\nБермуда 11:45-12:45\r\n\r\nКофе-брейк 12:45-13:15\r\n\r\nСташкевич 13:15-14:00\r\nВоловик 14:00-14:45\r\nКорс 14:45-15:30\r\nВоронин 15:30-16:30\r\n\r\nОбеденный перерыв 16:30-17:30\r\n\r\nАлексеев 17:30-18:15\r\nЕфремов 18:15-19:00\r\nЗакрытие 19:00-19:30\r\n"];
                return $result;
            }
            public function FormEditProfile()
            {
                $send_data = 
                [
                    'text'=> 'Здесь вы можете поменять данные о себе. Что вы хотите изменить?',
                    'reply_markup'=>
                    [
                        'resize_keyboard' => true, 
                        'keyboard' =>   
                        [
                            [
                                ['text' => '<Имя!>'],
                                ['text' => '<Компетенция!>'],
                            ],
                            [
                                ['text' => '<Запрос к аудитории!>'],
                                ['text' => '<Фотография!>'],
                            ],
                            [
                                ['text' => '<Не буду пока ничего менять!>'],
                            ]
                        ]
                    ]
                ];
                return $send_data;

            } 

            public function RegisterTextShow($Status,$Info)
            {
                switch($Status)
                {
                    case 'ch_Name_reg':
                        return $this->Request_Name();    
                        break;
                    case 'ch_City_reg':
                        return $this->Request_City($Info);
                        break;    
                    case 'ch_Busines_reg':
                        return $this->Request_Busines();
                        break;        
                    case 'ch_AboutSelf_reg':
                        return $this->Request_AboutSelf();
                        break;
                    case 'ch_WhoSearch_reg':
                        return $this->Request_WhoSearch();
                        break;
                    case 'ch_Img_reg':
                        return $this->Request_Img();
                        break;
                    case 'on':
                        return $this->UserPanel();                        
                    break;
                }      
            }
    




        }

?>





