<?php
/**
 */

class LoginController extends BasicController {
    const BIND_EMAIL_COOKIE_NAME = 'i001';
    const COOKIE_STRING = 'i365daysh+4kFAADzgC+nx';

    const LOGIN_SUCCESS_COOKIE_NAME = 'i002';
    //60*60*24*30
    const LOGIN_SUCCESS_COOKIE_EXPIRE = 2592000;

    public function doLoginAction() {
        //验证invite code是否合法，合法则继续绑定邮箱
        if ($_SESSION['invite_code']
            && InviteCodeModel::getInstance()->isValidInviteCode($_SESSION['invite_code'])) {
            $this->goToBindEmail();
        }

        $conditions = array(
            'app_uid' => $_SESSION['oauth_user_info']['app_uid'],
            'app_id' => $_SESSION['oauth_user_info']['app_id']
        );
        $userInfo = UserModel::getInstance()->getUserInfoByConditions($conditions);
        var_dump($userInfo);
        if (empty($userInfo)) {
            //TODO 提示用户需要申请
            $this->redirect("/");
        }

        //种Cookie，登录成功。跳转
        $this->setLoginSuccessCookie($userInfo);

        $this->redirect("/home");
    }

    private function setLoginSuccessCookie($userInfo) {
        $cookieValue = Util_EncryptDecrypt::getInstance()->encryptdecrypt($userInfo['user_id'] . self::COOKIE_STRING, '');
        setcookie(self::LOGIN_SUCCESS_COOKIE_NAME, $cookieValue, time() + self::LOGIN_SUCCESS_COOKIE_EXPIRE, '/', 'i365day.com', false, true);
    }

    private function goToBindEmail() {
        $cookieValue = Util_EncryptDecrypt::getInstance()->encryptdecrypt(COOKIE_STRING . time(), '');
        setcookie(self::BIND_EMAIL_COOKIE_NAME, $cookieValue, 0, '/', 'i365day.com', false, true);

        $this->redirect("/login/bindEmail");
    }

    public function bindEmailAction() {
        if (!isset($_COOKIE[self::BIND_EMAIL_COOKIE_NAME]) || empty($_COOKIE[self::BIND_EMAIL_COOKIE_NAME])) {
            $this->redirect("/");
        }

        $decodeString = Util_EncryptDecrypt::getInstance()->encryptdecrypt($_COOKIE[self::BIND_EMAIL_COOKIE_NAME]);
        $result = strpos($decodeString, self::COOKIE_STRING);
        if ($result === false || $result !== 0) {
            $this->redirect("/");
        }

        //TODO 查询是否有重名
    }
}