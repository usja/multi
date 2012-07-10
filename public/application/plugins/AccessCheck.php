<?php

class Plugin_AccessCheck extends Zend_Controller_Plugin_Abstract {

    // тип пользователя по умолчанию
    private $_defaultRole = 'guest';

    public function __construct(Zend_Auth $auth) {
        $this->auth = $auth;
        $this->acl = new Zend_Acl();
        // добавляем различные типы пользователей
        $this->acl->deny();
        $this->acl->addRole(new Zend_Acl_Role($this->_defaultRole));
        $this->acl->addRole(new Zend_Acl_Role('user'));
        $this->acl->addRole(new Zend_Acl_Role('admin'), 'user');
        $this->acl->addRole(new Zend_Acl_Role('root'), 'admin');


        // добавляем ресурсы, доступом к которым нужно управлять
        // управление аккаунтом
        $this->acl->add(new Zend_Acl_Resource('default'))
                ->add(new Zend_Acl_Resource('default:users'), 'default')
                ->add(new Zend_Acl_Resource('default:index'), 'default')
                ->add(new Zend_Acl_Resource('admin:login'), 'default');


        $this->acl->add(new Zend_Acl_Resource('admin'))
                ->add(new Zend_Acl_Resource('admin:pages'), 'admin')
                ->add(new Zend_Acl_Resource('admin:language'), 'admin')
                ->add(new Zend_Acl_Resource('admin:logout'), 'admin')
                ->add(new Zend_Acl_Resource('admin:users'), 'admin');

        $this->acl->add(new Zend_Acl_Resource('root'))
                ->add(new Zend_Acl_Resource('admin:configs'), 'root');
        
        $this->acl->add(new Zend_Acl_Resource('register'))
                ->add(new Zend_Acl_Resource('register:index'), 'register');

        $this->acl->add(new Zend_Acl_Resource('user'))
                ->add(new Zend_Acl_Resource('user:index'), 'user')
                ->add(new Zend_Acl_Resource('user:edit'), 'user')
                ->add(new Zend_Acl_Resource('user:add'), 'user')
                ->add(new Zend_Acl_Resource('user:ajax-login'), 'user')
                ->add(new Zend_Acl_Resource('user:logout'), 'user');

        // разрешаем доступ везде          
        $this->acl->allow()
                ->deny(null, 'admin')
                ->deny('user', 'register')
                ->deny('admin', 'register')
                ->deny('admin', 'admin:configs')
                ->deny('admin', 'admin:users')
                ->deny('user', 'user:ajax-login')
                ->deny('guest', 'user:logout')
                ->deny('guest', 'user:edit')
                ->deny('guest', 'user:add')
                ->allow('guest', 'user:ajax-login')
                ->allow('admin', 'admin')
                ->allow('root', 'admin')
                ->allow('root', 'admin:users')
                ->allow('root', 'admin:configs');
        /*         * ********** */
        //    $this->acl->add(new Zend_Acl_Resource('register'));
        //$this->acl->deny('user', 'register');
        // разрешаем пользоватлеям уровня "users" доступ к управлению аккаунтом
        //    $this->acl->allow('user', 'default:users');

        Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($this->acl);
    }

    /*     * ***************** */

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        // проверяем если юзер вошел в систему и имеет правильный тип,
        // иначе, назначаем ему тип по-умолчанию ("guest")
        if ($this->auth->hasIdentity())
            $role = $this->auth->getIdentity()->role_users;
        else
            $role = $this->_defaultRole;

        if (!$this->acl->hasRole($role))
            $role     = $this->_defaultRole;
        // $role = 'guest';
        // ACL resource - запрошенный модуль:контроллер
        $resource = $request->module . ':' . $request->controller;

        // ACL privilege  - запрошенное действие
        $privilege = $request->action;
        /*  echo $resource;
          echo '-' . $privilege;
          echo '=' . $role; */
        // если не имеем конкретно добавленного ресурса, проверяем
        // привилегии по-умолчанию
        if (!$this->acl->has($resource))
            $resource  = null;
        // доступ запрещен - перенаправляем запрос на страницу входа
        //echo $role.'-'.$resource.'-'.$privilege.'<br/>';
        if (!$this->acl->isAllowed($role, $resource, $privilege)) {
            die('Access denied');
            $request->setModuleName('admin');
            $request->setControllerName('login');
            $request->setActionName('index');
        }
        Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole($role);
    }

}
