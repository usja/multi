<?php

class Admin_Model_PagesMapper {

    protected $_dbTable;

    /*     * ************************************** */

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
            $this->setDbTable('Admin_Model_DbTable_Pages');
        }
        return $this->_dbTable;
    }

    /*     * ************************************** */

    public function fetchAll() {
        $where     = $this->getDbTable()->select()
                ->where('lang_id = ?', Zend_Registry::get('uds_lang_id'))
                ->order('pr ASC');
        $resultSet = $this->getDbTable()->fetchAll($where);
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry     = new Admin_Model_Pages();
            $entry->setId($row->id)
                    ->setParent_Id($row->parent_id)
                    ->setTitle($row->title)
                    ->setUrl($row->url)
                    ->setPic($row->pic)
                    ->setVisible($row->visible)
                    ->setHeader($row->header)
                    ->setIsindex($row->isindex)
                    ->setExternal_url($row->external_url);
            $entries[] = $entry;
        }
        return $entries;
    }

    /*     * *********************************** */

    public function sortByParentId() {
        $items = $this->fetchAll();
        if (count($items) > 0) {
            $itemsByParent = array();
            foreach ($items as $item) {
                if (!isset($itemsByParent[$item->parent_id])) {
                    $itemsByParent[$item->parent_id] = array();
                }
                $itemsByParent[$item->parent_id][] = $item;
                unset($item);
            }
            return $itemsByParent;
        }
    }
    // Сброс главных страниц
    protected function resetIndex() {
        $data = array(
            'isindex' => '0'
        );
        $this->getDbTable()->update($data, array('lang_id = ?' => Zend_Registry::get('uds_lang_id')));
    }

    /*     * ****************************************** */

    public function save(Admin_Model_Pages $Pages) {
        $url = UDS_Additional::RusToLat($Pages->getTitle());
        if (0 === ($id  = $Pages->getId())) {

            $validator = new Zend_Validate_Db_RecordExists(
                            array(
                                'table'  => 'pages',
                                'field'  => 'url'
                            )
            );
            $counter = 0;
            while ($validator->isValid($url)) {
                $counter++;
                $url = $url . '-' . $counter;
            }
        }
        if ($Pages->getIsindex() == 1) {
            self::resetIndex();
        }

        if ($Pages->getForm_iid()) {
            $d = $Pages->getForm_iid();
        } else {
            $d    = 0;
        }
        $data = array(
            'parent_id'    => $d,
            'txt'          => $Pages->getTxt(),
            'url'          => $url,
            'lang_id'      => Zend_Registry::get('uds_lang_id'),
            'title'        => $Pages->getTitle(),
            'kw'           => $Pages->getKw(),
            'dw'           => $Pages->getDw(),
            'visible'      => $Pages->getVisible(),
            'header'       => $Pages->getHeader(),
            'external_url' => $Pages->getExternal_url(),
            'isindex'      => $Pages->getIsindex()
        );
        try {

            if (0 === ($id = $Pages->getId())) {
                unset($data['id']);
                return $this->getDbTable()->insert($data);
            } else {
                $this->getDbTable()->update($data, array('id = ?' => $id));
                return $id;
            }
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    ////////////////////////////////////////////////////
    public function saveAjaxPagesOrder(Admin_Model_Pages $Pages) {
        $data = array(
            'pr'        => $Pages->getPr(),
            'parent_id' => $Pages->getParent_Id()
        );
        try {
            $this->getDbTable()->update($data, array('id = ?' => $Pages->getId()));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    //////////////////////////////////////////////////
    public function find($id) {
        $result = $this->getDbTable()->find($id);
        // print_r($result);
        if (0 == count($result)) {
            return false;
        }
        $row       = $result->current();
        $page_data = new Admin_Model_Pages();
        $page_data->setId($row->id)
                ->setParent_Id($row->parent_id)
                ->setTitle($row->title)
                ->setTxt($row->txt)
                ->setKw($row->kw)
                ->setDw($row->dw)
                ->setPic($row->pic)
                ->setVisible($row->visible)
                ->setHeader($row->header)
                ->setIsindex($row->isindex)
                ->setExternal_url($row->external_url);
        return $page_data;
    }

    ///////////////////////////////////////////////
    /* Удаление */
    public function delete($id) {
        if ($id > 0) {
            try {

                $this->getDbTable()->delete('id = ' . $id);
                // Обновляем рейтинг и уровень вложеноости тех страниц, чьего родителя мы удалили
                $data = array(
                    'parent_id' => '0',
                    'pr'        => '999',
                    'visible'   => '0'
                );
                $this->getDbTable()->update($data, array('parent_id = ?' => $id));
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
    }

    /*     * * */

    public function fetchAllAsArray() {
        $lang      = '';
        if ('ua' != Zend_Registry::get('uds_lang'))
            $lang      = Zend_Registry::get('uds_lang');
        //die(Zend_Registry::get('uds_lang_id'));
        $where     = $this->getDbTable()->select()
                ->where('lang_id = ?', Zend_Registry::get('uds_lang_id'))
                ->order('pr ASC');
        $resultSet = $this->getDbTable()->fetchAll($where);
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = array();
            $entry['id']        = $row->id;
            $entry['parent_id'] = $row->parent_id;
            $entry['title']     = $row->title;
            $entry['label']     = $row->title;
            $entry['txt']       = $row->txt;
            $entry['kw']        = $row->kw;
            $entry['pic']       = $row->pic;
            $entry['visible']   = $row->visible;
            $entry['header']    = $row->header;
            $entry['isindex']   = $row->isindex;

            if ($row->external_url) {
                $entry['external_url'] = $row->external_url;
                if (strstr($row->external_url, '/'))
                    $entry['uri']          = $row->external_url;
                else {
                    $entry['module'] = $row->external_url;
                    $entry['route']  = 'defaultMultilingual';
                    $entry['params'] = array('lang' => $lang);
                }
            } else {
                $entry['module']     = 'pages';
                $entry['controller'] = 'show';
                $entry['action']     = 'index';
                $entry['params']     = array('key'           => $row->url, 'lang'          => $lang);
                $entry['route'] = 'staticpagxes';
            }
            
            $entries[]      = $entry;
        }
        return $entries;
    }

}