<?php
/**
 * Created by PhpStorm.
 * User: zhangyuyi
 * Date: 14-1-17
 * Time: 下午9:44
 */

class Util_TimeFormatter
{
    const TIME_FORMAT_MINITE = "%s分钟前";
    const TIME_FORMAT_TODAY = "今天 %s";
    const TIME_FORMAT_MONTH = "%s月%s日";
    const TIME_FORMAT_YEAR = "%s-%s-%s";

    public static function timeFormat($time)
    {
        $now = time();
        if (strpos($time, '-') !== false) {
            $time = strtotime($time);
        }
        if (($dur = $now - $time) < 3600) {
            $minutes = ceil($dur / 60);
            if ($minutes <= 0) {
                $minutes = 1;
            }
            if ($minutes == 1) {
                $time = '刚刚';
            } else {
                $time = sprintf(self::TIME_FORMAT_MINITE, $minutes);
            }
        } elseif (date("Ymd", $now) == date("Ymd", $time)) {
            $time = sprintf(self::TIME_FORMAT_TODAY, date("H:i", $time));
        } else {
            if (date("Y") == date("Y", $time)) {
                $time = sprintf(self::TIME_FORMAT_MONTH, date("n", $time), date("j", $time)) . " " . date("H:i", $time);
            } else {
                $time = sprintf(self::TIME_FORMAT_YEAR, date("Y", $time), date("n", $time), date("j", $time)) . " " . date("H:i", $time);
            }
        }

        return $time;
    }
}