<?php
    class MyBot 
    {
            private     $botName =          '@md246bot';
            protected   $botToken =       '1238564789:AAF1kydnaZ_ZWXlBrCXVyKC5RVeOLynCMvg';
            //method sendMessage
        public function  GetBotName(){return $this->botName;}
        public function  GetBotToken(){return $this->botToken;}
        public function  SetBotName($bName){$this->botName = $bName;}
        public function  SetBotToken($bToken){$this->botName = $bToken;}
    
    private function getPhoto($data)// общая функция загрузки картинки
    {
        $file_id = $data[count($data) - 1]['file_id'];     	// берем последнюю картинку в массиве
        $file_path = $this->getPhotoPath($file_id);         // получаем file_path
        return $this->copyPhoto($file_path);                // возвращаем результат загрузки фото
    }

    private function getPhotoPath($file_id)                 // функция получения метонахождения файла
    {
        $array = json_decode($this->requestToTelegram(['file_id' => $file_id], "getFile"), TRUE);     	// получаем объект File
        return  $array['result']['file_path'];              // возвращаем file_path
    }

    private function copyPhoto($file_path)              // копируем фото к себе
    {
        $file_from_tgrm = "https://api.telegram.org/file/bot".$this->botToken."/".$file_path;    	// ссылка на файл в телеграме
        $ext =  end(explode(".", $file_path));                                                      // достаем расширение файла
        $name_our_new_file = time().".".$ext;                                                       // назначаем свое имя здесь время_в_секундах.расширение_файла
        return copy($file_from_tgrm, "img/".$name_our_new_file);
    }

        public function GetUpdates($data)
        {
            $data = $data['callback_query'] ? $data['callback_query'] : $data['message'];
            $message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');
            return $message;
        }

        public function GetImage($data)
        {
            $text = $this->getPhoto($data['message']['photo'])
                ? "Спасибо! Можете еще загрузить мне понравилось их сохранять."
                : "Что-то пошло не так, попробуйте еще раз";
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

        public function SendMessage($method, $data, $headers = []){
            $TOKEN = $this->GetBotToken();
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_POST => 1,
                CURLOPT_HEADER => 0,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'https://api.telegram.org/bot' . $TOKEN . '/' . $method,
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => array_merge(array("Content-Type: application/json"))
            ]);
            $result = curl_exec($curl);
            curl_close($curl);
            return (json_decode($result, 1) ? json_decode($result, 1) : $result);
        }
    }