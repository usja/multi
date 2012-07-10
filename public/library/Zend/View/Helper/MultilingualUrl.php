<?php

class Zend_View_Helper_MultilingualUrl extends Zend_Controller_Action_Helper_Url {
    /**
     * URI delimiter
     */

    const URI_DELIMITER = '/';

    /**
     * Language key
     * @var string
     */
    protected $_languageKey = 'lang';

    public function multilingualUrl($urlOptions = array(), $name = null, $reset = false, $encode = true) {
        return $this->url($urlOptions, $name, $reset, $encode);
    }

    /**
     * Assembles a URL based on a given route
     *
     * This method will typically be used for more complex operations, as it
     * ties into the route objects registered with the router.
     *
     * @param  array   $urlOptions Options passed to the assemble method of the Route object.
     * @param  mixed   $name       The name of a Route to use. If null it will use the current Route
     * @param  boolean $reset
     * @param  boolean $encode
     * @return string Url for the link href attribute.
     */
    public function url($urlOptions = array(), $name = null, $reset = false, $encode = true) {
        $config = new Zend_Config_Ini(
                        APPLICATION_PATH . '/configs/application.ini',
                        'production');
        if (@$config->languages->langKey) {
            $this->_languageKey = $config->languages->langKey;
        }

// если не существует в переданных параметрах ключа языка, значит нужно
// его сформировать либо из входящего url, либо из дефолтного
        if (!array_key_exists($this->_languageKey, $urlOptions)) {
            $path = $this->getFrontController()->getRequest()->getPathInfo();
            $path = trim($path, self::URI_DELIMITER);
            $aPath = explode(self::URI_DELIMITER, $path);
// язык по умолчанию
            $language = $config->languages->default;
// язык из урл

            if (strlen($aPath[0]) == 2 && $aPath[0] != $language) {
                $language = $aPath[0];
            }
            $urlOptions = array($this->_languageKey => $language) + $urlOptions;
        }
        $router = $this->getFrontController()->getRouter();
        return $router->assemble($urlOptions, $name, $reset, $encode);
    }

}