<?php

/* Controller for Administration module
 * Author: Ukrainian Design Studio, Uskov A.
 * 
 * 
 */

class Admin_IndexController extends Zend_Controller_Action {

    protected static $identity;

    public function init() {
        $auth = Zend_Auth::getInstance();
        $this->identity = $auth->getIdentity();
        if ((!$auth->hasIdentity())) {
            $url = $this->view->multilingualUrl(array(
                'module'     => 'admin',
                'controller' => 'login',
                'action'     => 'index'), null, false);
            $this->_redirect($url);
        }
        $this->_helper->layout->setLayout('admin');
    }

    public function indexAction() {
        if (!isset($this->identity->role_users) || ('user' === $this->identity->role_users)) {
            $url = $this->view->multilingualUrl(array(
                'module'     => 'admin',
                'controller' => 'login',
                'action'     => 'index'), null, false);
            $this->_redirect($url);
        }
        $url         = $this->view->multilingualUrl(array(
            'module'     => 'admin',
            'controller' => 'pages',
            'action'     => 'index'), null, false);
        $this->_redirect($url);
    }

}

