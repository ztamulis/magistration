<?php

class Domain {

    private $Navigation_info = array();
    private $Number_of_elements;

    public function __contruct() {
        $this->Number_of_elements = 0;
    }

    public function insertIntoArray(Navigacija $element) {
        $this->Navigation_info[] = $element;
        $this->Number_of_elements++;
    }

    public function get_count_elements() {
        return $this->Number_of_elements;
    }
    public function delete_from_array($index){
        unset($this->Number_of_elements[$index]);
    }
    public function get_element($index) {
        return $this->Navigation_info[$index];
    }

}