<?php

if (!function_exists('sha_256')) {

    function sha_256($string) {
        $string = hash('sha256', $string);
        return $string;
    }

}

function winToUtf8($s) {
    // перекодировка из win в utf-8

    static $table = array
(
"\xC0" => "\xD0\x90", "\xC1" => "\xD0\x91", "\xC2" => "\xD0\x92", "\xC3" => "\xD0\x93", "\xC4" => "\xD0\x94",
 "\xC5" => "\xD0\x95", "\xA8" => "\xD0\x81", "\xC6" => "\xD0\x96", "\xC7" => "\xD0\x97", "\xC8" => "\xD0\x98",
 "\xC9" => "\xD0\x99", "\xCA" => "\xD0\x9A", "\xCB" => "\xD0\x9B", "\xCC" => "\xD0\x9C", "\xCD" => "\xD0\x9D",
 "\xCE" => "\xD0\x9E", "\xCF" => "\xD0\x9F", "\xD0" => "\xD0\xA0", "\xD1" => "\xD0\xA1", "\xD2" => "\xD0\xA2",
 "\xD3" => "\xD0\xA3", "\xD4" => "\xD0\xA4", "\xD5" => "\xD0\xA5", "\xD6" => "\xD0\xA6", "\xD7" => "\xD0\xA7",
 "\xD8" => "\xD0\xA8", "\xD9" => "\xD0\xA9", "\xDA" => "\xD0\xAA", "\xDB" => "\xD0\xAB", "\xDC" => "\xD0\xAC",
 "\xDD" => "\xD0\xAD", "\xDE" => "\xD0\xAE", "\xDF" => "\xD0\xAF", "\xAF" => "\xD0\x87", "\xB2" => "\xD0\x86",
 "\xAA" => "\xD0\x84", "\xA1" => "\xD0\x8E", "\xE0" => "\xD0\xB0", "\xE1" => "\xD0\xB1", "\xE2" => "\xD0\xB2",
 "\xE3" => "\xD0\xB3", "\xE4" => "\xD0\xB4", "\xE5" => "\xD0\xB5", "\xB8" => "\xD1\x91", "\xE6" => "\xD0\xB6",
 "\xE7" => "\xD0\xB7", "\xE8" => "\xD0\xB8", "\xE9" => "\xD0\xB9", "\xEA" => "\xD0\xBA", "\xEB" => "\xD0\xBB",
 "\xEC" => "\xD0\xBC", "\xED" => "\xD0\xBD", "\xEE" => "\xD0\xBE", "\xEF" => "\xD0\xBF", "\xF0" => "\xD1\x80",
 "\xF1" => "\xD1\x81", "\xF2" => "\xD1\x82", "\xF3" => "\xD1\x83", "\xF4" => "\xD1\x84", "\xF5" => "\xD1\x85",
 "\xF6" => "\xD1\x86", "\xF7" => "\xD1\x87", "\xF8" => "\xD1\x88", "\xF9" => "\xD1\x89", "\xFA" => "\xD1\x8A",
 "\xFB" => "\xD1\x8B", "\xFC" => "\xD1\x8C", "\xFD" => "\xD1\x8D", "\xFE" => "\xD1\x8E", "\xFF" => "\xD1\x8F",
 "\xB3" => "\xD1\x96", "\xBF" => "\xD1\x97", "\xBA" => "\xD1\x94", "\xA2" => "\xD1\x9E", "\®"   => "\®"
    );

    return strtr($s, $table);
}

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
    /*     * *********************** */

    public function _initConfig() {
        Zend_Registry::set('constants', new Zend_Config_Ini(
                        APPLICATION_PATH . '/configs/configs.ini',
                        'constants')
        );
    }

    /*     * *********************** */

    protected function _initAutoload() {
        $moduleLoader = new Zend_Application_Module_Autoloader(array(
                    'basePath'  => APPLICATION_PATH . '/',
                    'namespace' => '',
                ));
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('UDS_');
        return $moduleLoader;
    }

    /*     * *********************** */

    protected function _initViewHelpers() {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view   = $layout->getView();
        $view->doctype('XHTML1_STRICT');
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');
        $view->addHelperPath(APPLICATION_PATH . '/views/helpers/', 'Application_View_Helper');
    }

    /*     * *********************** */

    protected function _initDefaultEmailTransport() {
        $emailConfig   = $this->getOption('email');
        $smtpHost      = $emailConfig['transportOptionsSmtp']['host'];
        $mailTransport = new Zend_Mail_Transport_Smtp($smtpHost, $emailConfig['transportOptionsSmtp']);

        /*   $protocol = new Zend_Mail_Protocol_Smtp($smtpHost);
          $protocol->connect();
          $protocol->helo($smtpHost);
          $mailTransport->setConnection($protocol);

         */
        Zend_Mail::setDefaultTransport($mailTransport);
    }

    /*     * *********************** */

    protected function _initRoutes() {
        $frontController = Zend_Controller_Front::getInstance();

        $router          = $frontController->getRouter();
        $router->removeDefaultRoutes();
        $frontController = Zend_Controller_Front::getInstance();
        $frontController->getRouter()->addRoute('default', new Zend_Controller_Router_Route(
                        '/:module/:controller/:action/*',
                        array(
                            'module'     => 'default',
                            'controller' => 'index',
                            'action'     => 'index',
                            'lang'       => $lang
                        )
                )
        );


        // add multilingual route
        $frontController->getRouter()->addRoute('defaultMultilingual', new Zend_Controller_Router_Route(
                        ':lang/:module/:controller/:action/*',
                        array(
                            'module'     => 'default',
                            'controller' => 'index',
                            'action'     => 'index',
                            'lang'       => $lang
                        ),
                        array(
                            'lang' => '\w{2}'
                        )
                )
        );
        /*         * *********************************** */
        $route = new Zend_Controller_Router_Route(
                        'pages/:key',
                        array(
                            'lang'       => '',
                            'module'     => 'pages',
                            'controller' => 'show',
                            'action'     => 'index',
                            'key'        => ':key',
                        )
        );
        $router->addRoute('staticpages', $route);

        $route = new Zend_Controller_Router_Route(
                        ':lang/pages/:key',
                        array(
                            'lang'       => '',
                            'module'     => 'pages',
                            'controller' => 'show',
                            'action'     => 'index',
                            'key'        => ':key',
                        )
        );
        $router->addRoute('staticpagxes', $route);

        /*         * ********************** */
        $router->addRoute(
                'adminoller', new Zend_Controller_Router_Route('/admin',
                        array(
                            'module'     => 'admin',
                            'controller' => 'index',
                            'action'     => 'index'
                        )
                )
        );

        $router->addRoute(
                'admixnoller', new Zend_Controller_Router_Route('/admin/:controller',
                        array(
                            'module'     => 'admin',
                            'controller' => 'index',
                            'action'     => 'index'
                        )
                )
        );
        $router->addRoute(
                'axdmixnoller', new Zend_Controller_Router_Route('/admin/:controller/:action',
                        array(
                            'module'     => 'admin',
                            'controller' => 'index',
                            'action'     => 'index'
                        )
                )
        );
        $router->addRoute(
                'adminollex', new Zend_Controller_Router_Route('admin/news',
                        array(
                            'module'     => 'admin',
                            'controller' => 'news',
                            'action'     => 'index'
                        )
                )
        );
        $router->addRoute(
                'adminolxlex', new Zend_Controller_Router_Route('admin/news/add',
                        array(
                            'module'     => 'admin',
                            'controller' => 'news',
                            'action'     => 'add'
                        )
                )
        );
        /*   $route       = new Zend_Controller_Router_Route(
          ':lang/admin/:controller/:action',
          array(
          'lang'       => '',
          'module'     => 'admin',
          'controller' => 'controller',
          'action'     => 'action'
          )
          );
          $router->addRoute('admin_o', $route); */
        /*         * ****************** */
    }

    /*     * *********************** */

    protected function _initCache() {
        $cacheFrontendOptions = array(
            'automatic_serialization' => true,
            'cache_id_prefix'         => 'translate'
        );
        $cacheBackendOptions      = array(
            'cache_dir'              => APPLICATION_PATH . '/data/cache/',
            'file_name_prefix'       => 'uds',
            'hashed_directory_level' => 2
        );

        $cache = Zend_Cache::factory('Core', 'File', $cacheFrontendOptions, $cacheBackendOptions);
        Zend_Translate::setCache($cache);
        Zend_Translate::clearCache();



        /*     $this->bootstrap('db');
          $cacheFrontendOptions = array (
          'automatic_serialization' => true,
          'cache_id_prefix' => 'z'
          );
          $cacheBackendOptions = array (
          'cache_dir' => APPLICATION_PATH . '/data/cache/',
          'file_name_prefix' => 'uds',
          'hashed_directory_level' => 2
          );
          $cache = Zend_Cache::factory('Core', 'Memcached', $cacheFrontendOptions, $cacheBackendOptions);
          Zend_Registry::set('cache', $cache);
          return $cache;

         */
        /*   $frontendOptions = array(
          'lifetime' => 10800,
          'automatic_serialization' => true,
          'debug_header' => false,
          'regexps' => array(
          '$' => array('cache' => false),
          '/ajax' => array('cache' => true),
          ),
          'default_options' => array('cache_with_cookie_variables' => true,
          'make_id_with_cookie_variables' => false)
          );
          $backendOptions = array('cache_dir' => APPLICATION_PATH . '/data/cache');
          $cache = Zend_Cache::factory('Page', 'File', $frontendOptions, $backendOptions);
          $cache->start();
         * 
         */
        $this->bootstrap('db');
        $cacheFrontendOptions = array(
            'automatic_serialization' => true,
            'cache_id_prefix'         => 'z'
        );
        $cacheBackendOptions      = array(
            'cache_dir'              => APPLICATION_PATH . '/data/cache/',
            'file_name_prefix'       => 'uds',
            'hashed_directory_level' => 2
        );
        $cache                   = Zend_Cache::factory('Core', 'File', $cacheFrontendOptions, $cacheBackendOptions);
        Zend_Registry::set('cache', $cache);
        return $cache;
    }

    /*     * *********** */

    protected function _initAcl() {
        $fc = Zend_Controller_Front::getInstance();
        $fc->registerPlugin(new Plugin_AccessCheck(Zend_Auth::getInstance()));
    }

    /*     * *********** */

    protected function _initNavigation() {
        $this->bootstrap('FrontController');
        if ($this->_navigation === null) {
            $front      = $this->getResource('frontController');
            $navigation = new Plugin_UserMenu();
            $front->registerPlugin($navigation);
            $this->_navigation = $navigation;
        }
        return $this->_navigation;
    }

    protected function _initZFDebug() {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('ZFDebug');

        $options = array(
            'plugins' => array(
                'Variables',
                'File' => array('base_path' => '/путь/к/проекту'),
                'Memory',
                'Time',
                'Registry',
                'Exception',
                'Html',
            )
        );

        // Настройка плагина для адаптера базы данных
        if ($this->hasPluginResource('db')) {
            $this->bootstrap('db');
            $db                                        = $this->getPluginResource('db')->getDbAdapter();
            $options['plugins']['Database']['adapter'] = $db;
        }

        // Настройка плагина для кеша
        if ($this->hasPluginResource('cache')) {
            $this->bootstrap('cache');
            $cache                                  = $this - getPluginResource('cache')->getDbAdapter();
            $options['plugins']['Cache']['backend'] = $cache->getBackend();
        }

        $debug = new ZFDebug_Controller_Plugin_Debug($options);

        $this->bootstrap('frontController');
        $frontController = $this->getResource('frontController');
        $frontController->registerPlugin($debug);
    }

    /*     * ************************* */


//
}

