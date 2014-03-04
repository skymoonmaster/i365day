<?php
/**
 */

class BetaApplicationModel extends BasicModel {
    protected static $instances;

    protected $table = 'beta_application';

    const TO_AUDIT = 1;
    const PASSED = 2;
    const REFUSED = 3;


    public static function getInstance() {
        if (!isset(self::$instances)) {
            self::$instances = new BetaApplicationModel();
        }

        return self::$instances;
    }

}