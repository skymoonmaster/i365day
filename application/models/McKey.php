<?php

/* * *************************************************************************^M
 *
 * Copyright (c)2013 Active.com, Inc. All Rights Reserved^M
 *
 * ************************************************************************ */

/**
 * Memcache key manager class
 *
 * @author  scao(sid.cao@activenetwork.com)
 * @version $Revision: 1.0 $
 */
class McKeyModel {

    /**
     * @var McKeyModel 
     */
    protected static $instances;

    /**
     * @return McKeyModel 
     */
    public static function getInstance() {
        if (!isset(self::$instances)) {
            self::$instances = new McKeyModel();
        }
        return self::$instances;
    }

    const MEMCACHE_KEY_PREFIX = 'i365day';

    /**
     * companyInfo
     */

    const PREFIX_COMPANY_INFO = '_company_';
    
     /**
     * mission
     */

    const PREFIX_MISSION = '_mission_';

    /**
     *
     * @param  int    $companyId
     * @param  string $item
     * @return string
     */
    public static function forCompanyInfo($item, $companyId, $date) {
        return self::MEMCACHE_KEY_PREFIX . self::PREFIX_COMPANY_INFO . $item . '_' . $companyId . '_' . $date;
    }
    
    /**
     *
     * @param  int    $companyId
     * @param  string $item
     * @return string
     */
    public static function forMissionLock($missionType) {
        return self::MEMCACHE_KEY_PREFIX . self::PREFIX_MISSION . $missionType . '_LOCKER';
    }

}
