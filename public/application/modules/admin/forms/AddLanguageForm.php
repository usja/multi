<?php

class Admin_Form_AddLanguageForm extends Zend_Form {

    public function init() {

        $this->setMethod('post');


        $title = new Zend_Form_Element_Text('language_locale', array(
                    'required' => false,
                    'label' => 'Локаль [ru, en, fr..]:',
                    'maxlength' => '5',
                    'filters' => array('StringTrim'),
                ));

        $this->addElement($title);


        $title = new Zend_Form_Element_Text('language_name', array(
                    'required' => false,
                    'label' => 'Название языка:',
                    'maxlength' => '15',
                    'filters' => array('StringTrim'),
                ));

        $this->addElement($title);
        ////////////////////////////////////////////
        $id = new Zend_Form_Element_Hidden('language_id', array(
                    'value' => 'null'
                        )
        );

        $this->addElement($id);

        $id = new Zend_Form_Element_Hidden('language_pr', array(
                    'value' => 'null'
                        )
        );

        $this->addElement($id);

        ////////////////////////////////////////////

        $vis = new Zend_Form_Element_Checkbox('language_visible', array(
                    'label' => 'Показывать'));

        $vis->setValue(array('1'));
        $vis->setChecked(true);
        $vis->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            array('HtmlTag', array('tag' => 'div', 'class' => 'checkbox')),
            array('Label', array('tag' => 'div', 'class' => 'form-name')),
        ));

        $this->addElement($vis);
    }

}

