<?php

/*
 *  Администрирование сайтом
 * 
 */
define("ICO_WIDTH", Zend_Registry::get('constants')->static->ico_width);
define("ICO_HEIGHT", Zend_Registry::get('constants')->static->ico_height);

class Admin_PagesController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $this->_redirect = $this->_helper->getHelper('Redirector');
       
        $auth = Zend_Auth::getInstance();
        $this->identity = $auth->getIdentity();

        if (!$auth->hasIdentity()) {
            die($this->identity);
            $this->_redirect('admin/login/');
        }
    }

    /*
     * Отображение страниц
     *
     */

    public function indexAction() {
        $mapper = new Admin_Model_PagesMapper();
        $this->view->entries = $mapper->sortByParentId();
        $msg = Zend_Controller_Action::_getParam('c');
        if ('ok' == Zend_Controller_Action::_getParam('msgok'))
            $this->view->ok = 'Данные сохранены';
        if ('d0' === $msg) {
            $this->view->ok = 'Страница успешно удалена.';
        } elseif ('d1' === $msg) {
            $this->view->neok = 'Ошибка удаления страницы.';
        } elseif ('d3' === $msg) {
            $this->view->neok = 'Такой страницы нет.';
        }
    }

    /*
     * форма добавления
     */

    public function addAction() {

        $form = new Admin_Form_AddPagesForm();
        $this->view->form = $form;
        /*         * ************************************************* */
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_getAllParams())) {
                $page = new Admin_Model_Pages($form->getValues());
                $mapper = new Admin_Model_PagesMapper();
                $ins_id = $mapper->save($page);
                if ($ins_id > 0) {
                    if (strlen($_FILES['Filedata']['name']) > 3) {
                        $tempFile = $_FILES['Filedata']['tmp_name'];

                        $targetPath = $_SERVER['DOCUMENT_ROOT'] . '/images/icon/tmp/';
                        $targetPathB = $_SERVER['DOCUMENT_ROOT'] . '/images/icon/';
                        $temp_ext = UDS_Additional::extension($_FILES['Filedata']['name']);
                        $fileNameShort = 'ico_' . UDS_Additional::generateRandom($_FILES['Filedata']['name'], $targetPathB, $temp_ext);
                        $fileName = $fileNameShort . '.' . $temp_ext;
                        $targetFile = str_replace('//', '/', $targetPath) . $fileName;
                        $targetFileB = str_replace('//', '/', $targetPathB) . $fileName;
                        /*                         * ************************************** */
                        if (!is_dir($targetPathB)) {
                            mkdir(str_replace('//', '/', $targetPathB), 0755, true);
                        }
                        if (!is_dir($targetPath)) {
                            mkdir(str_replace('//', '/', $targetPath), 0755, true);
                        }
                        move_uploaded_file($tempFile, $targetFileB);

                        //   $im = new Imagick($targetFile);
                        //  $im->cropThumbnailImage(164, 120);
                        //  $im->writeImage($targetFileB);
                        // $im->destroy();
                        @unlink($targetFile);
                        $help = new Admin_Model_Help();
                        $help->updatepagepic($ins_id, $fileName);
                    }

                    $this->view->ok = "Страница создана.";
                    if (isset($_POST['back'])) {
                        $url = $this->view->multilingualUrl(array('module' => 'admin', 'controller' => 'pages', 'action' => 'index'));
                        $this->_redirect($url . '?msgok=ok');
                    }
                    $form_defs = array(
                        'title' => '',
                        'form_iid' => '',
                        'kw' => '',
                        'dw' => '',
                        'txt' => '',
                        'visible' => '1',
                        'header' => '',
                        'isindex' => '0'
                    );
                    $form = new Admin_Form_AddPagesForm();
                    $this->view->form = $form;
                    $form->setDefaults($form_defs);
                } else {
                    $this->view->neok = "Такая страница уже сущействует";
                }
            } else {
                $this->view->neok = "Заполните все поля";
            }
        }
    }

    /*
     * Редактирование статической страницы
     */

    public function editAction() {
        $page_id = (int) Zend_Controller_Action::_getParam('id');
        $page = new Admin_Model_Pages(array(
                    'id' => $page_id
                        )
        );
        $mapper = new Admin_Model_PagesMapper();
        $form = new Admin_Form_AddPagesForm();
        $help = new Admin_Model_Help();
        /* Сохранение */
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_getAllParams())) {
                /*                 * ***************************** */
                if (strlen($_FILES['Filedata']['name']) > 3) {
                    $tempFile = $_FILES['Filedata']['tmp_name'];

                    $targetPath = $_SERVER['DOCUMENT_ROOT'] . '/images/icon/tmp/';
                    $targetPathB = $_SERVER['DOCUMENT_ROOT'] . '/images/icon/';

                    $temp_ext = UDS_Additional::extension($_FILES['Filedata']['name']);
                    $fileNameShort = 'ico_' . UDS_Additional::generateRandom($_FILES['Filedata']['name'], $targetPathB, $temp_ext);
                    $fileName = $fileNameShort . '.' . $temp_ext;


                    $targetFile = str_replace('//', '/', $targetPath) . $fileName;
                    $targetFileB = str_replace('//', '/', $targetPathB) . $fileName;
                    /*                     * ************************************** */
                    if (!is_dir($targetPathB)) {
                        mkdir(str_replace('//', '/', $targetPathB), 0755, true);
                    }
                    if (!is_dir($targetPath)) {
                        mkdir(str_replace('//', '/', $targetPath), 0755, true);
                    }
                    move_uploaded_file($tempFile, $targetFileB);

                    /*  $im = new Imagick($targetFile);
                      $im->cropThumbnailImage(164, 120);

                      $im->writeImage($targetFileB);
                      $im->destroy();
                      @unlink($targetFile);
                     * 
                     */
                    $help = new Admin_Model_Help();
                    $help->updatepagepic($page_id, $fileName);
                }
                /*                 * **************************** */
                $page = new Admin_Model_Pages($form->getValues());
                $mapper = new Admin_Model_PagesMapper();

                if ($mapper->save($page)) {
                    if (isset($_POST['back'])) {
                        $url = $this->view->multilingualUrl(array('module' => 'admin', 'controller' => 'pages', 'action' => 'index'));
                        $this->_redirect($url . '?msgok=ok');
                    }
                    $this->view->ok = "Страница сохранена.";
                } else {
                    $this->view->neok = "Страница не сохранена.";
                }
            } else {
                $this->view->neok = "Ошибка заполнения формы.";
            }
        }

        if ($_GET['clear'] == 1) {

            //Удалить фото
            $help->clearphotopages($page_id);
        }
        /* Конец сохранения */
        $url = $this->view->multilingualUrl(array('module' => 'admin', 'controller' => 'pages', 'action' => 'edit'));
        $form->setAction($url . '?id=' . $page_id);
        $data = $mapper->find($page_id);
        $this->view->data = $data;
        $form->id->setValue($page_id);
        $form_defs = array(
            'title' => $data->title,
            'form_iid' => $data->parent_id,
            'kw' => $data->kw,
            'dw' => $data->dw,
            'txt' => $data->txt,
            'visible' => $data->visible,
            'external_url' => $data->external_url,
            'header' => $data->header,
            'isindex'   => $data->isindex
        );
        $form->setDefaults($form_defs);
        $this->_request->setActionname('add');
        $this->view->form = $form;
    }

    /*     * ******* Удаление *** */

    public function deleteAction() {
        $page_id = (int) Zend_Controller_Action::_getParam('id');
        $url = $this->view->multilingualUrl(array('module' => 'admin', 'controller' => 'pages', 'action' => 'index'));
        if ($page_id < 1) {
            $this->_redirect($url . '?c=d3');
        } else {
            $mapper = new Admin_Model_PagesMapper();
            $mapper->delete($page_id) ? $this->_redirect($url . '?c=d0') : $this->_redirect($url . 'c=d1');
        }
    }

    /* Ajax сортировка */

    public function ajaxChangePriorityAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->q = $this->getRequest()->getPost('q');
            $arr = explode('&', $this->q);
            if (count($arr)) {
                $pr = 0;
                foreach ($arr as $i => $b) {
                    $pr++;
                    $k[$i] = explode('=', $b);
                    if ($k[$i][1] < 1)
                        $k[$i][1] = 0;
                    $datas = new Admin_Model_Pages(array(
                                'id' => preg_replace("/\D/", "", $k[$i][0]),
                                'parent_Id' => $k[$i][1],
                                'pr' => $pr
                            ));
                    $mapper = new Admin_Model_PagesMapper();
                    $mapper->saveAjaxPagesOrder($datas);
                }
            }
        }
    }

}

