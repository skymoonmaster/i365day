<?php
	

class FeedContentModel extends BasicModel {
	protected static $instances;
	
	protected $table = 'feed_content';

    public static function getInstance() {
        if (!isset(self::$instances)) {
            self::$instances = new FeedContentModel();
        }

        return self::$instances;
    }

	public function getFeedContent($feedInfos) {
        $feedIds = array();
        array_walk($feedInfos, function($v) use(&$feedIds) {$feedIds[] = $v['feed_id'];});

        $sql = "SELECT * FROM `{$this->table}` WHERE `feed_id` IN (" . implode(',', $feedIds) . ") ORDER BY `create_time` DESC";
        $rows = $this->db->queryAllRows($sql);
        if (empty($rows)) {
            return array();
        }

        array_walk($rows, function(&$row) {$row['formatted_time'] = Util_TimeFormatter::timeFormat($row['create_time']);});

        return $rows;
	} 
}

?>