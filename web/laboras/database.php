<?php
date_default_timezone_set('Europe/Vilnius');

class Database {

    protected $connection;
    protected $query_counter = 0;

    function __construct() {
        $this->connection = new mysqli('127.0.0.1', 'homestead', 'secret', 'symfony');
    }

    public function query($query) {
        $result = $this->connection->query($query);
        $this->query_counter++;
        return $result;
    }

    public function beginTransaction(){
        $this->connection->autocommit(false);
    }

    public function commit(){
        $this->connection->commit();
    }

    public function rollback(){
        $this->connection->rollback();
    }

    public function error() {
        return $this->connection->error;
    }

    public function escape($var) {
        return $this->connection->real_escape_string($var);
    }

    public function select($query) {
        $rows = array();
        $result = $this->query($query);
        if ($result === false) {
            return false;
        }
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function num_rows($query) {
        $result = $this->query($query);
        if ($result === false) {
            return false;
        }
        return $result->num_rows;
    }

    public function affected_rows() {
        return mysqli_affected_rows($this->connection);
    }

    public function get_query_counter() {
        return $this->query_counter;
    }

    public function insert_id() {
        return $this->connection->insert_id;
    }
}
