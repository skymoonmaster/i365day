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
        if (isset($_SESSION['invite_code'])
            && InviteCodeModel::getInstance()->isValidInviteCode($_SESSION['invite_code'])) {

            //TODO 有邀请码的同学直接从邀请码中解析出email，并跳过绑定email步骤。
            $this->goToBindEmail();

            return ;
        }

        $conditions = array(
            'app_uid' => $_SESSION['user_info']['app_uid'],
            'app_id' => $_SESSION['user_info']['app_id']
        );
        $userInfo = UserModel::getInstance()->getUserInfoByConditions($conditions);
        if (empty($userInfo)) {
            //TODO 暂不执行该逻辑
            //TODO 提示用户需要申请
            //$this->redirect("/#apply");

            $this->goToBindEmail();

            return ;
        }

        $_SESSION['user_id'] = $userInfo['user_id'];
        //种Cookie，登录成功。跳转
        $this->setLoginSuccessCookie($userInfo['user_id']);

        $this->redirect("/home");
    }

    private function setLoginSuccessCookie($userId) {
        $cookieValue = Util_EncryptDecrypt::getInstance()->encryptdecrypt($userId . self::COOKIE_STRING, '');
        setcookie(self::LOGIN_SUCCESS_COOKIE_NAME, $cookieValue, time() + self::LOGIN_SUCCESS_COOKIE_EXPIRE, '/', 'i365day.com', false, true);
    }

    private function goToBindEmail() {
        $this->setBindEmailCookie();

        $this->redirect("/login/bindEmail");
    }

    private function setBindEmailCookie() {
        $cookieValue = Util_EncryptDecrypt::getInstance()->encryptdecrypt(self::COOKIE_STRING . time(), '');
        setcookie(self::BIND_EMAIL_COOKIE_NAME, $cookieValue, 0, '/', 'i365day.com', false, true);
    }

    private function deleteBindEmailCookie() {
        setcookie(self::BIND_EMAIL_COOKIE_NAME, '', time() - 3600, '/', 'i365day.com', false, true);
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

        $this->getView()->assign('city', $_SESSION['user_info']['location']);
        $this->getView()->assign('avatar', $_SESSION['user_info']['avatar']);
        $this->getView()->assign('nick_name', $_SESSION['user_info']['nick_name']);
    }

    public function doBindEmailAction() {
        Yaf_Dispatcher::getInstance()->autoRender(false);

        $email = $this->getAjaxParam('email');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo Util_Result::failure('invalid email');

            return ;
        }

        $_SESSION['user_info']['email'] = $email;
        $userId = UserModel::getInstance()->create($_SESSION['user_info']);
        if (empty($userId)) {
            echo Util_Result::failure('service error, please try again.');

            return ;
        }

        $_SESSION['user_id'] = $userId;

        if (isset($_SESSION['invite_code'])) {
            InviteCodeModel::getInstance()->cancelInviteCode($_SESSION['invite_code']);
        }

        //写入用户信息成功后，删除绑定邮箱Cookie，防止用户多次进入绑定邮箱页面。
        $this->deleteBindEmailCookie();

        //种“正式用户”Cookie，30天后失效
        $this->setLoginSuccessCookie($_SESSION['user_info']['user_id']);

        echo Util_Result::success('success');

        return ;
    }
}