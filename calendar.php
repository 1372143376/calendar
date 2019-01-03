<?php

class calendar

{
    /**
     * 设置日历
     * weekTitle: ['日', '一', '二', '三', '四', '五', '六']
     * @param int year 年份
     * @param int month 月份，取值1~12
     */
    private static $year;
    private static $month;
    private $showMoreDays = true; //如果要显示前后月的日期

    private static $empty_days_count;// 本月第一天是周几，0是星期日，6是星期六


    private static $days_count; // 本月最后一天是几号
    private static $last_date; // 本月最后一天是星期几
    private static $next_date; // 上个月的天数


    public function __construct($day)
    {
        self::$month = date('m', strtotime($day));
        self::$year = date('Y', strtotime($day));
        self::$empty_days_count = date('w', strtotime(self::$year . '-' . self::$month . '-01'));
        self::$days_count = date('d', strtotime(self::$year . '-' . self::$month . '+1 month -1 day'));
        self::$last_date = date('w', strtotime(self::$year . '-' . self::$month . '+1 month -1 day'));
        self::$next_date = date('t', strtotime($day.'-1 month '));
    }

    /**上个月的日期
     *
     * @return array
     */
    public function _getUpMonthData()
    {
        $empty_days = [];
        $prev_month = self::$month - 1 == 0 ? 12 : self::$month - 1;             // 上个月的月份数
        $prev_year = self::$month - 1 == 0 ? self::$year - 1 : self::$year;
        for ($i = 0; $i < self::$empty_days_count; $i++) {

            array_push($empty_days, ['day' => -1,

                'month' => $prev_month,
                'year' => $prev_year,]);
        }
        return $empty_days;
    }


    /**
     * 下个月的日期
     */
    public function _getDownMonthData()
    {
        $empty_days_last = [];
        $next_month = self::$month + 1 == 13 ? 1 : self::$month + 1; // 下个月的月份数
        $next_year = self::$month + 1 == 13 ? self::$year + 1 : self::$year;
        for ($i = 0; $i < 6 - self::$last_date;
             $i++) {
            array_push($empty_days_last, ['day' => -2,

                'month' => $next_month,
                'year' => $next_year,]);
        }
        return $empty_days_last;
    }

    /**
     * 本月的日期
     */
    public function _getNowMonthData($up_month, $down_month)
    {
        $temp = [];
        for ($i = 1; $i < self::$days_count;
             $i++) {
            array_push($temp, ['day' => $i,

                'month' => self::$month,
                'year' => self::$year,]);
        }
        $days_rang = $temp;
        // 本月
        $days = array_merge($up_month, $days_rang, $down_month); // 上个月 + 本月 + 下个月
        // 如果要显示前后月份的日期
        if ($this->showMoreDays) {
            // 显示下月的日期
            foreach ($days as $k => $val) {
                if ($val['day'] == -2) {
                    $index = $k;
                    break;
                }
            }
            if ($index != -1) {
                $length = count($days);
                $count = $length - $index;
                for ($i = 1; $i <= $count; $i++) {
                    $days[$index + $i - 1]['day'] = $i;
                }
            }

            // 显示上月的日期
            foreach ($days as $k => $val) {
                if ($val['day'] == 1) {
                    $index = $k - 1;
                    break;
                }
            }
            if ($index != -1) {
                $last_month_day = self::$next_date;
                for ($i = 0; $i <= $index; $i++) {
                    $days[$i]['day'] = $last_month_day - $index + $i;
                }
            }
        }

        return $days;
    }

}

$day = '2019-03-02';
$calender = new calendar($day);
$up_month = $calender->_getUpMonthData();

$down_month = $calender->_getDownMonthData();
//var_dump($down_month);die;
var_dump($calender->_getNowMonthData($up_month, $down_month));
