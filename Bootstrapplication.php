<?php

/**
 * 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf_Bootstrap_Abstract {

    /**
     * 自定义视图引擎
     */
    public function _initSmarty(Yaf_Dispatcher $dispatcher) {
        $smarty = new Smarty_Adapter(null, Yaf_Application::app()->getConfig()->get("smarty")->toArray());
        Yaf_Dispatcher::getInstance()->setView($smarty);
    }

    public function _initSessionStart() {
        session_start();
    }

    public function _initLoginCheck() {
        #LoginModel::getInstance()->checkLogin();
    }
    public function _initRegistCheck(){
        #RegisterModel::getInstance()->checkRegSteps();
    }
    /**
     * 注册一个插件
     * 插件的目录是在application_directory/plugins
     */
    public function _initPlugin(Yaf_Dispatcher $dispatcher) {
        $user = new UserPlugin();
        $dispatcher->registerPlugin($user);
    }

}

?>
