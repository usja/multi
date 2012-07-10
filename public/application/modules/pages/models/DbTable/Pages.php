<?php

class Pages_Model_DbTable_Pages extends Zend_Db_Table_Abstract {

    protected $_name = 'pages';

    /*
     * получение полной информации по статической странице
     */
        public function GetExternalPageInformation($key) {
        $sql    = $this->getAdapter()->select()
                ->from($this->_name)
                ->where("external_url like ? ", '%'.$key)
                ->where('lang_id = ?', Zend_Registry::get('uds_lang_id'))
                ->limit('1');
        $stmt   = $this->getAdapter()->query($sql);
        $result = $stmt->fetchObject();
        return $result;
    }

    public function GetStaticPageInformation($key) {
        $sql    = $this->getAdapter()->select()
                ->from($this->_name)
                ->where("url = ?", $key)
                ->where('lang_id = ?', Zend_Registry::get('uds_lang_id'))
                ->limit('1');
        $stmt   = $this->getAdapter()->query($sql);
        $result = $stmt->fetchObject();
        return $result;
    }

    /*
     * 
     */

    public function GetIndexPageInformation() {
        $sql    = $this->getAdapter()->select()
                ->from($this->_name)
                ->where("isindex = ?", '1')
                ->where('lang_id = ?', Zend_Registry::get('uds_lang_id'))
                ->limit('1');
        $stmt   = $this->getAdapter()->query($sql);
        $result = $stmt->fetchObject();
        return $result;
    }

}

