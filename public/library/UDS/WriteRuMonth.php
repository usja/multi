<?php
/*
 *  Хэлпер для отображдения месяца прописью
 *
 */
    class UDS_WriteRuMonth extends Zend_Controller_Plugin_Abstract {
        public function answer($month)
        {
         $month = intval($month);
          switch ($month){
                case 1: $result = 'января'; break;
                case 2: $result = 'февраля'; break;
                case 3: $result = 'марта'; break;
                case 4: $result = 'апреля'; break;
                case 5: $result = 'мая'; break;
                case 6: $result = 'июня'; break;
                case 7: $result = 'июля'; break;
                case 8: $result = 'августа'; break;
                case 9: $result = 'сентября'; break;
                case 10: $result = 'октября'; break;
                case 11: $result = 'ноября'; break;
                case 12: $result = 'декабря'; break;
                }
             
            return $result;
        }

        public function plural_form($n, $forms)
        {
               return $n%10==1&&$n%100!=11?$forms[0]:($n%10>=2&&$n%10<=4&&($n%100<10||$n%100>=20)?$forms[1]:$forms[2]);
        }
        public function SmallDate($month)
        {

                switch ($month){
                case 01: $result = 'янв'; break;
                case 02: $result = 'фев'; break;
                case 03: $result = 'мар'; break;
                case 04: $result = 'апр'; break;
                case 05: $result = 'мая'; break;
                case 06: $result = 'июн'; break;
                case 07: $result = 'июл'; break;
                case 08: $result = 'авг'; break;
                case 09: $result = 'сен'; break;
                case 10: $result = 'окт'; break;
                case 11: $result = 'ноя'; break;
                case 12: $result = 'дек'; break;
                }
            return $result;
        }
    }

?>
