<?
class Zend_View_Helper_Tidy extends Zend_View_Helper_Abstract
{
    private $_config = array();

    public function __construct(){
        $this->_config["show-body-only"]=true;
        $this->_config["wrap"]=1;
        $this->_config["wrap-attributes"]=1;
        $this->_config["preserve-entities"]=1;
        $this->_config["output-xhtml"]=1;
        $this->_config["new-inline-tags"]='go';
        $this->_config['fix-bad-comments']='no';
        $this->_config['hide-comments']='no';
        $this->_config['drop-empty-paras']='yes';
    }
    public function tidy($html,$charset = 'utf8'){
        return tidy_repair_string($html,$this->_config,$charset);
    }
}
