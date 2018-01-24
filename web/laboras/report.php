
<html>
<head>
    <script src="javascripts/jquery-1.12.0.min.js"></script>
    <style>
        #work_hours, #header-fixed {border-collapse: collapse;
            border: 1px solid grey;}
        #header-fixed {
            position: fixed;
            top: 0px; display:none;
            background-color:white;
        }
        #work_hours, #header-fixed th{font-size: 15px;}
        #work_hours td{font-size: 12px;}
        .days {width: 40px;}
        .tag {width: 9%;}
        .date_label {width: 12%;}
        .time_cell {text-align: center;}
        .row:nth-child(3n+4) td {border-bottom: 3px solid black;}
        .weekend {background-color: #999999;}
        .vacation {background-color: yellow;}
        .holiday {background-color: cyan;}
        .free-day {background-color: #9370DB;}
        .no_violation {background-color: #00cc00;}
        .violation {color: #ff3333;}
        .average {width: 8%;}
        .pointer {width: 4%;}
    </style>
</head>
<body>
<div>
    <div id="table-wrapper">
        <table border="1" id="header-fixed"></table>
        <table border="1" id="work_hours">
            <thead>
            <th class="tag">Darbuotojas</th>
            <th colspan="2" class='date_label'><?php echo $year . " m. " . strtoupper($month_labels[date('F', strtotime($year . "-" . $month))]); ?></th>
            <?php

            //                    foreach ($users_list as $user) {
            //                    $workHoursList = $employee_work_hours->work_hours_data($user['id'], $year, $month);
            //                    for ($i = 1; $i <= $monthDaysCount; $i++) {
            //                    $day = $i <= 9 ? "0" . $i : $i;
            //                    $array_key = $this->year . "-" . $this->month . "-" . $day;
            //                    if ($workHoursList[$array_key]['free_day'] == $array_key){
            //                        echo "<td class='time_cell free-day'>{$i}</td>";
            //                    }
            //                    }
            //                    }
            for ($i = 1; $i <= $month_days; $i++) {

                $day = date('D', strtotime(date($year . '-' . $month . '-' . $i)));
                if ($day === "Sat" || $day === "Sun") {
                    echo "<th class='days weekend'>{$i} d.</th>";
                } else {
                    //Vertikalus langeliu spalvos pakeitimas jie diena yra nedarbo diena
                    if ($holidays->is_holiday($year, $month, $i)) {
                        $holiday = true;
                    }
                    if (!$holiday) {
                        echo "<th class='days'>{$i} d.</th>";
                    } else {
                        echo "<th class='days holiday'>{$i} d.</th>";
                        $holiday = false;
                    }


                    //Vertikalus langeliu spalvos pakeitimas jie diena yra nedarbo diena
//                            if ($workHoursList['free_day'] === ($year . '-' . $month . '-' . $i) ) {
//                                echo "<th class='days free-day'>{$i} d.</th>";
//                            }
                }
            }

            ?>
            </thead>
            <tbody>
            <?php
            foreach ($users_list as $user) {
                $workStats = new workStats($user['id'], "{$year}-{$month}", ["work_hours", "vacation", "free_days", "free_hours", "violations"], $db);
                $workHoursList = $workStats->get_data(["work_hours", "violations", "free_hours", "free_days"]);
                $vacationLists = $workStats->get_data(["vacation"]);
//                            $workHoursList = $employee_work_hours->work_hours_data($user['id'], $year, $month);
//                            $freehours = $employee_work_hours->freehours($user_id);
//                            $freedays = $employee_work_hours->freedays($user_id, $date);
                $vacationList = $employee_work_hours->user_vacation_date("{$year}-{$month}-1", $vacationLists);

                $workDaysTotal = $employee_work_hours->count_work_days($workHoursList);
                $workHoursTotalInt = $employee_work_hours->count_work_hours_time($workHoursList);
                $workHoursTotal = $employee_work_hours->get_time_from_int($workHoursTotalInt);
                $white_spacesA = strlen($workDaysTotal) === 1 ? 8 : 6;
                $white_spacesB = strlen($workHoursTotal) <= 5 ? 4 : 3;
                echo "<tr class='row'>";
                echo "<td class='tag'>{$user['name']}</td>";
                echo "<td class='average'>" . "Dirbo dienų" . str_repeat("\x20", $white_spacesA) . $workDaysTotal . "</td>";
                echo "<td class='pointer'>Pradžia</td>";
                $employee_work_hours->set_month_days_hybrid_html($workHoursList, $month_days, "day_start", $holidaysList, $vacationList, $holidays);
                echo "</tr>";
                echo "<tr class='row'>";
                echo "<td class='tag'>{$user['surname']}</td>";
                echo "<td class='average'>" . "Dirbo val." . str_repeat("\x20", $white_spacesB) . $workHoursTotal . "</td>";
                echo "<td class='pointer'>Pabaiga</td>";
                $employee_work_hours->set_month_days_hybrid_html($workHoursList, $month_days, "day_end", $holidaysList, $vacationList, $holidays);
                echo "</tr>";
                echo "<tr class='row'>";
                echo "<td class='tag'>{$user['position']}</td>";
                echo "<td class='average'>" . "Vid./diena" . str_repeat("\x20", 4) . $employee_work_hours->count_average_work_time($workDaysTotal, $workHoursTotalInt) . "</td>";
                echo "<td class='pointer'>Dirbo</td>";
                $employee_work_hours->set_month_days_hybrid_html($workHoursList, $month_days, "work_hours", $holidaysList, $vacationList, $holidays);
                echo "</tr>";
                echo "<tr class='row'>";
                echo "<td class='tag'></td>";
                echo "<td class='average'>" . "" . "</td>";
                echo "<td class='pointer'>" . "Laisvų val." . "</td>";
                $employee_work_hours->set_month_days_hybrid_html($workHoursList, $month_days , 'free_hours' , $holidaysList, $vacationList, $holidays);
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <div id="label-wrapper" style="margin-top: 20px;">
        <table id="content">
            <tbody>
            <?php
            foreach ($contentLabels as $array) {
                echo "<tr>";
                echo "<td><div style='background-color: {$array['color']}; width: 20px; height: 25px;'></div></td><td>{$array['name']}</td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>
<script type="text/javascript">
    var tableOffset = $("#work_hours").offset().top;
    var $header = $("#work_hours > thead").clone();
    var $fixedHeader = $("#header-fixed").append($header);

    $(window).bind("scroll", function () {
        var offset = $(this).scrollTop();

        if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
            $fixedHeader.show();
        } else if (offset < tableOffset) {
            $fixedHeader.hide();
        }
    });
</script>
</html>