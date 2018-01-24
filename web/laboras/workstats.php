
//
//<?php
//
/*
 * This class is for gathering data about employer from database.
 * Data fields available: work_hours, free_hours, free_days, violations, vacation, ill.
 * To use this class firstly you need to call it with values:
 * id - employer id in database
 * date - write date of stats (year - month and if need day)
 * fields - write array fields. Choose it from availablefields array. (example: ["free_hours]
 * !at the last field you must write dependencie for this class - Database $db
 *
 * To use your called class write function get_data['employee_stat'];
 */

class workStats
{

    private $id = null;
    private $date = null;
    private $fields = array();
    protected $db = null;
    protected $data = array();
    private $availableFields = [
        "work_hours",
        "free_hours",
        "free_days",
        "violations",
        "vacation",
        "ill"
    ];
    private $work_hours = [
        "day_start",
        "fixed_day_start",
        "day_end",
        "fixed_day_end",
        "work_hours",
        "last_job_time"
    ];
    private $free_hours = ["free_hours", "free_minutes"];
    private $free_days = ["free_day"];
    private $violations = ["violation"];
    private $vacation = ["vac_start", "vac_end"];
    private $ill = ["ill_start", "ill_end"];

    public function __construct($id, $date, array $fields, Database $db)
    {
        $this->id = $id;
        $this->date = $date;
        $this->fields = $fields;
        $this->db = $db;
        $this->init();
    }

    /*
     * function that joining constructor $fields params to query
     * @param string $fields
     * @param string $glue
     */
    private function join_fields($glue)
    {
        $array = [];
        foreach ($this->fields as $field) {
            foreach ($this->{$field} as $value) {
                $array[] = $value;
            }
        }
        return join($glue, $array);
    }

    /*
     * Selecting from database $fields by date and id
     * @param string $date
     * @param int $id
     *
     */

    private function get_query()
    {
        $query = "SELECT  date, ";
        //Joining $fields values
        $query .= $this->join_fields(", ");
        $query .= " FROM `employee_work_stats` WHERE `user` = '{$this->id}'"
            . " AND `date` LIKE '%{$this->date}%' ORDER BY `date` ASC";
        return $query;
    }

    /*
     * cheching if array has useless values.
     * @param string $value
     */

    private function var_empty($value)
    {
        if ($value === "" || $value === "a:0:{}" || is_null($value) || empty($value)) {
            return true;
        }
        return false;
    }

    /*
     * unserializing and returning array $value
     * function unserialize()
     * return string $unserializedVar
     *
     */

    private function unserialize_var($value)
    {
        $unserializedVar = @unserialize($value);
        if (!$unserializedVar) {
            return $value;
        }
        return $unserializedVar;
    }

    /*
     * checking and regrouping array values. setting date to be a key of array.
     * @param array $result
     * return array with $fields data.
     */
    private function set_data($result)
    {
        $arrayToReturn = array();
        foreach ($result as $array) {
            foreach ($array as $key => $value) {
                // checking if array dont have date value
                //  and other conditions from function var_empty()
                if ($key !== "date" && !$this->var_empty($value)) {
                    $arrayToReturn[$array['date']][$key] = $this->unserialize_var($value);
                }
            }
        }
        return $arrayToReturn;
    }

    /*
     * checking if container $fields values are available
     */
    private function check_fields()
    {
        foreach ($this->fields as $key => $field) {
            if (!in_array($field, $this->availableFields)) {
                unset($this->fields[$key]);
            }
        }
    }

    /*
     * quering and selecting from database than putting it in $data array.
     *
     */

    private function init()
    {
        $this->check_fields();
        $query = $this->get_query();
        $result = $this->db->select($query);
        // setting  $result values to $data through function set_data()
        $this->data = $this->set_data($result);
    }

    /*
     * checking if containers are the same.
     * returning only the same containers $colums.
     */

    public function check_container($domain)
    {
        $colums = array();
        foreach ($domain as $field) {
            // cheking if $domain values have $fields values
            if (!in_array($field, $this->fields)) {
                continue;
            }
            foreach ($this->$field as $column) {
                $colums[] = $column;
            }
        }
        return $colums;
    }

    /*
     * executing data from container
     * returning array of container values
     *
     */

    public function get_data(array $domain)
    {
        $array = array();
        // executing function filter_data()
        $colums = $this->check_container($domain);
        if (!empty($colums)) {
            foreach ($this->data as $date => $data) {
                foreach ($colums as $column) {
                    // checking if array values are not null.
                    if (isset($data[$column]) == null) {
                        continue;
                    }
                    $array[$date][$column] = $data[$column];
                }
            }
        }
        return $array;
    }


    public function user_vacation_date($date, $vacationLists)
    {
        global $conn;
        global $work;
        $userVacationList = Array();
//        $query = "SELECT v.date_from, v.date_to FROM `vacation` v LEFT JOIN `requests` r ON r.id = v.request_id "
//                . "WHERE v.user = {$user_id} AND (v.date_from LIKE '%{$this->year}-{$this->month}%' OR v.date_to LIKE '%{$this->year}-{$this->month}%') AND r.confirmed = 1";
//        $result = $conn->query($query);
//        var_dump($result);
        if ($vacationLists == !null) {
            while ($vacationLists) {
                $vacationLists[$date] = array('from' => ['vac_start'], 'to' => ['vac_end']);
            }
        }
        foreach ($userVacationList as $key => $array) {
            if (explode("-", $date)[1] !== explode("-", $array['from'])[1]) {
                $userVacationList[$date]['from'] = $date;
                $userVacationList[$date]['to'] = $userVacationList[$key]['to'];
                unset($userVacationList[$key]);
            }
        }
        return $userVacationList;
    }

}



require '../../web/laboras/database.php';
$work = new workStats(28, "2017-02", ["free_hours", "free_days"], new Database());

$data = $work->get_data(["free_hours"]);
foreach ($data as $key => $value) {
//    echo "$key => ";
//    print_r($value);
//    echo "<br>";
}
//
function calculate_data($data)
{
    foreach ($data as $key => $dataItem) {
        echo "<br>";
        var_dump($key);
        echo "<br>";
        var_dump($data[$key]['free_hours']);
        var_dump($dataItem);
        $data[$key]['free_hours'] = array_sum($dataItem['free_hours']);
        $data[$key]['free_minutes'] = array_sum($dataItem['free_minutes']);

    }

    return $data;
}
//
$gud = calculate_data($data);
print_r($gud);
?>