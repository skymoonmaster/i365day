<?php

Class DiaryLogicModel extends BasicModel {

    /**
     * @var DiaryLogicModel
     */
    protected static $instances;

    /**
     * @return DiaryLogicModel
     */
    public static function getInstance() {
        if (!isset(self::$instances)) {
            self::$instances = new DiaryLogicModel();
        }
        return self::$instances;
    }

    public function fillDiaryListForHomepage($inputMonth, $inputUserId) {
        $startDate = $this->getStartDate($inputMonth);
        $endDate = $this->getEndDate($inputMonth);
        $startDateTS = strtotime($startDate);
        $endDateTS = strtotime($endDate);
        $filledDiaryList = array();
        $condition = array('user_id' => $inputUserId);
        $diaryList = DiaryModel::getInstance()->getDataListByDateSectionAndConditions($condition, $startDate, $endDate);
        if(is_array($diaryList) && count($diaryList) > 0){
            foreach ($diaryList as $diary) {
                $diaryListByDate[$diary['date']] = $diary;
            }
        }
        for ($i = $startDateTS; $i <= $endDateTS; $i = $i + 86400 ) {
            $currentDate = date('Ymd', $i);
            if (isset($diaryListByDate[$currentDate])) {
                $filler = $diaryListByDate[$currentDate];
            } else {
                $filler = array('date_ts' => $i);
            }
            $filledDiaryList[] = $filler;
        }
        return $filledDiaryList;
    }

    public function getStartDate($inputMonth) {
        $firstDateOfMonthTS = strtotime($inputMonth . '01');
        $startDayByWeek = date('w', $firstDateOfMonthTS);
        return date('Ymd', $firstDateOfMonthTS - $startDayByWeek * 86400);
    }

    public function getEndDate($inputMonth) {
        
        $currentMonth = date('Ym');

        if ($currentMonth < $inputMonth) {
            throw new Exception_BadInput('bad input month');
        }
        if ($currentMonth > $inputMonth) {
            return date('Ymt', strtotime($inputMonth . '01'));
        }

        return date('Ymd');
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
