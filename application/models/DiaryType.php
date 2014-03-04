<?php

Class DiaryTypeModel extends BasicModel {

    /**
     * @var DiaryModel
     */
    protected static $instances;
    public static $diaryTypeIdToName = array(1 => '365周报', 2 => '365线下聚会', 3 => '365黑板报', 4 => '365明星同学', '5' =>'其他');
    
}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
