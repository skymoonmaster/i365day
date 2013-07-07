<?php
/**
 * Config for DBProxy clusters
 *
 * @category           DB
 * @package		DBProxy
 * @version		$Revision: 1.2 $
 */
class Conf_AMS
{
    static $gateway = 'https://mstest.active.com/MS/MSServer';
    static $merchantAccounts = array(
        'cc' => array(
            'merchantUser' => 'AMS34USD',
            'merchantPassword' => 'AMS34USD',
            'merchantDescriptor' => 'active view mobile',
            'accountRetentionDate' => '2016-10-12'
        )
    );
}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
