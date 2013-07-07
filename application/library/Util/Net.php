<?php
class Util_Net {
    private static $instance = null;
    /**
     * @return Util_Net
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Util_Net();
        }
        return self::$instance;
    }

    /**
     * Get the real remote client's IP
     * @return string
     */
    public static function getClientIP() {
        if (isset($_SERVER['HTTP_CLIENTIP'])) {
            $ip = $_SERVER['HTTP_CLIENTIP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) &&
                $_SERVER['HTTP_X_FORWARDED_FOR'] != '127.0.0.1') {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = $ips[0];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = '127.0.0.1';
        }

        $pos = strpos($ip, ',');
        if ($pos > 0) {
            $ip = substr($ip, 0, $pos);
        }

        return trim($ip);
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=90 noet: */
?>
