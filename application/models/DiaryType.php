<?php

Class DiaryTypeModel extends BasicModel {

    /**
     * @var DiaryModel
     */
    protected static $instances;
    public static $diaryTypeIdToName = array(0 => '周报', 1 => '线下聚会', 2 => '黑板报', 3 => '明星同学', 4 => '其他');
    
}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
