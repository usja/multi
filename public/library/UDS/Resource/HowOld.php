<?php
/*
 *  Хэлпер для отображдения когда последний раз было событие
 *  @param $last_day formay Y-m-d
 *
 */
    class UDS_HowOld extends Zend_Controller_Plugin_Abstract {

        protected $last_day;
        
        public function answer($last_day)
        {
            
                $d1 = strtotime($last_day);
                $d2 = strtotime(date("Y-m-d"));
                $days = ((($d2-$d1)/(24*60*60)));

                if ($days == 0)
                {
                        $result = "сегодня";
                }
                else if ($days == 1)
                {
                        $result = "вчера";
                }
                else if ($days == 2)
                {
                        $result = "позавчера";
                }
                else if (( $days > 2 )&& ( $days < 31 ))
                {
                        $result = $days. ' '. UDS_WriteRuMonth::plural_form($days, array('день', 'дня', 'дней' )). ' назад';
                }

            return $result;
        }
    }

?>
