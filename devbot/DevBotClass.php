<?php
class DataBase
{
    private $servername =   "aa389941.mysql.tools";
    private $database =     "aa389941_botbase";
    private $username =     "aa389941_meridian246";
    private $password =     "96stYP8BMf7h";
    protected $ImgUrl =     "https://telebot.tesovii.space/devbot/img/";
        private function DBConnect()
            {
            $conn = mysqli_connect($this->servername, $this->username, $this->password, $this->database);
            mysqli_set_charset($conn, "utf8");
            if (!$conn) {return false;} else {return $conn;}}
        private function DBDisconnect($link)
            {
                mysqli_close($link);
            }
        protected function CreateUser($data)
            {
                $link   =   $this->DBConnect();
                $chat_id =      $data['message']['chat']['id'];
                if($chat_id==0){return false;}
                $first_name =   $data['message']['chat']['first_name'];
                $last_name =    $data['message']['chat']['last_name'];
                $username =     $data['message']['chat']['username'];
                $Status_ed  = 'close';
                ////////////////////////////////////////////
                $sql = "INSERT INTO users (chat_id,first_name,last_name,username,Status_ed) 
                VALUES 
                ('$chat_id','$first_name','$last_name','$username','$Status_ed')";
                ////////////////////////////////////////////
                $result = mysqli_query($link, $sql);
                $this->DBDisconnect( $link );
                return $result;
            }
        protected function DeleteUser($data)
            {
            $chat_id =  $data['message']['chat']['id'];
            $link=      $this->DBConnect();
            $sql =      "DELETE FROM users WHERE chat_id=".$chat_id;
            $result = mysqli_query($link, $sql);
            return $result;}
        protected function UpdateUser($field,$data)
            {
            $chat_id =      $data['message']['chat']['id'];
            $message =      $data['message']['text'];
            $link   =  $this->DBConnect();
            $sql = "UPDATE users SET ".$field."='".$message."' WHERE chat_id=".$chat_id;
            $result = mysqli_query($link, $sql);
            return $result;}

        protected function UpdateUserStatusReg($chat_id,$Status_reg)
            {
            $link   =  $this->DBConnect();
            $sql = "UPDATE users SET Status_reg='".$Status_reg."' WHERE chat_id=".$chat_id;
            $result = mysqli_query($link, $sql);
            return $result;}  
        protected function UpdateStatus_ed($chat_id,$status)
            {
                $link   =   $this->DBConnect();
                $sql = "UPDATE users SET Status_ed='".$status."' WHERE chat_id=".$chat_id;
                mysqli_query($link, $sql);
                $this->DBDisconnect( $link );
            }
        protected function SaveUserProfileFoto($chat_id,$filename)
            {
                $url    =   $filename;
                $link   =   $this->DBConnect();
                $sql = "UPDATE users SET ImgProfile='".$url."' WHERE chat_id=".$chat_id;
                $result = mysqli_query($link, $sql);
                $this->DBDisconnect($link);
                return $result;
            }
        protected function GetOneUser($field,$variable){
            $link   =   $this->DBConnect();
            $sql    =   "SELECT * FROM users WHERE ".$field."='".$variable."' LIMIT 1";
            $result =   mysqli_fetch_assoc( $result = mysqli_query($link, $sql) );
            $this->DBDisconnect( $link );
            return $result;}
        protected function GetRandUserAnkets($field=null,$variable=null)
            {
            if($field){ $sql_params = 'WHERE '.$field.'='.$variable; }else{ $sql_params = '';}
            $link   =   $this->DBConnect();
            $sql    =   "SELECT * FROM users ".$sql_params." ORDER BY RAND() LIMIT 1";
            $User =   mysqli_fetch_assoc( $User = mysqli_query($link, $sql) );
            $this->DBDisconnect( $link );
            return $User;}
        protected function GetTestUserIs($chat_id)
            {
            $link   =   $this->DBConnect();
            $sql = "SELECT * FROM users WHERE chat_id = ".$chat_id;
            $User = mysqli_query($link, $sql);
            $result = mysqli_num_rows($User);
            $this->DBDisconnect( $link );
            return $result;}
        protected function GetUserRegStatus($chat_id)
            {
            $link   =   $this->DBConnect();
            $sql    =   "SELECT * FROM users WHERE chat_id=".$chat_id." LIMIT 1";
            $result =   mysqli_fetch_assoc( $result = mysqli_query($link, $sql) );
            $this->DBDisconnect( $link );
            return $result['Status_reg'];}
        protected function GetUserEditStatus($chat_id)
            {
            $link   =   $this->DBConnect();
            $sql    =   "SELECT * FROM users WHERE chat_id=".$chat_id." LIMIT 1";
            $result =   mysqli_fetch_assoc( $result = mysqli_query($link, $sql) );
            $this->DBDisconnect( $link );
            return $result['Status_ed'];}
        protected function GetStatus_ed($chat_id)
            {
                $User = $this->GetOneUser('chat_id',$chat_id);
                return $User['Status_ed'];
            }
        protected function GetNextUser()
            {
                $link   =   $this->DBConnect();
                $sql    =   "SELECT * FROM users ORDER BY RAND() LIMIT 1";
                $User =   mysqli_fetch_assoc( $User = mysqli_query($link, $sql) );
                return $User;
            }
        protected function SendAdmin($data)
            {
                $link   =       $this->DBConnect();
                $chat_id =      $data['message']['chat']['id'];
                if($chat_id==0){return false;}
                $username =     $data['message']['chat']['username'];
                $text =         $data['message']['text'];
                ////////////////////////////////////////////
                $sql = "INSERT INTO questions (from_chat_id,username,text) 
                VALUES ($chat_id,'$username','$text')";
                ////////////////////////////////////////////
                $result = mysqli_query($link, $sql);
                $this->DBDisconnect( $link );
                return $sql;
            }
}

class TeleBot extends DataBase
{
    private             $botToken = '5026319207:AAE77UW_jQZQLeP2GywHKegwkZgvAj1_UgU';
    private             $apiUrl =   "https://api.telegram.org/bot";
    private             $method=    'sendMessage';
        public function  sendMessage($chat_id, $text)
            {
            $this->requestToTelegram(['chat_id' => $chat_id,'text' => $text,], $this->method);
            }       
        public function  sendMessageEnd($data, $headers = [])
            {
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_POST => 1,
                    CURLOPT_HEADER => 0,
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => 'https://api.telegram.org/bot' . $this->botToken . '/' .$this->method,
                    CURLOPT_POSTFIELDS => json_encode($data),
                    CURLOPT_HTTPHEADER => array_merge(array("Content-Type: application/json"))
                ]);
                $result = curl_exec($curl);
                curl_close($curl);
                return (json_decode($result, 1) ? json_decode($result, 1) : $result);
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
        public  function TestRegisterUserData($chat_id)
            {
                $User = $this->GetOneUser('chat_id',$chat_id);
                if($User['Name']==NULL)          {return 'Name';}
                if($User['City']==NULL)          {return 'City';}
                if($User['Busines']==NULL)       {return 'Busines';}
                if($User['AboutSelf']==NULL)     {return 'AboutSelf';}
                if($User['WhoSearch']==NULL)     {return 'WhoSearch';}
                if($User['ImgProfile']==NULL)    {return 'ImgProfile';}
                return 'close';
            }         
        public  function ShowRegisterUpdateQuest($Status_reg,$chat_id)
            {
                switch($Status_reg)
                {
                    case 'Name':
                        $send_data = $this->Request_Name();
                    break;
                    case 'City':
                        $User = $this->GetOneUser('chat_id',$chat_id);
                        $send_data = $this->Request_City($User['Name']);
                    break;
                    case 'Busines':
                        $send_data = $this->Request_Busines();
                    break;
                    case 'AboutSelf':
                        $send_data = $this->Request_AboutSelf();
                    break;
                    case 'WhoSearch':
                        $send_data = $this->Request_WhoSearch();
                    break;
                    case 'ImgProfile':
                        $send_data = $this->Request_ImgProfile();
                    break;
                }    
                return $send_data;
            }
        private function Request_Name()
            {
                $send_data = 
                [
                    'text'=> "*<Для>* использования бота необходимо ответить на несколько вопросов. Ваши данные не будут использованы для рекламной рассылки организаторами конференции и её участниками. Бот обеспечивает анонимность и использовать ваши контакты в корыстных целях не получится.\n\r\n\r *Введите свое Имя:*",
                    'reply_markup'=>
                    [
                        'resize_keyboard' => true, 
                        'keyboard' =>   
                        [
                            [
                                ['text' => '<Нет, мне не интересно!>'],
                            ]
                        ]
                    ],
                    'parse_mode' => 'markdown'
                ];
            return $send_data;}                      
        private function Request_City($UserName)
            {
            $send_data = 
            [
            'text' => "*<Здравствуйте>*,".$UserName."! Напишите из какого Вы города?",
            'reply_markup'=>['remove_keyboard'=>true],'parse_mode' => 'markdown'
            ];
            return $send_data;}        
        private function Request_Busines()
            {
            $send_data = 
            [
                'text'=> "*<Классный город!>* А кем Вы являетесь? Выберите один из вариантов.",
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
                ],
                'parse_mode' => 'markdown'
            ];
            return $send_data;}                    
        private function Request_AboutSelf()
            {
            $send_data = 
            [
                'text'=> "*<А теперь...>* напишите какими навыками и компетенциями вы обладаете! Укажите кратко ваши самые сильные стороны и достижения.",
                'reply_markup'=>['remove_keyboard'=>true],'parse_mode' => 'markdown'
            ];
            return $send_data;}                    
        private function Request_WhoSearch()
            {
            $send_data = 
            [
                'text'=> "*<Здорово!>* А кого бы вы хотели найти на конференции?",
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
                    'parse_mode' => 'markdown'
            ];
            return $send_data;}              
        private function Request_ImgProfile()
            {
            $send_data = 
            [
                'text'=>"*<Круто!>* Пришлите Вашу фотографию на аватар профиля в боте",
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
                'reply_markup'=>['remove_keyboard'=>true],'parse_mode' => 'markdown'
            ];
            return $send_data;}        
        private function GoOut()
            {
                $send_data = 
                [
                    'text'=>"<Приходите Еще!>",
                    'reply_markup'=>
                    [
                        'resize_keyboard' => true, 
                        'keyboard' =>   
                        [
                            [
                                ['text' => '<Я передумал!>'],
                            ]
                        ]
                    ]                    
                ];
                return $send_data;
            }
        private function UserPanel($chat_id)
            {
            $User= $this->GetOneUser('chat_id',$chat_id);
            $send_data = 
            [
                'text'=> "*<".$User['Name']."!>* Добро пожаловать на 💙 международную нетворкинг платформу *OPENATOR!* Здесь Вы найдете тех, кто поможет Вам перейти на новый уровень!",
                'reply_markup'=>
                [
                    'resize_keyboard' => true, 
                    'keyboard' =>   
                    [
                        [
                            ['text' => '<OPENATOR Marketing Forum>',"url"=>"http://google.com"],
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
                    ],
                'parse_mode' => 'markdown'
            ];
            return $send_data;}
        
        public  function Registration($data)   /// Возвращает Status_reg
            {
                $this->CallInlineBooton($data);
                $message = mb_convert_encoding($data['message']['text'], "UTF-8");
                $chat_id = $data['message']['chat']['id'];
                //Если пользователя нет в базе
                if($this->GetTestUserIs($chat_id)==0)
                {   
                    if($this->CreateUser($data)==false) {
                        $this->sendMessage($chat_id, '<Пользователь не создан, попробуйте позже!>');
                        exit;
                    }else{
                        $this->sendMessage($chat_id, '<Привет! Привет!  Вы приняты!>');
                        $Status_reg = $this->GetUserRegStatus($chat_id);
                    }
                } 
                    //Если пользователь уже есть            
                if($this->GetTestUserIs($chat_id)==1)
                {   
                    if($message[0]<>'/' AND $message[0]<>'<' AND $message[0]<>'{')
                    {
                        if (array_key_exists('photo', $data['message'])) {
                            $this->SaveUserPhoto($data); 
                        } 

                        $Status_reg = $this->TestRegisterUserData($chat_id);
                        if($Status_reg <>'close')
                        {
                            $this->UpdateUser($Status_reg,$data);
                        }
                    }
                    //Смотрим какой у него статус и какие поля не заполнены
                    $Status_reg = $this->TestRegisterUserData($chat_id);        
                    //Отправляем Пользователя на заполнение недостающих полей
                    $send_data = $this->ShowRegisterUpdateQuest($Status_reg,$chat_id); //Вопросы для регистрации
                    switch($message)
                    {
                        case '<Нет, мне не интересно!>':
                            $this->DeleteUser($data);
                            $send_data = $this->GoOut();
                        break;
                        case '<Я Передумал!>':
                            $send_data = ['text'=>'/start'];    
                        break;    
                    }
                    if($Status_reg<>'close')
                    {
                        $send_data['chat_id'] = $chat_id; $this->sendMessageEnd($send_data); ///SEND Message
                    }    
                } 
                return $this->TestRegisterUserData($chat_id);
            }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////        
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////        
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////        
        public function ConnetcWithManager()
            {
                return ['text'=>'Напишите нам прямо сюда в этот чат, и мы с вами свяжемся!'];
            }
        public function MarketingPforum()
            {
                return 
                ['text'=>'Вы можете посетить наш сайт, нажмите на кнопку для перехода',
                'reply_markup' => json_encode(array('inline_keyboard' => $this->GotoUrl()))];
            }
        public function FormEditProfile($chat_id)
            {
                $User = $this->GetOneUser('chat_id',$chat_id);
                $send_data = 
                [
                    'text'=>
                    "".$this->ImgUrl.$User['ImgProfile']."\n\r *Имя:* ".$User['Name']."\n\r *Город:* ".$User['City']."\n\r *Занятие:* ".$User['Busines']."\n\r *Компетенция:* ".$User['AboutSelf']."\n\r *Ищу:* ".$User['WhoSearch'].
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
        public function TimeLineShow()
            {
                $result = ['text'=>"Регистрация 9:00-10:00\r\nОткрытие 10:00-10:30\r\n\r\nГрибов 10:30-11:15\r\nУшенин 11:15-11:45\r\nБермуда 11:45-12:45\r\n\r\nКофе-брейк 12:45-13:15\r\n\r\nСташкевич 13:15-14:00\r\nВоловик 14:00-14:45\r\nКорс 14:45-15:30\r\nВоронин 15:30-16:30\r\n\r\nОбеденный перерыв 16:30-17:30\r\n\r\nАлексеев 17:30-18:15\r\nЕфремов 18:15-19:00\r\nЗакрытие 19:00-19:30\r\n"];
                return $result;
            }
        public function NetworkingShow($my_chat_id)
            {
                $MySelf = $this->GetOneUser('chat_id',$my_chat_id);
                $My_username = $MySelf['username']; 
                $User = $this->GetNextUser();                
                $send_data = 
                [
                    'text'=> 
                    $this->ImgUrl.$User['ImgProfile']."\n\r".
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
                    'reply_markup' => json_encode(array('inline_keyboard' => $this->InlineConnect($User['chat_id'],$My_username))),
                    'parse_mode' => 'markdown'
                ];
                return $send_data;
            }
///////////////////////////////////////////////////////////////////////////////////////////////////////////
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
///////////////////////////////////////////////////////////////////////////////////////////////////////////
        private function SaveUserPhoto($data) 
            {
                $Photo = $data['message']['photo'];
                $chat_id = $data['message']['chat']['id'];
                $file_id = $Photo[count($Photo) - 2]['file_id'];
                $array = json_decode($this->requestToTelegram(['file_id' => $file_id], "getFile"), TRUE);
                $file_path = $array['result']['file_path'];
                $User = $this->GetOneUser('chat_id',$chat_id);   $OldFilename = $User['ImgProfile'];
                if (file_exists($OldFilename)) {unlink($OldFilename);}
                $file_from_tgrm = "https://api.telegram.org/file/bot".$this->botToken."/".$file_path;
                $ext =  end(explode(".", $file_path));
                $name_our_new_file = time().".".$ext;
                $this->SaveUserProfileFoto($chat_id,$name_our_new_file);
                $re = Array();
                $re['copy'] = copy($file_from_tgrm, "img/".$name_our_new_file);
                $re['name'] = $name_our_new_file;
                $re['boolean'] = true;
                return $re;
            }


///////////////////////////////////////////////////////////////////////////////////////////////////////////
        protected function GotoUrl()
            {
                 //'reply_markup' => json_encode(array('inline_keyboard' => $keyboard))
                //'reply_markup' => json_encode(array('inline_keyboard' => $this->InlineKeyBoard())),
                $keyboard = array(
                    array(
                    array('text'=>'Перейти на наш сайт',"url"=>"google.com")
                    )
                );
                return $keyboard;
            }            
        protected function InlineConnect($chat_id,$my_username)
            {
                //'reply_markup' => json_encode(array('inline_keyboard' => $keyboard))
                //'reply_markup' => json_encode(array('inline_keyboard' => $this->InlineKeyBoard())),
                //                array('text'=>'test',"callback_data"=>'/test')
                $keyboard = array(
                    array(
                    array('text'=>'Связаться!','callback_data'=>'/connect*'.$chat_id.'*'.'@'.$my_username)
                    )
                );
                return $keyboard;
            }    
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////
        protected function CallInlineBooton($data)
            {
                ///////////////////////
                $callback_query = $data['callback_query'];
                $call = $callback_query['data'];
                $chat_idd = $callback_query['message']['chat']['id'];
                $call = $callback_query['data'];
                $pieces = explode("*", $call);
                switch($pieces[0]){
                    case $pieces[0]:
                    $this->sendMessage($chat_idd, $pieces[0]);
                    $this->sendMessage($pieces[1], 'Этот пользователь приглашает вас к общению... надо бы связаться - '.$pieces[2]);
                    $this->sendMessage($chat_idd, 'Запрос на общение отправлен. Если пользователь захочет, он вам напишет в личку.');
                    $this->sendMessage($chat_idd, $pieces[2]);
                    break;
    
                }                      
                $this->sendMessage($chat_idd, $chat_idd);
                ///////////////////////       
                return $data;         
            }
///////////////////////////////////////////////////////////////////////////////////////////////////////////
        public function WorkIngBot($data)
            {
                $message = mb_convert_encoding($data['message']['text'], "UTF-8");
                $chat_id = $data['message']['chat']['id'];
                ///////////////////////////////////////////////////////////////////////
                $Status_ed = $this->GetStatus_ed($chat_id);

                if($message[0] <> '<' AND $message[0] <> '/') //mb_substr($myString, 0, 1)
                {
                    if($Status_ed=='Name')       {$this->UpdateUser('Name',     $data); $this->UpdateStatus_ed($chat_id,'close');}
                    if($Status_ed=='AboutSelf')  {$this->UpdateUser('AboutSelf',$data); $this->UpdateStatus_ed($chat_id,'close');}
                    if($Status_ed=='WhoSearch')  {$this->UpdateUser('WhoSearch',$data); $this->UpdateStatus_ed($chat_id,'close');}                    
                    if($Status_ed=='SendAdmin')  {$this->SendAdmin($data); $this->sendMessage($chat_id, 'Ваше сообщение принято!');  $this->UpdateStatus_ed($chat_id,'close');}                    
                    if($Status_ed=='ImgProfile') 
                    {
                        if (array_key_exists('photo', $data['message'])) {
                                                  $this->SaveUserPhoto($data);          $this->UpdateStatus_ed($chat_id,'close');
                        } else {$this->sendMessage($chat_id,'Это не фотография!');}
                    }
                }                  
                switch($message)
                {
                    case '<Связаться с клиентским менеджером>': $send_data = $this->ConnetcWithManager(); $this->UpdateStatus_ed($chat_id,'SendAdmin'); break;
                    case '<Правила нетворкинга>':               $send_data = $this->TimeLineShow();                 break;
                        ///////////////////////////////////////////////////////////////////////////////////////////////
                    case '<Профиль>':                           $send_data = $this->FormEditProfile($chat_id);      break;
                        case '<Имя!>':                          $send_data = $this->EditNameForm();       $this->UpdateStatus_ed($chat_id,'Name');       break;
                        case '<Компетенция!>':                  $send_data = $this->EditAboutSelfForm();  $this->UpdateStatus_ed($chat_id,'AboutSelf');  break;
                        case '<Запрос к аудитории!>':           $send_data = $this->EditRequestAudForm(); $this->UpdateStatus_ed($chat_id,'WhoSearch');  break;
                        case '<Фотография!>':                   $send_data = $this->EditPhotoForm();      $this->UpdateStatus_ed($chat_id,'ImgProfile'); break;
                        case '<Не буду пока ничего менять!>':   $send_data = $this->UserPanel($chat_id);            break;
                        ///////////////////////////////////////////////////////////////////////////////////////////////    
                    case '<Нетворкинг>':                        $send_data = $this->NetworkingShow($chat_id);     $this->UpdateStatus_ed($chat_id,'NetSearch'); break;
                        case '<Связаться>':                     $send_data = ['text'=>'Связаться с ...'];           break;
                        case '<Следующий>':                     $send_data = $this->NetworkingShow();               break;
                        case '<Выйти>':                         $send_data = $this->UserPanel($chat_id);            break;
                        ///////////////////////////////////////////////////////////////////////////////////////////////    
                    case '<OPENATOR Marketing Forum>':             $send_data = $this->MarketingPforum();              break;
                    default :                                   $send_data = $this->UserPanel($chat_id);            break;        
                }
                $send_data['chat_id'] = $chat_id; $this->sendMessageEnd($send_data); ///SEND Message 
            }
}


