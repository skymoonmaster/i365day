<?php

class BasicController extends Yaf_Controller_Abstract {

    protected function ajaxParam($value) {
        if (!empty($value)) {
            $value = trim(urldecode($value));
        }
        return $value;
    }

    protected function setLoginInfo() {
        $companyAccount = CompanyAccountModel::getInstance()->getCompanAccountById($_SESSION['user_id']);
        $cominfo = CompanyinfoModel::getInstance()->getCompanyInfoByCid($_SESSION['co_id']);
        $lincenseInfo = LicenseModel::getInstance()->getLincenseInfoByCompanyId($_SESSION['co_id']);
        $this->getView()->assign('cominfo', $cominfo);
       
        $this->getView()->assign('companyAccountInfo', $companyAccount);

        if (is_array($lincenseInfo) && count($lincenseInfo) > 0) {
            $this->getView()->assign('lincenseInfo', $lincenseInfo);
        } else {
            $this->getView()->assign('lincenseInfo', array('license_level' => 0));
        }
    }

    protected function init() {
        if (LoginModel::getInstance()->isLogin()) {
            $this->setLoginInfo();
        }
    }

}

?>