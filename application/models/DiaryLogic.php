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

    public function fillDiaryListForHomepage($inputMonth) {
        $startDate = $this->getStartDate($inputMonth);
        $endDate = $this->getEndDate($inputMonth, $startDate);
        $startDateTS = strtotime($startDate);
        $endDateTS = strtotime($endDate);
        $filledDiaryList = array();
        $condition = array('user_id', $_SESSION['user_id']);
        $diaryList = DiaryModel::getInstance()->getDataListByDateSectionAndConditions($condition, $startDate, $endDate);
        foreach ($diaryList as $diary) {
            $diaryListByDate[$diary['date']] = $diary;
        }
        for ($i = $startDateTS; $i <= $endDateTS; $i = $i + 86400 ) {
            $currentDate = date('Ymd', $i);
            if (isset($diaryListByDate[$currentDate])) {
                $filler = $diaryListByDate[$currentDate];
            } else {
                $filler = array('date' => $i);
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

    public function getEndDate($inputMonth, $startDate) {
        $currentMonth = date('Ym');

        if ($currentMonth < $inputMonth) {
            throw new Exception_BadInput('bad input month');
        }
        //the home page contains 42 diary (MAX);
        if ($currentMonth > $inputMonth) {
            return date('Ymd', strtotime($startDate) + 41 * 86400);
        }

        return date('Ymd');
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
