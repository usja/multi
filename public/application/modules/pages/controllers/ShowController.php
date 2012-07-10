<?

class Pages_ShowController extends Zend_Controller_Action {

    protected $values;
    protected $key;

    public function init() {
        $this->key = htmlspecialchars(Zend_Controller_Action::_getParam('key'));
    }

    public function indexAction() {
        $model = new Pages_Model_DbTable_Pages();
        if ($this->key) {
            $this->values = $model->GetStaticPageInformation($this->key);
        } else {
            
            $this->values = $model->GetIndexPageInformation();
        }


        if ($this->values) {
            $this->view->title = $this->values->title;
            $this->view->headMeta()->appendName('keywords', $this->values->kw);
            $this->view->headMeta()->appendName('description', $this->values->dw);
            $this->view->headTitle($this->view->title, 'PREPEND');
            $this->view->data = $this->values;
        } elseif (isset($this->key)) {
            $this->_redirect('/404');
        }
    }

}
