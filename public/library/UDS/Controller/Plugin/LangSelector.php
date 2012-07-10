<?php

class UDS_Controller_Plugin_LangSelector extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $lang = $request->getParam('lang', '');

        try {
            $locale = new Zend_Locale(Zend_Locale::BROWSER);
        } catch (Exception $e) {
            $locale = new Zend_Locale(Zend_Locale);
        }
        /*         * ********************** */
        if ($lang == 'ru')
            $loc    = 'ru_RU';
        else if ($lang == 'en')
            $loc    = 'en_EN';
        else if ($lang == 'ua')
            $loc    = 'uk_UA';
        else {
            $lang = 'ua';
            $loc  = 'uk_UA';
        }
        /*         * ********************** */

        $locale->setLocale($lang);

        Zend_Registry::set('Zend_Locale', $loc);


        try {
            $translate = new Zend_Translate(
                            array(
                                'adapter' => 'gettext',
                                'content' => APPLICATION_PATH . '/configs/lang/' . $lang . '.mo',
                                'locale'  => $lang
                            )
            );
        } catch (Exception $e) {
            $translate = new Zend_Translate(array(
                        'adapter' => 'gettext',
                        'content' => APPLICATION_PATH . '/configs/lang/ru.mo',
                        'locale'  => $lang
                            )
            );
        }

        Zend_Registry::set('Zend_Translate', $translate);
        Zend_Registry::set('uds_lang', $lang);
        $langm   = new Admin_Model_LanguageMapper();
        $lang_id = $langm->getLanguageId();
        Zend_Registry::set('uds_lang_id', $lang_id);
    }

}