<?

class IndexController extends Zend_Controller_Action {

    public function indexAction() {
        $model = new Pages_Model_DbTable_Pages();
        $this->values = $model->GetIndexPageInformation();
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

