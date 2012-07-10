<?php

class Admin_Form_AddPagesForm extends Zend_Form {

    public function init() {

        $this->setMethod('post');
        $title = new Zend_Form_Element_Text('title', array(
                    'required'  => true,
                    'label'     => 'Название:',
                    'maxlength' => '255',
                    'filters'   => array('StringTrim'),
                ));
        $this->addElement($title);




        ////////////////////////
        $mapper    = new Admin_Model_PagesMapper();
        $tree      = $mapper->sortByParentId();
        $tree_sort = new Admin_View_Helper_SelectParentId();
        $getsort   = $tree_sort->Show($tree);

        if (count($getsort)) {
            $parent_id = new Zend_Form_Element_Select('form_iid');
            $parent_id->setLabel('Родитель:')
                    ->addMultiOption('0', '');
            $parent_id->setDecorators(array(
                'ViewHelper',
                'Description',
                'Errors',
                array('HtmlTag', array('tag'   => 'div', 'class' => 'skinselect')),
                array('Label', array('tag'   => 'div', 'class' => 'form-name')),
            ));


            foreach ($getsort as $c) {
                $parent_id->addMultiOption($c['id'], $c['title']);
            }
            $this->addElement($parent_id);
        }
        /////////////////////////////

        $title = new Zend_Form_Element_Text('external_url', array(
                    'required'  => false,
                    'label'     => 'Внешняя cсылка (Если этот пункт меню служит в качестве ссылки):',
                    'maxlength' => '255',
                  //  'onkeyup'   => 'hideblock("external_url","txt_form_input")',
                    'filters'   => array('StringTrim'),
                ));
        $this->addElement($title);

        $kw = new Zend_Form_Element_Text('kw', array(
                    'label'     => 'Ключевые слова (SEO):',
                    'maxlength' => '255',
                    'filters'   => array('StringTrim'),
                ));
        $this->addElement($kw);

        $dw = new Zend_Form_Element_Textarea('dw', array(
                    'label'     => 'Описание (SEO):',
                    'maxlength' => '255',
                    'rows'      => '2',
                    'filters'   => array('StringTrim'),
                ));
        $this->addElement($dw);
        
        $title = new Zend_Form_Element_Text('header', array(
                    'required'  => false,
                    'label'     => 'Заголовок:',
                    'maxlength' => '255',
                    'filters'   => array('StringTrim'),
                ));
        $this->addElement($title);
        $txt = new Zend_Form_Element_Textarea('txt', array(
                    'id'    => 'txt',
                    'label' => 'Текст:',
                ));

        $txt->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            array('HtmlTag', array('tag' => 'div', 'id'  => 'txt_form_input')),
            array('Label', array('tag' => 'div', 'id'  => 'txt_form_input_label')),
        ));
        $this->addElement($txt);

        $vis = new Zend_Form_Element_Checkbox('visible', array(
                    'label' => 'Показывать'));

        $vis->setValue(array('1'));
        $vis->setChecked(true);
        $vis->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            array('HtmlTag', array('tag'   => 'div', 'class' => 'checkbox')),
            array('Label', array('tag'   => 'div', 'class' => 'form-name')),
        ));
        $this->addElement($vis);
        //////

        ////////////////////////////////////////////
        $id = new Zend_Form_Element_Hidden('id', array(
                    'value' => 'null'
                        )
        );

        $this->addElement($id);
        
        $vis = new Zend_Form_Element_Checkbox('isindex', array(
                    'label' => 'Использовать как главную страницу'));

        $vis->setValue(array('1'));
        $vis->setChecked(false);
        $vis->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            array('HtmlTag', array('tag'   => 'div', 'class' => 'checkbox')),
            array('Label', array('tag'   => 'div', 'class' => 'form-name')),
        ));

        $this->addElement($vis);
        
    }

}

