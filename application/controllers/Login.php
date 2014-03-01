<?php
/**
 */

class LoginController extends BasicController {
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

            UserAuthModel::getInstance()->setBindEmailCookie();
            $this->redirect("/login/bindEmail");

            return ;
        }

        $_SESSION['user_id'] = $userInfo['user_id'];
        //种Cookie，登录成功。跳转
        UserAuthModel::getInstance()->setLoginSuccessCookie($userInfo['user_id']);

        $this->redirect("/home");
    }

    public function bindEmailAction() {
        if (isset($this->userInfo['email']) && !empty($this->userInfo['email'])) {
            $this->redirect("/home");
        }

        $result = UserAuthModel::getInstance()->isBindEmail();
        if ($result) {
            $this->redirect("/home");
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
        UserAuthModel::getInstance()->deleteBindEmailCookie();

        //种“正式用户”Cookie，30天后失效
        UserAuthModel::getInstance()->setLoginSuccessCookie($_SESSION['user_id']);

        echo Util_Result::success('success');

        return ;
    }

    public function doLogoutAction() {
        UserAuthModel::getInstance()->deleteLoginSuccessCookie($_SESSION['user_id']);

        $this->redirect("/index");
    }
}