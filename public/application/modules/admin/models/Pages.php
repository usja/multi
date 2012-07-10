<?php

class Admin_Model_Pages {

    protected $_url;
    protected $_title;
    protected $_parent_id;
    protected $_id;
    protected $_txt;
    protected $_kw;
    protected $_dw;
    protected $_form_iid;
    protected $_visible;
    protected $_pr;
    protected $_pic;
    protected $_external_url;
    protected $_header;
    protected $_isindex;

    public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value) {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid Pages set property');
        }
        $this->$method($value);
    }

    public function __get($name) {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('<b>' . $method . '</b> Invalid Pages get property');
        }
        return $this->$method();
    }

    public function setOptions(array $options) {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {

            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    /*     * ***************************************** */

    public function setIsindex($text) {
        $this->_isindex = (int) $text;
        return $this;
    }

    public function getIsindex() {
        return $this->_isindex;
    }

    //    ID
    public function setId($text) {
        $this->_id = (int) $text;
        return $this;
    }

    public function getId() {
        return $this->_id;
    }

    // Parent ID
    public function setParent_Id($text) {
        $this->_parent_id = (int) $text;
        return $this;
    }

    public function getParent_Id() {
        return $this->_parent_id;
    }

    // Header
    public function setHeader($text) {
        $this->_header = (string) $text;
        return $this;
    }

    public function getHeader() {
        return $this->_header;
    }

    // Title
    public function setTitle($text) {
        $this->_title = (string) $text;
        return $this;
    }

    public function getTitle() {
        return $this->_title;
    }

    // URL
    public function setUrl($text) {
        $this->_url = (string) $text;
        return $this;
    }

    public function getUrl() {
        return $this->_url;
    }

    //Txt
    public function setTxt($text) {
        $this->_txt = (string) $text;
        return $this;
    }

    public function getTxt() {
        return $this->_txt;
    }

    //KW
    public function setKw($text) {
        $this->_kw = (string) $text;
        return $this;
    }

    public function getKw() {
        return $this->_kw;
    }

    //DW
    public function setDw($text) {
        $this->_dw = (string) $text;
        return $this;
    }

    public function getDw() {
        return $this->_dw;
    }

    //form_id
    public function setForm_iid($text) {
        $this->_form_iid = (int) $text;
        return $this;
    }

    public function getForm_iid() {
        return $this->_form_iid;
    }

    //visible
    public function setVisible($text) {
        $this->_visible = (int) $text;
        return $this;
    }

    public function getVisible() {
        return $this->_visible;
    }

    //PR
    public function setPr($text) {
        $this->_pr = (int) $text;
        return $this;
    }

    public function getPr() {
        return $this->_pr;
    }

    //
    public function setPic($text) {
        $this->_pic = (string) $text;
        return $this;
    }

    public function getPic() {
        return $this->_pic;
    }

    //
    public function setExternal_url($text) {
        $this->_external_url = (string) $text;
        return $this;
    }

    public function getExternal_url() {
        return $this->_external_url;
    }

}
