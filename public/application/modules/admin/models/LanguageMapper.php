<?php

/*
 * Mapper для работы с таблицей языков
 * 
 * 
 */

class Admin_Model_LanguageMapper extends Zend_Db_Table_Abstract {

    static protected $_locale;
    protected $_dbTable;


    public function setDbTable($dbTable) {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable('Admin_Model_DbTable_Language');
        }
        return $this->_dbTable;
    }

    public function getLanguageWithoutCurrent() {
        $where     = $this->getDbTable()->select()
                ->where('language_id != ?', Zend_Registry::get('uds_lang_id'));
        $resultSet = $this->getDbTable()->fetchAll($where);
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry     = new Admin_Model_Language();
            $entry->setLanguage_id($row->language_id);
            $entries[] = $entry;
        }
        return $entries;
    }

    // Получение всех языков
    public function fetchAll() {
        $where     = $this->getDbTable()->select()
                ->order('language_pr');
        $resultSet = $this->getDbTable()->fetchAll($where);
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry     = new Admin_Model_Language();
            $entry->setLanguage_id($row->language_id)
                    ->setLanguage_locale($row->language_locale)
                    ->setLanguage_name($row->language_name)
                    ->setLanguage_pic($row->language_pic)
                    ->setLanguage_visible($row->language_visible)
                    ->setLanguage_pr($row->language_pr);
            $entries[] = $entry;
        }
        return $entries;
    }

    // получение id языка
    public function getLanguageId() {
        $where  = $this->getDbTable()->select()
                ->order('language_pr')
                ->where('language_locale = ?', Zend_Registry::get('uds_lang'))
                ->limit('1');
        $result = $this->getDbTable()->fetchRow($where);
        return $result->language_id;
    }

    // сохранение/вставка
    public function save(Admin_Model_Language $Data) {

        if ($Data->getLanguage_id()) {
            $id = $Data->getLanguage_id();
        } else {
            $id = 0;
        }

        $data = array(
            'language_locale'  => $Data->getLanguage_locale(),
            'language_name'    => $Data->getLanguage_name(),
            'language_pr'      => $Data->getLanguage_pr(),
            'language_pic'     => $Data->getLanguage_pic(),
            'language_visible' => $Data->getLanguage_visible()
        );
        try {
            if (0 === ($id = $Data->getLanguage_id())) {
                unset($data['language_id']);
                return $this->getDbTable()->insert($data);
            } else {
                $this->getDbTable()->update($data, array('language_id = ?' => $id));
                return true;
            }
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    // find
    public function find($id) {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return false;
        }
        $row       = $result->current();
        $page_data = new Admin_Model_Language();
        $page_data->setLanguage_id($row->language_id)
                ->setLanguage_locale($row->language_locale)
                ->setLanguage_name($row->language_name)
                ->setLanguage_pic($row->language_pic)
                ->setLanguage_visible($row->language_visible)
                ->setLanguage_pr($row->language_pr);
        return $page_data;
    }

    // Смена приоритета
    public function saveAjaxOrder(Admin_Model_Language $Data) {
        $data = array(
            'language_pr' => $Data->getLanguage_pr(),
        );
        try {
            $this->getDbTable()->update($data, array('language_id = ?' => $Data->getLanguage_id()));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // удаление
    public function delete($id) {
        if ($id > 0) {
            try {
                $this->getDbTable()->delete('language_id = ' . $id);
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
    }

    // Добавление записи в конфиг таблице
    public function addConfigTable($ins_id) {
        $this->_name = 'configs';
        parent::_setupTableName();
        $data = array('lang_id' => $ins_id);
        $id       = parent::insert($data);
        if ($id) {
            return $id;
        } else {
            return false;
        }
    }

}

