<?php

    class UDS_Additional extends Zend_Controller_Plugin_Abstract {

        protected $str;
        protected $folder;
        protected $ext;
        
        /*
         * Генератор пароля
         *
         */
        public function GenerateRandomPassword($length=5){
              $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
              $numChars = strlen($chars);
              $string = '';
              for ($i = 0; $i < $length; $i++) {
                $string .= substr($chars, rand(1, $numChars) - 1, 1);
              }
              return $string;
        }
        /*
         * Когда был последний раз написан пост
         */
        public function WhenPostWasWritten($post_date)
        {
                $d1 = strtotime($post_date);
                $d2 = strtotime(date("Y-m-d H:m:s"));
                $hours = round((($d2-$d1)/(60*60)));
               // $date = new Zend_Date ($post_date, Zend_Date::ISO_8601);
                $date = new Zend_Date($post_date);
            

                if ($date->isYesterday()) $hours = 47;
                   
                if ($hours <= 1)
                {
                   /*     if ($minutes < 60)
                        {
                             
                                if ($minutes < 1){$minutes = 1;}
                                $forms = array('минуту','минуты', 'минут');
                                $result = $minutes.' '.UDS_WriteRuMonth::plural_form($minutes, $forms).' назад';
                        }
                        else{*/
                                $result = "час назад";
                        //}
                }
                else if ($hours <= 2)
                {
                        $result = "2 часа назад";
                }
                else if ($hours <= 3)
                {
                        $result = "3 часа назад";
                }
                else if (( $hours >= 3 )&& ( $hours < 24 ))
                {
                        $result = "сегодня";
                }
                else if (( $hours >= 24 )&& ( $hours < 48 ))
                {
                        $result = "вчера";
                }
                else
                {
                        $str_t   = strtotime ($post_date);
                        $result  = date("d ", $str_t);
                        $result .= UDS_WriteRuMonth::answer(date("m", $str_t));
                        if (date("Y") != date("Y", $str_t))
                        {
                                $result .= date(" Y", $str_t);
                        }
                        $result .= date(" в H:m", $str_t);
                }

            return $result;
        }
        /*
         * Хэлпер для получения расширения файла
         *
         */
        public function extension($str)
        {
            $arr = array();
            $arr = explode ('.', $str);
            $count = count($arr);
            if ( $count < 1 )
            {
                    return False;
            }
            return $arr[$count-1];
        }
        /*/
         * Получаем Имя файла для загрузки
         */
        public function generateRandom($str, $folder, $ext)
        {
                $str1 = intval (md5 ($str . $folder . $ext));
                $str2 = intval (md5 (rand(2, 321221)) ) ;
                $str3 = time();
                $name = $str3+$str2+$str1;
                return (file_exists($folder.$name.'.'.$ext)) ? $this->generateRandom($folder, $ext, $name): $name;

        }
        /*
         * Транслит
         */
        public function RusToLat($str)
        {
                $str= trim($str);
                $str= strip_tags($str);
                $str = str_replace(' ','-',$str);
                 $str = str_replace('.','',$str);
                 $str = str_replace(',','',$str);
                $str = str_replace('«','',$str);
                $str = str_replace('»','',$str);
                $str = str_replace('“','',$str);
                $str = str_replace('”','',$str);
                $str = str_replace("
","",$str);
                $str = str_replace("–","-",$str);
                $str = str_replace("\r","_",$str);
                $str = str_replace(":","_",$str);
                $str = str_replace("(","_",$str);
                $str = str_replace(")","_",$str);
                $str = str_replace("%","",$str);
                $str = str_replace("!","",$str);
                $str = str_replace("№","",$str);
                $str = str_replace(";","",$str);
                $str = str_replace("@","",$str);
                $str = str_replace("#","",$str);
                $str = str_replace("^","",$str);
                $str = str_replace("*","",$str);
                $str = str_replace("
","_",$str);
                $str = str_replace("—","-",$str);
                $str = str_replace("«","",$str);
                $str = str_replace("»","",$str);
                $str = str_replace("§","",$str);
                $str = str_replace("\n","_",$str);
                $str = str_replace("\r\n","",$str);
                 $str = str_replace(",","",$str);
                 $str = str_replace("+","",$str);
                 $str = str_replace('*','',$str);
                 $str = str_replace('/','',$str);
                 $str = str_replace('"','',$str);
                 $str = str_replace("'",'',$str);
                 $str = str_replace('?','',$str);
                 $str = str_replace('<','',$str);
                 $str = str_replace('>','',$str);
                 $str = (string) $str;
                 $str = str_replace('й','j',$str);
                 $str = str_replace('ц','ts',$str);
                 $str = str_replace('у','u',$str);
                 $str = str_replace('к','k',$str);
                 $str = str_replace('е','e',$str);
                 $str = str_replace('ё','e',$str);
                 $str = str_replace('Ё','e',$str);
                 $str = str_replace('н','n',$str);
                 $str = str_replace('г','g',$str);
                 $str = str_replace('ш','sh',$str);
                 $str = str_replace('щ','sch',$str);
                 $str = str_replace('з','z',$str);
                 $str = str_replace('х','h',$str);
                 $str = str_replace('ъ','',$str);
                 $str = str_replace('ф','f',$str);
                 $str = str_replace('ы','y',$str);
                 $str = str_replace('в','v',$str);
                 $str = str_replace('а','a',$str);
                 $str = str_replace('п','p',$str);
                 $str = str_replace('р','r',$str);
                 $str = str_replace('о','o',$str);
                 $str = str_replace('л','l',$str);
                 $str = str_replace('д','d',$str);
                 $str = str_replace('ж','zh',$str);
                 $str = str_replace('э','e',$str);
                 $str = str_replace('є','e',$str);
                 $str = str_replace('Є','E',$str);
                 $str = str_replace('ї','i',$str);
                 $str = str_replace('Ї','I',$str);
                 $str = str_replace('я','ja',$str);
                 $str = str_replace('ч','ch',$str);
                 $str = str_replace('с','s',$str);
                 $str = str_replace('м','m',$str);
                 $str = str_replace('и','i',$str);
                 $str = str_replace('т','t',$str);
                 $str = str_replace('ь','',$str);
                 $str = str_replace('б','b',$str);
                 $str = str_replace('ю','ju',$str);
                 $str = str_replace('Й','J',$str);
                 $str = str_replace('Ц','TS',$str);
                 $str = str_replace('У','U',$str);
                 $str = str_replace('К','K',$str);
                 $str = str_replace('Е','E',$str);
                 $str = str_replace('Н','N',$str);
                 $str = str_replace('Г','G',$str);
                 $str = str_replace('Ш','SH',$str);
                 $str = str_replace('Щ','SCH',$str);
                 $str = str_replace('З','Z',$str);
                 $str = str_replace('Х','h',$str);
                 $str = str_replace('Ъ','',$str);
                 $str = str_replace('Ф','F',$str);
                 $str = str_replace('Ы','Y',$str);
                 $str = str_replace('В','V',$str);
                 $str = str_replace('А','A',$str);
                 $str = str_replace('П','P',$str);
                 $str = str_replace('Р','R',$str);
                 $str = str_replace('О','O',$str);
                 $str = str_replace('Л','L',$str);
                 $str = str_replace('Д','D',$str);
                 $str = str_replace('Ж','ZH',$str);
                 $str = str_replace('Э','E',$str);
                 $str = str_replace('Я','JA',$str);
                 $str = str_replace('Ч','CH',$str);
                 $str = str_replace('С','S',$str);
                 $str = str_replace('М','M',$str);
                 $str = str_replace('И','I',$str);
                 $str = str_replace('Т','T',$str);
                 $str = str_replace('Ь','',$str);
                 $str = str_replace('Б','B',$str);
                 $str = str_replace('Ю','JU',$str);
                 $str = str_replace('і','i',$str);
                 $str = str_replace('І','I',$str);
                 $str = str_replace('Ї','I',$str);
                 $str = str_replace('ї','ї',$str);
                 $str = preg_replace('/\s+$/m', '', $str);
                 $str = str_replace('
                 ','',$str);
                 return $str;
        }
        public function WhenPostWasWrittenDay($post_date)
        {
            
                $day = date("w", strtotime($post_date));
                $ar[0] = "воскресенье";
                $ar[1] = "понедельник";
                $ar[2] = "вторник";
                $ar[3] = "среда";
                $ar[4] = "четверг";
                $ar[5] = "пятница";
                $ar[6] = "суббота";

                return $ar[$day];
        }
        
                public function WhenPostWasWrittenDayS($post_date)
        {
            
                $day = date("w", strtotime($post_date));
                $ar[0] = "в воскресенье";
                $ar[1] = "в понедельник";
                $ar[2] = "во вторник";
                $ar[3] = "в среду";
                $ar[4] = "в четверг";
                $ar[5] = "в пятницу";
                $ar[6] = "в субботу";
                return $ar[$day];
        }
        
    }

?>
