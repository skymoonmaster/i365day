<?php
/**
 * Config for DBProxy clusters
 *
 * @category           DB
 * @package		DBProxy
 * @version		$Revision: 1.2 $
 */
class Conf_DBProxy
{
	/**
	 * DBProxy集群编号定义
	 * @var int
	 */
	const DBPROXY_COSTPLATFORM_INDEX = 0;

	/**
	 * 数据库连接失败时的重试次数(包括失败、超时等)
	 * @var int
	 */
	const RETRY_TIMES = 3;

	/**
	 * 单个机房数据库连接失败时的重试次数(包括失败、超时等)
	 * @var int
	 */
	const RETRY_TIMES_PER_IDC = 2;

	/**
	 * MySQL 链接超时时间（秒）
	 * 用于设置 MYSQLI_OPT_CONNECT_TIMEOUT
	 * @var int
	 */
	const CONNECTION_TIMEOUT = 1;

	/**
	 * 数据库库名与集群编号的映射关系表，说明每个数据库部署在哪个集群上
	 * 每增加一个数据库时必须在这里增加一个映射记录，如果不增加映射记录，
	 * 则默认认为该数据库部署在第一个集群上
	 * @var array
	 */
	static $arrDatabaseMap = array(
		'cost_platform'         => array(self::DBPROXY_COSTPLATFORM_INDEX, 'utf8'),
		'cost_platform_mis'	=> array(self::DBPROXY_COSTPLATFORM_INDEX, 'utf8'),
		'test'			=> array(self::DBPROXY_COSTPLATFORM_INDEX, 'utf8'),
	);

	/**
	 * DBProxy集群的机器列表、访问集群时所用的用户名、密码、端口号、失败重试次数
	 * @var array
	 */
	static $arrDBProxyServer = array(
		self::DBPROXY_COSTPLATFORM_INDEX => array(	//cost-platform dbproxy集群
			'username' => 'avm',
			'password' => 'sdlf1n4',
			'port' => 3306,
			'dx' => array(
				'qastable-viewmobile-db-01.dev.activenetwork.com',
				),
			'lt' => array(
				'qastable-viewmobile-db-01.dev.activenetwork.com',
				),
		),
	);
}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
