<?php

define('PROCESS_START_TIME', microtime(true) * 1000);
define('HTTP_DOMAIN', 'http://' . DOMAIN);
define('STATIC_DOMAIN', HTTP_DOMAIN . '/statics');
define('LOG_PATH', '/opt/active/logs');
define('COST_PLATFORM', 'cost_platform');
define('EXPORT_PDF_SUFFIX', 'wireless_report');
define('DEFAULT_CHARGE_DIR', '/opt/active/viewmobile/data');
define('DEFAULT_CHARGE_FILENAME_FROMAT', 'charge_detail_for_%s.csv');

define('DEFAULT_PACKAGE_FILENAME_FROMAT', 'package_detail_%s.csv');
define('DEFAULT_DEVICE_TO_PACKAGE_FILENAME_FROMAT', 'device_to_package_%s.csv');
define('DEFAULT_DURATION', 6);
define('DEFAULT_TOP_AMOUNT', 10);

define('ALLOCATION_TAR_PER_FULL_TIME_EMPLOYEE', 150);
define('TOTAL_FEE_TOP_LIMIT', 20);
define('DEPARTMENT_TOP_LIMIT', 10);
define('TEMP_IMAGES_STORE_DIR', '/opt/active/viewmobile/data');
define('PDF_STORE_DIR', DEFAULT_CHARGE_DIR . DIRECTORY_SEPARATOR . 'pdf');
define('CONTRACT_STORE_DIR', DEFAULT_CHARGE_DIR . DIRECTORY_SEPARATOR . 'contract');
define('PDF_DEL_DIR', DEFAULT_CHARGE_DIR . DIRECTORY_SEPARATOR . '/pdf_del');
define('CHARGE_RAWDATA_STORE_DIR', DEFAULT_CHARGE_DIR . DIRECTORY_SEPARATOR . 'charge/rawdata');
define('USER_DEVICE_STORE_DIR', DEFAULT_CHARGE_DIR . DIRECTORY_SEPARATOR . 'user_device');
define('USER_DEVICE_EXPORT_DIR', DEFAULT_CHARGE_DIR . DIRECTORY_SEPARATOR . 'user_device_export');
define('FETCH_LIMIT_PER_TIME', 10);

define('IMPORT_FETCH_LIMIT_PER_TIME', 10);
define('TRIAL_VERSION_USER_LIMIT', 20);
define('SMTP_FROM', 'ViewMobileReport@activenetwork.com');
define('SMTP_FROM_NAME', 'DoNotReplyViewMobileReport');
define('DEFAULT_LOGO_URL', STATIC_DOMAIN . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'acitve_logo.png');
define('DEFAULT_WWW_DIR', '/opt/active/viewmobile/wwwroot/avm');
define('FINANCE_REPORT_RECEIVER', 'steve.andrews@activenetwork.com');
define('DEPARTMENT_REPORT_SUBJECT', 'Monthly Department Wireless Charge Report_%s');
define('INDIVIDUAL_REPORT_SUBJECT', 'Monthly Wireless Charge Report_%s');
define('FINANCE_REPORT_SUBJECT', 'Monthly Finance Wireless Charge Report_%s');
define('STATISTICS_REPORT_SUBJECT', 'Monthly Statistics Wireless Charge Report_%s');
define('PAYMENT_REMIND_SUBJECT', 'Payment Error Reminder');
define('PASSWD_REMIND_SUBJECT', 'Default Password Reminder');
define('PAYMENT_REMIND_RECEIVER', 'sid.cao@activenetwork.com');
define('CHARGE_CUTOFF_DAY', 15);
define('DEMO_USER_EMAIL', 'avmsupport@activenetwork.com');
define('COOKIE_USER_EMAIL', 'activeEmail');
define('COOKIE_USER_PASSWORD', 'activePassword');
define('MEMCACHE_KEY_PREFIX', 'avm');
define('MEMCACHE_LIFETIME', 1800);
define('CACHE_CLUSTER', '');
define('ENCRYPT_SWITCH', 'open');

date_default_timezone_set('Asia/Chongqing');

define('MAINTENANCE_TIME', '2013-06-20 12:00');
define('MAINTENANCE_REMIND_DAYS', 7);
ini_set("display_errors", '');
ini_set("session.cookie_httponly", 1);
//日志打印相关参数定义
$GLOBALS['LOG'] = array(
    'intLevel' => 7, //notice, warning, fatal
    'strLogFile' => LOG_PATH . DIRECTORY_SEPARATOR . 'cost.log',
    'arrSelfLogFiles' => array(
        'cost' => LOG_PATH . DIRECTORY_SEPARATOR . 'cost.sdf.log',
        'db' => LOG_PATH . DIRECTORY_SEPARATOR . 'db.sdf.log',
    ),
);
?>
