<?php

/**
 * Author: Ukrainian Design Studio
 * Uskov A.
 */
class Admin_View_Helper_Pages_PagesMenu extends Zend_View_Helper_Abstract
{

    public function MakePagesMenu()
    {
        $this->auth = Zend_Auth::getInstance();
        $this->identity = $this->auth->getIdentity();
        $req = Zend_Controller_Front::getInstance()->getRequest();
        ?>
        <ul>
            <li <? if ((('index' === $req->action) || ('edit' === $req->action)) && ('sostav' != $req->controller)): ?>class="active"<? endif; ?>>
                <a href="<?= $this->multilingualUrl(array('module' => 'admin', 'controller' => 'pages', 'action' => 'index')); ?>">Просмотр</a>
            </li>
            <li <? if ('add' === $req->action):?>class="active" <? endif;?>>
                <a href="<?= $this->multilingualUrl(array('module' => 'admin', 'controller' => 'pages', 'action' => 'add')); ?>">Добавление</a>
            </li>
        </ul>
        <?
    }

}
