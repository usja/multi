<?

class Admin_LogoutController extends Zend_Controller_Action {

    public function init() {
        $this->_redirect = $this->_helper->getHelper('Redirector');
        $this->_helper->layout->disableLayout();
    }

    public function indexAction() {
        $this->_helper->layout->disableLayout();
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        Zend_Session::forgetMe();
        $url  = $this->view->multilingualUrl(array('module'     => 'admin', 'controller' => 'login'), null, true);
        $this->_redirect($url);
    }

}
