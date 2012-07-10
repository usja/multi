<?php

class Admin_Model_Language {

    protected $_id;
    protected $_locale;
    protected $_name;
    protected $_pic;
    protected $_visible;
    protected $_pr;

    public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value) {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid Banner set property');
        }
        $this->$method($value);
    }

    public function __get($name) {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('<b>' . $method . '</b> Invalid Banner get property');
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

    /*     * *************************************** */

    public function setLanguage_visible($text) {
        $this->_visible = (int) $text;
        return $this;
    }

    public function getLanguage_visible() {
        return $this->_visible;
    }

    public function setLanguage_name($text) {
        $this->_name = (string) $text;
        return $this;
    }

    public function getLanguage_name() {
        return $this->_name;
    }

    public function setPic_name($text) {
        $this->_pic = (string) $text;
        return $this;
    }

    public function getPic_name() {
        return $this->_pic;
    }

    public function setLanguage_locale($text) {
        $this->_locale = (string) $text;
        return $this;
    }

    public function getLanguage_locale() {
        return $this->_locale;
    }

    public function setLanguage_pic($text) {
        $this->_pic = (string) $text;
        return $this;
    }

    public function getLanguage_pic() {
        return $this->_pic;
    }

    public function setLanguage_id($text) {
        $this->_id = (int) $text;
        return $this;
    }

    public function getLanguage_id() {
        return $this->_id;
    }

    public function setLanguage_pr($text) {
        $this->_pr = (int) $text;
        return $this;
    }

    public function getLanguage_pr() {
        return $this->_pr;
    }

}
