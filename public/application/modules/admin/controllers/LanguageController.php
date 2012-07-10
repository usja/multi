<?php

// Путь загрузки
define("UPLOAD_PATH", Zend_Registry::get('constants')->language->img_path);

class Admin_LanguageController extends Zend_Controller_Action {

    public function init() {
        // 
        $this->_redirect = $this->_helper->getHelper('Redirector');
        $this->_helper->layout->setLayout('admin');
    }

    /*
     * Отображение
     */

    public function indexAction() {
        $mapper = new Admin_Model_LanguageMapper();
        $data = $mapper->fetchAll();
        $msg = Zend_Controller_Action::_getParam('c');
        if ('d0' === $msg) {
            $this->view->ok = 'Удалено';
        } elseif ('d1' === $msg) {
            $this->view->neok = 'Ошибка удаления';
        } elseif ('d3' === $msg) {
            $this->view->neok = 'Не найдено';
        }
        $this->view->paginator = $data;
    }

    /*
     * Добавление
     */

    public function addAction() {
        $form = new Admin_Form_AddLanguageForm();
        $this->view->form = $form;
        /*         * ************************************************* */
        if ($this->_request->isPost()) {
            if (($form->isValid($this->_getAllParams()))) {
                /*                 * **************************************** */
                if (strlen($_FILES['Filedata']['name']) > 3) {
                    $tempFile = $_FILES['Filedata']['tmp_name'];

                    $targetPath = $_SERVER['DOCUMENT_ROOT'] . UPLOAD_PATH;
                    $temp_ext = UDS_Additional::extension($_FILES['Filedata']['name']);
                    $fileNameShort = 'lang_' . UDS_Additional::generateRandom($_FILES['Filedata']['name'], $targetPathB, $temp_ext);
                    $fileName = $fileNameShort . '.' . $temp_ext;
                    $targetFile = str_replace('//', '/', $targetPath) . $fileName;

                    if (!is_dir($targetPath)) {
                        mkdir(str_replace('//', '/', $targetPath), 0755, true);
                    }
                    move_uploaded_file($tempFile, $targetFile);
                }
                /*                 * **************************************** */
                $page = new Admin_Model_Language($form->getValues());
                $mapper = new Admin_Model_LanguageMapper();
                if ($fileName)
                    $page->setLanguage_pic(UPLOAD_PATH . $fileName);
                
                $ins_id = $mapper->save($page);
                /*                 * ******************************** */
                if ($ins_id > 0) {
                    $this->view->ok = "Язык создан";
                    $form_defs = array(
                        'language_id' => '',
                        'language_locale' => '',
                        'language_name' => '',
                        'language_visible' => '',
                        'language_pr' => '0'
                    );
                    $form->setDefaults($form_defs);
                    // Добавляем конфиг
                    $mapper->addConfigTable($ins_id);
                    
                } else {
                    $this->view->neok = "Язык не создан";
                }
            } else {
                $this->view->neok = "Заполните все поля";
            }
            /*             * **************************************** */
        }
    }

    /*
     * Редактирование
     */

    public function editAction() {
        $page_id = (int) Zend_Controller_Action::_getParam('id');

        $page = new Admin_Model_Language(array(
                    'id' => $page_id
                        )
        );
        $mapper = new Admin_Model_LanguageMapper();
        $form = new Admin_Form_AddlanguageForm();


        if (strlen($_FILES['Filedata']['name']) > 3) {
            $tempFile = $_FILES['Filedata']['tmp_name'];
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . UPLOAD_PATH;
            $temp_ext = UDS_Additional::extension($_FILES['Filedata']['name']);
            $fileNameShort = 'lang_' . UDS_Additional::generateRandom($_FILES['Filedata']['name'], $targetPathB, $temp_ext);
            $fileName = $fileNameShort . '.' . $temp_ext;
            $targetFile = str_replace('//', '/', $targetPath) . $fileName;
            if (!is_dir($targetPath)) {
                mkdir(str_replace('//', '/', $targetPath), 0755, true);
            }
            move_uploaded_file($tempFile, $targetFile);
        }
        /*         * **************************************** */
        $page = new Admin_Model_Language($form->getValues());
        $mapper = new Admin_Model_LanguageMapper();
        /* Сохранение */
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_getAllParams())) {
                $page = new Admin_Model_Language($form->getValues());
                $mapper = new Admin_Model_LanguageMapper();
                $data = $mapper->find($page_id);
                if ($fileName)
                    $page->setLanguage_pic(UPLOAD_PATH . $fileName);
                else {
                    $data = $mapper->find($page_id);
                    $page->setLanguage_pic($data->language_pic);
                }
                $page->setLanguage_pr($data->language_pr);

                if ($mapper->save($page)) {

                    $this->view->ok = "Баннер сохранена";
                } else {
                    $this->view->neok = "Ошибка сохранения";
                }
            } else {
                $this->view->neok = "Ошибка заполнения формы.";
            }
        }

        /* Конец сохранения */

        $form->setAction('/admin/language/edit/?id=' . $page_id);
        $data = $mapper->find($page_id);
        $this->view->data = $data;
        $form->language_id->setValue($page_id);

        $form_defs = array(
            'language_id' => $data->language_id,
            'language_locale' => $data->language_locale,
            'language_name' => $data->language_name,
            'language_pr' => $data->language_pr,
            'language_visible' => $data->language_visible
        );


        $form->setDefaults($form_defs);


        $this->_request->setActionname('add');
        $this->view->form = $form;
    }

    /*
     * Удаление
     */

    public function deleteAction() {
        $page_id = (int) Zend_Controller_Action::_getParam('id');
        if ($page_id < 1) {
            $this->_redirect('admin/language/?c=d3');
        } else {
            $mapper = new Admin_Model_LanguageMapper();
            $mapper->delete($page_id) ? $this->_redirect('admin/language/?c=d0') : $this->_redirect('admin/language/?c=d1');
        }
    }

    /*
     * 
     * ajax обработки
     */

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
                    $datas = new Admin_Model_Language(array(
                                'language_id' => preg_replace("/\D/", "", $k[$i][0]),
                                'language_pr' => $pr
                            ));
                    $mapper = new Admin_Model_LanguageMapper();
                    $mapper->saveAjaxOrder($datas);
                }
            }
        }
    }

}

