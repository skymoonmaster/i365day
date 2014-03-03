<?php

Class UserAuthModel extends BasicModel {

    protected static $instances;

    const COOKIE_STRING = 'i365daysh+4kFAADzgC+nx';

    const BIND_EMAIL_COOKIE_NAME = 'i001';
    const LOGIN_SUCCESS_COOKIE_NAME = 'i002';

    //60*60*24*30
    const LOGIN_SUCCESS_COOKIE_EXPIRE = 2592000;

    public static function getInstance() {
        if (!isset(self::$instances)) {
            self::$instances = new UserAuthModel();
        }
        return self::$instances;
    }

    public function isLogin() {
        $decodeString = Util_EncryptDecrypt::getInstance()->encryptdecrypt($_COOKIE[self::LOGIN_SUCCESS_COOKIE_NAME]);

        list($userId, $cookieString) = explode('@', $decodeString);

        if ($cookieString !== self::COOKIE_STRING) {
            return array(false, null);
        }

        return array(true, $userId);
    }

    public function deleteLoginSuccessCookie($userId) {
        $cookieValue = Util_EncryptDecrypt::getInstance()->encryptdecrypt($userId . '@' . self::COOKIE_STRING, '');

        setcookie(self::LOGIN_SUCCESS_COOKIE_NAME, $cookieValue, time() - 3600, '/', 'i365day.com', false, true);
    }

    public function setLoginSuccessCookie($userId) {
        $cookieValue = Util_EncryptDecrypt::getInstance()->encryptdecrypt($userId . '@' . self::COOKIE_STRING, '');

        setcookie(self::LOGIN_SUCCESS_COOKIE_NAME, $cookieValue, time() + self::LOGIN_SUCCESS_COOKIE_EXPIRE, '/', 'i365day.com', false, true);
    }

    public function isBindEmail() {
        if (!isset($_COOKIE[self::BIND_EMAIL_COOKIE_NAME]) || empty($_COOKIE[self::BIND_EMAIL_COOKIE_NAME])) {
            return false;
        }

        $decodeString = Util_EncryptDecrypt::getInstance()->encryptdecrypt($_COOKIE[self::BIND_EMAIL_COOKIE_NAME]);
        $result = strpos($decodeString, self::COOKIE_STRING);
        if ($result === false || $result !== 0) {
            return false;
        }

        return true;
    }

    public function setBindEmailCookie() {
        $cookieValue = Util_EncryptDecrypt::getInstance()->encryptdecrypt(self::COOKIE_STRING . time(), '');

        setcookie(self::BIND_EMAIL_COOKIE_NAME, $cookieValue, 0, '/', 'i365day.com', false, true);
    }

    public function deleteBindEmailCookie() {
        setcookie(self::BIND_EMAIL_COOKIE_NAME, '', time() - 3600, '/', 'i365day.com', false, true);
    }
}