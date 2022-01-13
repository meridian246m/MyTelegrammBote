<?php

    class DataBase
    {
            private $servername = "aa389941.mysql.tools";
            private $database = "aa389941_botbase";
            private $username = "aa389941_meridian246";
            private $password = "96stYP8BMf7h";
            private $url = "https://telebot.tesovii.space/img/";
        public function DataBaseConnect()
        {
            $conn = mysqli_connect($this->servername, $this->username, $this->password, $this->database);
            mysqli_set_charset($conn, "utf8");
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
        public function CreateUser($chat_id,$UserChatId)
        {
            $conn   =   $this->DataBaseConnect();
            $sql = "INSERT INTO data_user (chat_id,Name,City,Busines,AboutSelf,WhoSearch,Status,NewView,UserChatId) VALUES ('$chat_id','0','0','0','0','0','ch_Name_reg','0',".$UserChatId.")";
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
        public function UpdateUserDataFoto($item,$chat_id,$message)
        {
            $url = $this->url.$message['name'];
            $conn   =   $this->DataBaseConnect();
            $sql = "UPDATE data_user SET ".$item."='".$url."' WHERE chat_id=".$chat_id;
            mysqli_query($conn, $sql);
            $sql = "UPDATE data_user SET Test='".$url."' WHERE chat_id=".$chat_id;
            mysqli_query($conn, $sql);

            $result = $this->GetUserOnChatID($chat_id);
            return $result;
        }

        public function DeleteChat($chat_id)
        {
            $conn   =   $this->DataBaseConnect();
            $sql = "DELETE FROM data_user WHERE chat_id=".$chat_id;
            mysqli_query($conn, $sql);
            $result =   [
                            'text'=>'Приходите еще!',
                            'reply_markup'=>
                            [
                                'resize_keyboard' => true, 
                                'keyboard' =>   
                                [
                                    [
                                        ['text' => '/start']
                                    ]      
                                ]
                            ]     
                        ];
            return $result;
        }
        public function RegisterInfoStore($Status,$chat_id,$data)
        {
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
            }
        }
        public function RegisterPhotoStore($Status,$chat_id,$data)
        {
            switch($Status)
            {
                case 'ch_Img_reg':
                    $this->UpdateUserData('NewView',$chat_id,$data);
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
                    $this->UpdateUserData('NewView',$chat_id,$message);
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
           if($User['NewView']=='0'         || $User['NewView']=='')      {return 'ch_Img_reg';}
           return 'on';
        }
        public function UpdateStatus_ed($chat_id,$status)
        {
            $conn   =   $this->DataBaseConnect();
            $sql = "UPDATE data_user SET Status_ed='".$status."' WHERE chat_id=".$chat_id;
            mysqli_query($conn, $sql);
            $this->DataBaseDisconect( $conn );
        }
        public function UpdateStatus_reg($chat_id,$status)
        {
            $conn   =   $this->DataBaseConnect();
            $sql = "UPDATE data_user SET Status='".$status."' WHERE chat_id=".$chat_id;
            mysqli_query($conn, $sql);
            $this->DataBaseDisconect( $conn );
            if($status=='on'){return '<Спасибо! Ваше фото загруженно.>';}
            if($status=='ch_Img_reg'){return '<Что-то пошло не так, попробуйте еще раз>';}

        }

        public function GetStatus_ed($chat_id)
        {
            $User = $this->GetUserOnChatID($chat_id);
            return $User['Status_ed'];
        }
        public function GetNextUser()
        {
            $conn   =   $this->DataBaseConnect();
            $sql    =   "SELECT * FROM data_user ORDER BY RAND() LIMIT 1";
            $User =   mysqli_fetch_assoc( $User = mysqli_query($conn, $sql) );
            return $User;
        }
        public function GetNextUserFieldValue()
        {
            $conn   =   $this->DataBaseConnect();
            $sql    =   "SELECT * FROM data_user WHERE City = '125'";
            $Users = mysqli_query($conn, $sql);
            foreach($Users as $index){
                   $User =  $index;
            }
            return $User;
        }
        public function StorePhotoUrl($file,$chat_id)
        {
            $url = $this->url.$file;
            $conn   =   $this->DataBaseConnect();
            
            $sql = "UPDATE data_user SET NewView='".$url."' WHERE chat_id=".$chat_id;
            mysqli_query($conn, $sql);

            $sql = "UPDATE data_user SET Test='".$url."' WHERE chat_id=".$chat_id;
            mysqli_query($conn, $sql);

            $this->DataBaseDisconect( $conn );
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
                    'text'=> 'Напишите ваше Имя',
                    'reply_markup'=>['remove_keyboard'=>true]
                ];
                return $send_data;
            }                      
            public function Request_City($UserName) //ok!
            {
                $send_data = 
                [
                'text' => 'Здравствуйте, '.$UserName.'! Напишите из какого Вы города',
                'reply_markup'=>['remove_keyboard'=>true]
                ];
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
            public function Request_AboutSelf() //ok! step 1 on 2
            {
                $send_data = 
                [
                    'text'=> 'А теперь напишите какими навыками и компетенциями вы обладаете! Укажите кратко ваши самые сильные стороны и достижения',
                    'reply_markup'=>['remove_keyboard'=>true]
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
                        ],
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
                    ],
                    'reply_markup'=>['remove_keyboard'=>true]
                ];
                return $send_data;
            }        
            public function UserPanel($chat_id)
            {
                $User= $this->GetUserOnChatID($chat_id);
                $send_data = 
                [
                    'text'=> "**".$User['Name']."!** Добро пожаловать на 💙 международную нетворкинг платформу OPENATOR! Здесь Вы найдете тех, кто поможет Вам перейти на новый уровень!",
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
            public function TimeLineShow()
            {
                $result = ['text'=>"Регистрация 9:00-10:00\r\nОткрытие 10:00-10:30\r\n\r\nГрибов 10:30-11:15\r\nУшенин 11:15-11:45\r\nБермуда 11:45-12:45\r\n\r\nКофе-брейк 12:45-13:15\r\n\r\nСташкевич 13:15-14:00\r\nВоловик 14:00-14:45\r\nКорс 14:45-15:30\r\nВоронин 15:30-16:30\r\n\r\nОбеденный перерыв 16:30-17:30\r\n\r\nАлексеев 17:30-18:15\r\nЕфремов 18:15-19:00\r\nЗакрытие 19:00-19:30\r\n"];
                return $result;
            }
            public function FormEditProfile($chat_id)
            {
                $User = $this->GetUserOnChatID($chat_id);
                $send_data = 
                [
                    'text'=>
                    "".$User['Test']."\n\r *Имя:* ".$User['Name']."\n\r *Город:* ".$User['City']."\n\r *Занятие:* ".$User['Busines']."\n\r *Компетенция:* ".$User['AboutSelf']."\n\r *Ищу:* ".$User['WhoSearch'].
                    "\n\r \n\r *Здесь вы можете поменять данные о себе. Что вы хотите изменить?*",
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
                    ],
                    'parse_mode' => 'markdown'
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

            public function NetworkingShowNext()
            {
                $User = $this->GetNextUser();
                $send_data = 
                [
                    'text'=> $User['NewView']."\r\n".$User['Name']."\r\n".$User['City']."\r\n".$User['Busines']."\r\n".$User['AboutSelf']."\r\n".$User['WhoSearch'],
                    'reply_markup'=>
                    [
                        'resize_keyboard' => true, 
                        'keyboard' =>   
                        [
                            [
                                ['text' => '<Связаться>'],
                                ['text' => '<Следующий>']
                            ],
                            [
                                ['text' => '<Выйти>'],
                            ]

                        ]
                    ],
                    'parse_mode' => 'markdown'
                ];
                return $send_data;
            }

            public function NetworkingShow()
            {
                $User = $this->GetNextUser();
                $send_data = 
                [
                    'text'=> 
                    $User['Test']."\n\r".
                    "*".$User['Name']."*\n\r".
                    "Город: ".$User['City']."\n\r".
                    "Занимаюсь: ".$User['Busines']."\n\r".
                    "_Могу быть полезен: ".$User['AboutSelf']."_\n\r".
                    "Я ищу: ".$User['WhoSearch'],
                    'reply_markup'=>
                    [
                        'resize_keyboard' => true, 
                        'keyboard' =>   
                        [
                            [
                                ['text' => '<Связаться>'],
                                ['text' => '<Следующий>']
                            ],
                            [
                                ['text' => '<Выйти>'],
                            ]

                        ]
                    ],
                    'parse_mode' => 'markdown'
                ];
                return $send_data;
            }
            public function ConnetcWithManager()
            {
                return ['text'=>'Тут можно связаться с менеджером'];
            }
            public function MarketingPforum()
            {
                return ['text'=>'Тут можно будет наверное переход кудато'];
            }

            public function EditNameForm()
            {
                $send_data = 
                [ 
                    'text'=>'Введите Новое Имя',
                    'reply_markup'=>
                    [
                        'resize_keyboard' => true, 
                        'keyboard' =>   
                        [
                            [
                                ['text' => '<Не буду пока ничего менять!>'],
                            ]
                        ]
                    ]
                ];
                return $send_data;
            }
            public function EditAboutSelfForm()
            {
                $send_data = [ 'text'=>'Введите новое описание о ваших достижениях',
                                    'reply_markup'=>
                                    [
                                        'resize_keyboard' => true, 
                                        'keyboard' =>   
                                        [
                                            [
                                                ['text' => '<Не буду пока ничего менять!>'],
                                            ]
                                        ]
                                    ]
                            ];
                return $send_data;
            }
            public function EditRequestAudForm()
            {
                $send_data = 
                [
                    'text'=> 'Кого бы вы хотели найти на конференции?',
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
                            [
                                ['text' => '<Не буду пока ничего менять!>'],
                            ]
                        ]
                    ]
                ];
                return $send_data;
            }
            public function EditPhotoForm()
            {
                $send_data = 
                ['text'=>'Пришлите Вашу фотографию на аватар профиля в боте','reply_markup'=>['remove_keyboard' => true]];
                return $send_data;
            }
                        // общая функция загрузки картинки
                        public function getPhoto($data)
                        {
                            // берем последнюю картинку в массиве
                            $file_id = $data[count($data) - 1]['file_id'];
                            // получаем file_path
                            $file_path = $this->getPhotoPath($file_id);
                            // возвращаем результат загрузки фото
                            return $this->copyPhoto($file_path);
                        }
            
                        // функция получения метонахождения файла
                        public function getPhotoPath($file_id) {
                            // получаем объект File
                            $array = json_decode($this->requestToTelegram(['file_id' => $file_id], "getFile"), TRUE);
                            // возвращаем file_path
                            return  $array['result']['file_path'];
                        }

                        // копируем фото к себе
                        public function copyPhoto($file_path) {
                            // ссылка на файл в телеграме
                            $file_from_tgrm = "https://api.telegram.org/file/bot".$this->botToken."/".$file_path;
                            // достаем расширение файла
                            $ext =  end(explode(".", $file_path));
                            // назначаем свое имя здесь время_в_секундах.расширение_файла
                            $name_our_new_file = time().".".$ext;
                            return copy($file_from_tgrm, "img/".$name_our_new_file);
                        }

                        private function requestToTelegram($data, $type)
                        {
                            $result = null;
                            if (is_array($data)) {
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $this->apiUrl . $this->botToken . '/' . $type);
                                curl_setopt($ch, CURLOPT_POST, count($data));
                                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                                $result = curl_exec($ch);
                                curl_close($ch);
                            }
                            return $result;
                        }

                        public function getData($data)
                        {
                            return json_decode(file_get_contents($data), TRUE);
                        }
                        

                        function sendMessage_22222($chatId, $message, $token)
                        {
                            $url = "https://api.telegram.org/{$token}/sendMessage?" . http_build_query([
                                    'chat_id' => $chatId,
                                    'text' => $message
                                ]);
                            $ch = curl_init();
                            $optArray = [
                                CURLOPT_URL => $url,
                                CURLOPT_RETURNTRANSFER => true
                            ];
                            curl_setopt_array($ch, $optArray);
                            curl_exec($ch);
                            curl_close($ch);
                        }

        }

        class Bot
        {
            // <bot_token> - созданный токен для нашего бота от @BotFather
            private $botToken = "1238564789:AAF1kydnaZ_ZWXlBrCXVyKC5RVeOLynCMvg";
            // адрес для запросов к API Telegram
            private $apiUrl = "https://api.telegram.org/bot";
        
            public function init($data_php)
            {
                // создаем массив из пришедших данных от API Telegram
                $data = $this->getData($data_php);
                // id чата отправителя
                $chat_id = $data['message']['chat']['id'];
                    if (array_key_exists('photo', $data['message'])) 
                    {
                        $re = $this->getPhoto($data['message']['photo']);              
                        $this->sendMessage($chat_id, $re);
                        return $re;
                    } 
                
            }
        
            // функция отправки текстового сообщения
            private function sendMessage($chat_id, $text)
            {
                $this->requestToTelegram([
                    'chat_id' => $chat_id,
                    'text' => $text,
                ], "sendMessage");
            }
        
            // общая функция загрузки картинки
            private function getPhoto($data)
            {
                // берем последнюю картинку в массиве
                $file_id = $data[count($data) - 1]['file_id'];
                // получаем file_path
                $file_path = $this->getPhotoPath($file_id);
                // возвращаем результат загрузки фото
                $re = $this->copyPhoto($file_path);
                return  $re;
            }
        
            // функция получения метонахождения файла
            private function getPhotoPath($file_id) {
                // получаем объект File
                $array = json_decode($this->requestToTelegram(['file_id' => $file_id], "getFile"), TRUE);
                // возвращаем file_path
                return  $array['result']['file_path'];
            }
        
            // копируем фото к себе
            private function copyPhoto($file_path) {
                // ссылка на файл в телеграме
                $file_from_tgrm = "https://api.telegram.org/file/bot".$this->botToken."/".$file_path;
                // достаем расширение файла
                $ext =  end(explode(".", $file_path));
                // назначаем свое имя здесь время_в_секундах.расширение_файла
                $name_our_new_file = time().".".$ext;
                $re = Array();
                $re['copy'] = copy($file_from_tgrm, "img/".$name_our_new_file);
                $re['name'] = $name_our_new_file;
                $re['boolean'] = true;
                return $re;
            }
           
            // функция логирования в файл
            private function setFileLog($data, $file)
            {
                $fh = fopen($file, 'a') or die('can\'t open file');
                ((is_array($data)) || (is_object($data))) ? fwrite($fh, print_r($data, TRUE) . "\n") : fwrite($fh, $data . "\n");
                fclose($fh);
            }
        
            /**
             * Парсим что приходит преобразуем в массив
             * @param $data
             * @return mixed
             */
            private function getData($data)
            {
                return json_decode(file_get_contents($data), TRUE);
            }
        
            /** Отправляем запрос в Телеграмм
             * @param $data
             * @param string $type
             * @return mixed
             */
            private function requestToTelegram($data, $type)
            {
                $result = null;
        
                if (is_array($data)) {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $this->apiUrl . $this->botToken . '/' . $type);
                    curl_setopt($ch, CURLOPT_POST, count($data));
                    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                    $result = curl_exec($ch);
                    curl_close($ch);
                }
                return $result;
            }
        }

/*
        1.Сообщения пользователю
        2.Упоминание пользователя tg://user?id=123456789
        Упоминание пользователя — текст, похожий на ссылку, клик по которому открывает профиль пользователя. 
        Если упомянуть в группе её участника, он получит уведомление.
        Чтобы вставить в сообщение упоминание пользователя, в Bot API нужно встроить ссылку на tg://user?id=123456789.

        Кнопки под сообщениями (они же inline keyboards / inline buttons) в основном бывают трёх видов:

        URL button — кнопка с ссылкой.
        
        Callback button. При нажатии на такую кнопку боту придёт апдейт. С созданием кнопки можно указать параметр, который будет указан в этом апдейте (до 64 байтов). Обычно после нажатий на такие кнопки боты изменяют исходное сообщение или показывают notification или alert.
        
        Switch to inline button. Кнопка для переключения в инлайн-режим (об инлайн-режиме см. ниже). Кнопка может открывать инлайн в том же чате или открывать меню для выбора чата. Можно указать в кнопке запрос, который появится рядом с никнеймом бота при нажатии на кнопку.
*/
        ?>