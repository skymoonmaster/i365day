<?php

class UserPlugin extends Yaf_Plugin_Abstract {
    
    public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
        #echo "Plugin routerStartup called <br/>\n";
    }

    public function routerShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
        #echo "Plugin routerShutdown called <br/>\n";
    }

    public function dispatchLoopStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
        #echo "Plugin DispatchLoopStartup called <br/>\n";
    }

    public function preDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
        #echo "Plugin PreDispatch called <br/>\n";
    }

    public function postDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
        #echo "Plugin postDispatch called <br/>\n";
    }
  
    public function dispatchLoopShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
    	$username = (isset($_SESSION['username'])) ? $_SESSION['username'] : '';
    	Util_CLog::notice("uname[$username]");
    	#echo "Plugin DispatchLoopShutdown called <br/>\n";
    }
  
    public function preResponse(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
        #echo "Plugin PreResponse called <br/>\n";
    }
}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
