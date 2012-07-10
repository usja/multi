<?

class Admin_LoginController extends Zend_Controller_Action {

    protected static $identity;

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $auth = Zend_Auth::getInstance();
        $this->identity = $auth->getIdentity();
    }

    public function indexAction() {

        $modelC = new Admin_Model_Configs();
        $model  = new Admin_Model_Login();
        if ($this->_request->isPost()) {
            $data = $modelC->readConfigs();
            if ($data->ip_block == 1) {
                if ($model->getCountBadEnter() > 2) {
                    $url = $this->view->multilingualUrl(array('module'     => 'admin', 'controller' => 'login'), null, true);
                    $this->_redirect($url . '?s=x');
                }
            }
            if ($model->login(
                            $this->_request->getParam('user_login'), $this->_request->getParam('user_password')
            )) {
                $url = $this->view->multilingualUrl(array('module'     => 'admin', 'controller' => 'pages'), null, true);
                $this->_redirect($url);
            } else {
                $url = $this->view->multilingualUrl(array('module'     => 'admin', 'controller' => 'login'), null, true);
                $this->_redirect($url . '?s=e');
            }
        }
    }

}
