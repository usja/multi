<?php

final class Plugin_UserMenu extends Zend_Controller_Plugin_Abstract {

    protected $_view;
    static $_lang = '';

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        if ('ua' != Zend_Registry::get('uds_lang'))
            $this->_lang = Zend_Registry::get('uds_lang');
        $layout = Zend_Layout::getMvcInstance();
        $this->_view = $layout->getView();

        $mapper = new Admin_Model_PagesMapper();
        $oData  = $mapper->fetchAllAsArray();

        $mapper      = new Admin_Model_Help();
        $oDataFilial = $mapper->sortByParentId();

        $entries = array();
        foreach ($oData as $item) {
            if ((strpos($item['external_url'], 'filial')) || ($item['external_url'] == 'filial')) {

                if (is_array($oDataFilial)) {
                    foreach ($oDataFilial as $rowx) {
                        foreach ($rowx as $row) {
                            $entry = array();
                            $entry['id']        = $row->id;
                            $entry['parent_id'] = $item['id'];
                            $entry['title']     = $row->title;
                            $entry['txt']       = $row->title;
                            $entry['label']     = $row->title;
                            $entry['module']    = 'filial';
                            $entry['route']     = 'filialxx';
                            $entry['params']    = array('lang'     => $this->_lang, 'filial'   => $row->url);
                            $entries[] = $entry;
                        }
                    }
                }
            }
            $entries[] = $item;
        }

        $data = Default_View_Helper_SelectParentIdCategories::Tree($entries);

        $container = new Zend_Navigation_Page_Mvc();
        
        $container->setPages($data);
        foreach ($container->getPages() as $page) {
            $uri = $page->getHref();
            $uri = str_replace($this->_view->baseUrl(), '', $uri);
            $res = str_replace('/', '', $uri);

            if (($uri == $request->getRequestUri()) || ((strlen($res) > 0) && (stristr(str_replace('/', '', $request->getRequestUri()), $res)))) {
                $page->setActive('true');
                $page->setClass('active');
            }
        }

        Zend_Registry::set('mainMenu', $container);
        /*         * ******** */

        $filial = htmlspecialchars($request->getParam('filial'));
        
        $mapper = new Admin_Model_CategoriesMapper();
        $id = $mapper->findbyurl($filial)->id;
        if ($id < 1)
        {
            $id = 26;
            $help = new Admin_Model_Help();
            $d = $help->fetchCategory2LangById($id);
            $filial = $d->url;
        }
        
        $mapper = new Admin_Model_PagesfilialMapper();
        $oData  = $mapper->fetchAllAsArray($id, $filial);
        
        $data   = Default_View_Helper_SelectParentIdCategories::Tree($oData);

        $container = new Zend_Navigation_Page_Mvc();

        $container->setPages($data);
        foreach ($container->getPages() as $page) {
            $uri = $page->getHref();
            $uri = str_replace($this->_view->baseUrl(), '', $uri);
            $res = str_replace('/', '', $uri);

            if (((strlen($res) > 0) && (stristr(str_replace('/', '', $request->getRequestUri()), $res)))) {
                $page->setActive('true');
                $page->setClass('active');
            }
        }
        Zend_Registry::set('leftMenu', $container);
        /*         * ******* */

        $this->_view->navigation($container);
    }

}