<?php

date_default_timezone_set("Europe/Vilnius");

class employee_work_hours_functions {

    protected $workStats;
    private $group;
    private $department;
    private $subdivision;
    public $year;
    public $month;
    public $day_passed_time;
    public $work_day_end_time;

    function __construct($g, $d, $s, $y, $m) {
        $this->group = $g;
        $this->department = $d;
        $this->subdivision = $s;
        $this->year = $y;
        $this->month = $m;
        $this->set_times();
    }

    //
    public function get_option_value($option_name) {
        global $conn;
        $option_value = null;
        $query = "SELECT `option_value` FROM `settings` "
            . "WHERE `option_name` = '{$option_name}' AND `group_id` = {$this->group} AND `department_id` = {$this->department} AND `subdivision_id` = {$this->subdivision}";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $option_value = unserialize($row['option_value']);
        }
        return $option_value;
    }

    // nereik
//    private function work_hours_query($user_id) {
//        return "SELECT "
//                . "DATE_FORMAT(`fixed_day_start`, '%H:%i') AS fixed_day_start, "
//                . "DATE_FORMAT(`fixed_day_end`, '%H:%i') AS fixed_day_end, "
//                . "DATE_FORMAT(`work_hours`, '%H:%i') AS work_hours, `date`, `violation`, `user`, `free_day`, `free_hours`, `free_minutes` "
//                . "FROM `employee_work_stats` WHERE user = {$user_id} AND date LIKE '%{$this->year}-{$this->month}%'";
////        return "SELECT "
////                . "DATE_FORMAT(ewh.fixed_day_start, '%H:%i') AS fixed_day_start, "
////                . "DATE_FORMAT(ewh.fixed_day_end, '%H:%i') AS fixed_day_end, DATE_FORMAT(ewh.work_hours, '%H:%i') AS work_hours, ewh.date, v.violation_type, ewh.user, v.user "
////                . "FROM `employee_working_hours` ewh "
////                . "LEFT JOIN `violations` v ON v.violation_date = ewh.date AND v.user = ewh.user "
////                . "WHERE ewh.user = {$user_id} AND ewh.date LIKE '%{$this->year}-{$this->month}%' ORDER BY ewh.date ASC";
//    }

    // palikt
    public function fetch_user_list($user_id, $date = null) {
        global $conn;
        $workers = [];
        $conn->query("set names 'utf8'");
        $query = "SELECT id, name, surname, position FROM `user` ";
        if (!is_null($user_id)) {
            $query .= "WHERE id = {$user_id}";
        } else {
            $query .= "WHERE role = '1' AND group_id = {$this->group} AND department_id = {$this->department} AND subdivision_id = {$this->subdivision}";
        }
        if ($_SESSION['position'] == 'Praktikos vadovas') {
            $query .= " AND status = 2";
        } else {
            $query .= " AND status != 0 AND status != 4";
        }
        if (is_null($user_id) && !is_null($date)) {
            $query .= " AND started_working <= '{$date}'";
        }
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $temp = array();
            $temp['id'] = $row['id'];
            $temp['name'] = $row['name'];
            $temp['surname'] = $row['surname'];
            $temp['position'] = $row['position'];
            $workers[] = $temp;
        }
        return $workers;
    }

    // nereik ! workHoursList turi buti su work_hours, free_days, free_hours,
//    public function work_hours_data($user_id) {
//        global $conn;
//        $workHoursList = Array();
//        $query = $this->work_hours_query($user_id);
//        $result = $conn->query($query);
//        if ($result->num_rows > 0) {
//            while ($row = $result->fetch_assoc()) {
//                $freeHours = unserialize($row['free_hours']);
//                $freeMinutes = unserialize($row['free_minutes']);
//                $violations = unserialize($row['violation']);
//                $freedays = $row['free_day'];
//
////                if (!array_key_exists($row['date'], $workHoursList)) {
////                    $this->check_violation($row['violation_type'], $row['date']);
////                    $workHoursList[$row['date']] = array("day_start" => $row['fixed_day_start'], "day_end" => $row['fixed_day_end'],
////                        "work_hours" => $row['work_hours'], "violation" => array($row['violation_type']));
////                }
////                $workHoursList[$row['date']]["free_hours"][key($freeHours)] = $freeHours[key($freeHours)];
////                $workHoursList[$row['date']]["free_minutes"][key($freeMinutes)] = $freeMinutes[key($freeMinutes)];
//                $workHoursList[$row['date']] = array("day_start" => $row['fixed_day_start'], "day_end" => $row['fixed_day_end'],
//                    "work_hours" => $row['work_hours'], 'free_day' => $row['free_day']);
//                foreach ($freeHours as $key) {
//                    $workHoursList[$row['date']]["free_hours"] = $key;
//                }
//                foreach ($freeMinutes as $key => $value) {
//                    $workHoursList[$row['date']]["free_minutes"] = $key;
//                }
//                foreach ($violations as $key => $value) {
//                    $workHoursList[$row['date']]["violation"][] = $value;
//                }
//
//                foreach ($freedays as $key) {
//                    $workHoursList[$row['date']]["free_day"] = $key;
//                }
//            }
//        }
//        return $workHoursList;
//    }




    //excellio failas
    public function set_vacation_days($vacationList, $option, $startRow, $startCol, $month_days) {
        global $worksheet, $vacation_full_border_no_bold_colored, $vacation_full_border_bold_colored, $contentLabels;
        $style = $option === "work_hours" ? $vacation_full_border_bold_colored : $vacation_full_border_no_bold_colored;
        $style->setFgColor($contentLabels['vacation']['color']);
        $from = $to = null;
        for ($i = 1; $i <= $month_days; $i++) {
            $day = $i <= 9 ? "0" . $i : $i;
            $array_key = $this->year . "-" . $this->month . "-" . $day;
            if (array_key_exists($array_key, $vacationList)) {
                $from = $vacationList[$array_key]['from'];
                $to = $vacationList[$array_key]['to'];
            }
            if (!is_null($from) && strtotime($array_key) <= strtotime($to)) {
                $worksheet->write($startRow, $startCol + $i, "-", $style);
            } else {
                $from = $to = null;
            }
        }
    }

    // palikt
    public function set_cell_style($workHoursList, $array_key, $option) {
        global $full_border_no_bold_center, $full_border_bold_center, $full_border_bold_center_colored, $full_border_no_bold_center_colored;
        $style = $full_border_no_bold_center;
        if ($option === "day_start" && count($workHoursList[$array_key]['violation']) > 0) {
            $style = in_array(1, $workHoursList[$array_key]['violation']) ? $full_border_no_bold_center_colored : $full_border_no_bold_center;
        } elseif ($option === "day_start" && count($workHoursList[$array_key]['violation']) === 0) {
            $style = $full_border_no_bold_center;
        }
        if ($option === "day_end" && count($workHoursList[$array_key]['violation']) > 0) {
            $style = in_array(3, $workHoursList[$array_key]['violation']) ? $full_border_no_bold_center_colored : $full_border_no_bold_center;
        } elseif ($option === "day_end" && count($workHoursList[$array_key]['violation']) === 0) {
            $style = $full_border_no_bold_center;
        }
        if ($option === "work_hours") {
            $workHoursTimeInt = strtotime("1970-01-01 {$workHoursList[$array_key][$option]}:00 UTC");
            $style = $workHoursTimeInt < 28800 && !is_null($workHoursList[$array_key][$option]) ? $full_border_bold_center_colored : $full_border_bold_center;
        }
        if ($option === "free_hours") {
            $workHoursTimeInt = strtotime("1970-01-01 {$workHoursList[$array_key][$option]}:00 UTC");
            $style = $workHoursTimeInt < 28800 && !is_null($workHoursList[$array_key][$option]) ? $full_border_bold_center_colored : $full_border_bold_center;
        }

        return $style;
    }

    // palikt, bei perdaryt
    public function set_work_day_times($workHoursList, $startRow, $startCol, $monthDaysCount, $option, $holidayList) {
        global $worksheet, $fullBorderNoBoldNoFg, $fullBorderBoldNoFg;
        for ($i = 1; $i <= $monthDaysCount; $i++) {
            $day = $i <= 9 ? "0" . $i : $i;
            $array_key = $this->year . "-" . $this->month . "-" . $day;
            $week_day = date("D", strtotime($array_key));
            $style = $this->set_cell_style($workHoursList, $array_key, $option);
            if (array_key_exists($array_key, $workHoursList)) {
                $worksheet->write($startRow, $startCol + $i, $workHoursList[$array_key][$option], $style);
            } elseif ($week_day !== "Sat" && $week_day !== "Sun" && !array_key_exists($array_key, $holidayList)) {
                $style = $option === "work_hours" ? $fullBorderBoldNoFg : $fullBorderNoBoldNoFg;
                $worksheet->write($startRow, $startCol + $i, "-", $style);
            }
        }
    }

    //palikt
    public function set_cell_style_html($workHoursList, $array_key, $option) {
        $style = "";
        if ($option === "day_start" && count($workHoursList[$array_key]['violation']) > 0) {
            $style = in_array(1, $workHoursList[$array_key]['violation']) ? " violation" : " no_violation";
        } elseif ($option === "day_start" && count($workHoursList[$array_key]['violation']) === 0) {
            $style = " no_violation";
        }
        if ($option === "day_end" && count($workHoursList[$array_key]['violation']) > 0) {
            $style = in_array(3, $workHoursList[$array_key]['violation']) ? " violation" : " no_violation";
        } elseif ($option === "day_end" && count($workHoursList[$array_key]['violation']) === 0) {
            $style = " no_violation";
        }
        if ($option === "work_hours") {
            foreach ($workHoursList[$array_key]['violation'] as $key => $value) {
                if (is_null($value)) {
                    unset($workHoursList[$array_key]['violation'][$key]);
                }
            }


            $workHoursTimeInt = strtotime("1970-01-01 {$workHoursList[$array_key][$option]}:00 UTC");
            $style = $workHoursTimeInt < 28800 && !is_null($workHoursList[$array_key][$option]) && count($workHoursList[$array_key]['violation']) > 0 ? " violation" : " no_violation";
        }
        return $style;
    }

    //perrasyt per naujo
    public function set_month_days_hybrid_html($workHoursList, $monthDaysCount, $option, $holidayList, $vacationList, $holidays) {
        $from = $to = null;
        for ($i = 1; $i <= $monthDaysCount; $i++) {
            $day = $i <= 9 ? "0" . $i : $i;
            $array_key = $this->year . "-" . $this->month . "-" . $day;
            $week_day = date("D", strtotime($array_key));
            if (array_key_exists($array_key, $workHoursList) && ($week_day !== "Sat" && $week_day !== "Sun") && ($workHoursList[$array_key]['free_day'] !== $array_key) && !$holidays->is_holiday($this->year, $this->month, $i) && is_null($from) ) {
                echo "<td class='time_cell " . $this->set_cell_style_html($workHoursList, $array_key, $option) . "'>{$workHoursList[$array_key][$option]}</td>";
            } elseif (array_key_exists($array_key, $workHoursList && ($week_day !== "Sat" && $week_day !== "Sun"))) {
                echo "<td class='time_cell " . $this->set_cell_style_html($workHoursList, $array_key, $option) . "'>{$workHoursList[$array_key][$option]}</td>";
            } elseif (($workHoursList[$array_key]['free_day'] == $array_key) && ($workHoursList[$array_key]['free_hours'] !== is_null) && ($week_day !== "Sat" && $week_day !== "Sun")) {
                echo "<td class='time_cell free-day'>-</td>";
            } elseif (array_key_exists($array_key, $vacationList)) {
                $from = $vacationList[$array_key]['from'];
                $to = $vacationList[$array_key]['to'];
            } elseif ($holidays->is_holiday($this->year, $this->month, $i) && is_null($from)) {
                echo "<td class='time_cell holiday'>-</td>";
            } elseif (($week_day === "Sat" || $week_day === "Sun") && is_null($from)) {
                echo "<td class='time_cell weekend'>-</td>";
            } elseif (!array_key_exists($array_key, $workHoursList) && is_null($workHoursList[$array_key]['free_hours']) && ($workHoursList[$array_key]['free_day'] !== $array_key) && !$holidays->is_holiday($this->year, $this->month, $i) && is_null($from) && ($week_day !== "Sat" && $week_day !== "Sun")) {
                echo "<td class='time_cell'>?</td>";
            }
            if (!is_null($from) && strtotime($array_key) <= strtotime($to)) {
                echo "<td class='time_cell vacation'>-</td>";
            }
            if (strtotime($array_key) === strtotime($to)) {
                $from = $to = null;
            }
            if (strtotime($array_key) === strtotime($to)) {
                $from = $to = null;
            }

//            if (array_key_exists($array_key, $freehours) && ($week_day !== "Sat" && $week_day !== "Sun")) {
//                echo "<td class='time_cell'>{$freehours[$array_key][$option]}</td>";
//            }
        }
    }

    /**
     *
     * @param int $time
     * @return date
     */
    public function get_time_from_int($time) {
        $hours = floor($time / 3600);
        $hour = $hours < 10 ? "0" . $hours : $hours;
        $mins = floor($time / 60 % 60);
        $min = $mins < 10 ? "0" . $mins : $mins;
//        $secs = floor($time % 60);
//        $sec = $secs < 10 ? "0" . $secs : $secs;
        return "{$hour}:{$min}";
    }

    public function count_work_days($workHoursList) {
        $counter = 0;
        foreach ($workHoursList as $key => $value) {
            $day = date("D", strtotime($key));
            if ($day !== "Sat" && $day !== "Sun") {
                $counter++;
            }
        }
        return $counter;
    }

//        public function count_free_hours_time($freehours) {
//        $time_int = 0;
//        foreach ($freehours as $key => $value) {
//            $day = date("D", strtotime($key));
//            if ($day !== "Sat" || $day !== "Sun") {
//                $time_int += strtotime("1970-01-01 {$value['work_hours']}:00 UTC");
//            }
//        }
//        return $time_int;
//    }

    public function count_work_hours_time($workHoursList) {
        $time_int = 0;
        foreach ($workHoursList as $key => $value) {
            $day = date("D", strtotime($key));
            if ($day !== "Sat" || $day !== "Sun") {
                $time_int += strtotime("1970-01-01 {$value['work_hours']}:00 UTC");
            }
        }
        return $time_int;
    }

    public function count_average_work_time($days, $time) {
        $average_time = $time / $days;
        return $this->get_time_from_int($average_time);
    }

    /**
     * Checks if violation type is 3
     * If violation type is 3 and time of day is less than the time work day end violation type is changed to null
     * because violation type 3 is only showed when the work day has ended
     *
     * @param int $violation
     * @param date $date
     */
    public function check_violation(&$violation, $date) {
        if ((int) $violation === 3 && $this->day_passed_time < $this->work_day_end_time && $date === date("Y-m-d")) {
            $violation = null;
        }
    }

    private function set_times() {
        $options = $this->get_option_value('work_hours');
        if (!is_null($options)) {
            $this->work_day_end_time = 3600 * (int) $options['work_end'];
        } else {
            $this->work_day_end_time = 3600 * 17;
        }
        $this->day_passed_time = strtotime(date("H:i:s")) - strtotime(date("Y-m-d"));
    }

    public function print_content_labels($startRow) {
        global $worksheet, $workbook, $contentLabels;
        foreach ($contentLabels as $array) {
            $labelColor = & $workbook->addFormat();
            $labelColor->setFgColor($array['color']);
            $worksheet->writeBlank($startRow + 1, 2, $labelColor);
            $worksheet->write($startRow + 1, 3, $array['name']);
            $startRow++;
        }
    }

}