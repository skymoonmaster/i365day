<?php

Class Util_EncryptDecrypt {

    private static $instance;

    const KEY = '96325POIUA';

    public static $arrEncryptField = array(
        'user_email',
        'charge_account_num',
        'device_num',
        'monthly_charge',
        'usage',
        'voice',
        'data',
        'message',
        'roaming',
        'equipment',
        'adjustment_credit',
        'taxes_government',
        'others',
        'total_fee',
        'total_offender_fee',
        'owner_name',
        'owner_email',
        'contact_name',
        'email_address',
        'user_full_name',
        'user_real_name',
        'user_first_name',
        'user_last_name',
        'user_name',
        'real_name',
        'send_email_from',
        'send_email_from_name',
        'send_email_to',
        'send_email_cc',
        'send_email_bcc',
        'ECPD Profile ID', 'Account Number', 'Invoice Number', 'Charge', 'Allowance', 'Used', 'Billable',
        'Billing Account Number', 'Wireless Number', 'User Name', 'Amount', 'Total', 'Total Charge', 'Monthly Charge', 'Minutes Included in Plan', 'Minutes Used',
        'Billing Account',
        'Report Level',
        'User Number',
        'Monthly Service Fee',
        'Data Monthly Service Fee',
        'Text Messaging Monthly Service Fee',
        'Activation Fee',
        'Call Display',
        'Voice Mail',
        '911 Fee',
        'Directory Assistance',
        'System Access Fee',
        'System Access Fee Credit',
        "Gov't Regulatory Recovery Fee",
        "Gov't Regulatory Recovery Fee Credit",
        'Essential Value Packs', 'Equipment Charges',
        'Airtime Overage Charge',
        'Canadian Long Distance Charge', 'US Long Distance Charge', 'International Long Distance Charge', 'US Airtime Roaming Charges',
        'International Airtime Roaming Charges', 'US Roaming Long Distance Charge', 'International Roaming Long Distance Charges',
        'Non Roaming Text Sent Charges', 'Non Roaming Text Received Charges', 'Roaming Text Sent Charges', 'Roaming Text Received Charges',
        'US Text Sent', 'US Text Received', 'International Text Sent', 'International Text Received', 'Data Overage Charges', 'US Data Roaming Charges',
        'International Data Roaming Charges', 'Early Cancellation Payment', 'Premium Services', 'Other Charges', 'Credits and Discounts', 'WiFi Hotspot Charges',
        'UMA US Voice Charges', 'UMA INT Voice Charges', 'UMA US Voice Roaming Tax', 'UMA INT Voice Roaming Tax', 'UMA Local Charges', 'UMA Long Distance Charges',
        'MMS Canadian Charges', 'MMS US/International Charges', 'Subtotal', 'GST', 'PST', 'HST', 'QST', 'Total Current Charges', 'Service ID 1', 'Service ID 2',
        'Service ID 3', 'Service ID 4', 'Cost Centre ID1', 'Cost Centre ID2', 'Cost Centre ID3', 'Cost Centre ID4',
        'Subscribe Number','Charge Type','Charge Description','Amount','Origin Category'
    );
    public static $encryptTable = array(
        'company_account',
        'charge_account',
        'charge',
        'device',
        'user_device',
        'department',
        'chargeback_send_emaillist',
        'user',
        'send_email_mission',
        'verizon_charge',
        'att_charge',
        'rogers_charge',
        'tmobile_charge'
    );

    /**
     * 
     * @return Util_EncryptDecrypt
     */
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Util_EncryptDecrypt();
        }
        return self::$instance;
    }

    /**
     * 
     * @return str
     */
    public function encryptdecrypt($string, $operation = 'DECODE', $key = '', $expiry = 3600) {
        if (defined('ENCRYPT_SWITCH') && ENCRYPT_SWITCH == '') {
            return $string;
        }
        if (strlen($string) == 0) {
            return '';
        }
        $ckey_length = 16;
        // 随机密钥长度 取值 0-32;
        // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
        // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
        // 当此值为 0 时，则不产生随机密钥
        //$key = md5($key ? $key : EABAX::getAppInf('KEY'));
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 6, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'DECODE') {
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc . str_replace('=', '', base64_encode($result));
        }
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=90 noet: */
?>
