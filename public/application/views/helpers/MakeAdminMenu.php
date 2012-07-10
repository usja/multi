<?php
/*
 * Menu for administration
 *  
 *
 */

class Zend_View_Helper_MakeAdminMenu extends Zend_View_Helper_Abstract {

    public function MakeAdminMenu() {

        $request   = Zend_Controller_Front::getInstance()->getRequest()->getParams();
        $direction = trim(htmlspecialchars($request['controller']));

        $auth = Zend_Auth::getInstance();
        $this->identity = $auth->getIdentity();
        if ($auth->hasIdentity()):
            ?>
            <ul id="top-menu">
                <li <? if ($direction == "pages"): ?>class="active"<? endif; ?><? if ($direction == "sostav"): ?>class="active"<? endif; ?>>
                    <?
                    $url = $this->view->multilingualUrl(array(
                        'module'     => 'admin',
                        'controller' => 'pages',
                        'action'     => 'index'), 'defaultMultilingual', false);
                    ?>
                    <a href="<?= $url; ?>">Меню и страницы</a>
                </li>
                <li <? if ($direction == "news"): ?>class="active"<? endif; ?>>
                    <?
                    $url = $this->view->multilingualUrl(array(
                        'module'     => 'admin',
                        'controller' => 'news',
                        'action'     => 'index'), 'defaultMultilingual', false);
                    ?>
                    <a href="<?= $url; ?>">Новости</a>
                </li>
                <li <? if (($direction == "cats") || ($direction == "filial") || ($direction == "pagesfilial")): ?>class="active"<? endif; ?>>
                    <a href="<?= $this->view->multilingualUrl(array('module'     => 'admin', 'controller' => 'cats', 'action'     => 'index'), 'defaultMultilingual', false); ?>">Филиалы</a>
                </li>
                <li <? if ($direction == "ems"): ?>class="active"<? endif; ?>>
                    <?
                    $url = $this->view->multilingualUrl(array(
                        'module'     => 'admin',
                        'controller' => 'ems',
                        'action'     => 'index'), 'defaultMultilingual', false);
                    ?>
                    <a href="<?= $url; ?>">EMS</a>
                </li>

                <? if ($this->identity->role_users == 'root'): ?>
                    <li <? if ($direction == "users"): ?>class="active"<? endif; ?>>
                        <?
                        $url = $this->view->multilingualUrl(array(
                            'module'     => 'admin',
                            'controller' => 'users',
                            'action'     => 'index'), 'defaultMultilingual', false);
                        ?>
                        <a href="<?= $url; ?>">Администраторы</a>
                    </li>  

                    <li <? if ($direction == "banner"): ?>class="active"<? endif; ?>>
                        <?
                        $url = $this->view->multilingualUrl(array(
                            'module'     => 'admin',
                            'controller' => 'banner',
                            'action'     => 'index', 'group'      => 1), 'defaultMultilingual', false);
                        ?>
                        <a href="<?= $url; ?>">Баннеры</a>
                    </li>  
                <? endif; ?>

                <li id="niz-ten-hiden">-</li>
            </ul>

            <div id="top-dop-menu">
                <a href="<?= $this->view->multilingualUrl(array('module'     => 'admin', 'controller' => 'configs', 'action'     => 'index'), 'defaultMultilingual', false); ?>" id="settings">Настройки</a>
                <a href="<?= $this->view->multilingualUrl(array('module'     => 'admin', 'controller' => 'logout', 'action'     => 'index'), 'defaultMultilingual', false); ?>" id="exit">Выход</a>
            </div>
            <?
        endif;
    }

}