<?php

Class InviteCodeModel extends BasicModel {
    protected static $instances;

    protected $table = 'invite_code';

    public static function getInstance() {
        if (!isset(self::$instances)) {
            self::$instances = new InviteCodeModel();
        }

        return self::$instances;
    }

    public function isValidInviteCode($inviteCode) {

    }

    public function cancelInviteCode($inviteCode) {

    }
}
