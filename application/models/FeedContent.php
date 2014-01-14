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

        $sql = "SELECT * FROM `{$this->table}` WHERE `feed_id` IN (" . implode(',', $feedIds) . ")";
        $rows = $this->db->queryAllRows($sql);
        if (empty($rows)) {
            return array();
        }

        return $rows;
	} 
}

?>