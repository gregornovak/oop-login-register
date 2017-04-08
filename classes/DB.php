<?php

class DB
{
    // static -> so it can be used anywhere, _ -> marks it as private
    private static $_instance = null;
    private $_pdo,
            $_query,
            $_error = false,
            $_results,
            $_count = 0;
    // INITIALIZE THE DATABASE CONNECTION, call it with getInstance static method
    private function __construct() {
        try {
            $this->_pdo = new PDO('mysql:host='.
                Config::get('mysql/host').
                ';dbname=' . Config::get('mysql/db_name'),
                Config::get('mysql/username'),
                Config::get('mysql/password')
            );
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }
    //initialize database connection - singleton
    public static function getInstance() {
        if(!isset(self::$_instance)) {
            // self je isto kot this za statične metode
            self::$_instance = new DB();
        }
        return self::$_instance;
    }
    // a query that executes and returns or inserts values from/to database
    // 1 -> whole query without values only ? signs (bind), 2 -> options / values that we would like to send
    public function query($sql, $params = []) {
    // set error to false, if we have more queries at the same time
    $this->_error = false;
    // if query is prepared
    if($this->_query = $this->_pdo->prepare($sql)) {
        // go through every parameter and bind ? to the parameter passed in
        if(count($params)) {
            $x = 1;
            foreach($params as $param) {
                $this->_query->bindValue($x, $param);
                $x++;
            }
        }
        // if query executed without errors, return fields in an object, and count num of returned values
        if($this->_query->execute()) {
            $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
            $this->_count = $this->_query->rowCount();
        } else {
            // if there has been an error set error parameter to true
            $this->_error = true;
        }
    }
    return $this;
    }
    //a query that has possibility to get records from db with where clause
    public function action($action, $table, $where = []) {
        if(count($where) === 3) {
            $operators  = ['=', '<', '>', '<=', '>='];
            $field      = $where[0];
            $operator   = $where[1];
            $value      = $where[2];

            if(in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if(!$this->query($sql, [$value])->error()) {
                    return $this;
                }
            }
        }
        return false;
    }
    // a simple query to get values using the action method
    public function get($table, $where = []) {
        return $this->action('SELECT *', $table, $where);
    }
    // query to insert records in db
    public function insert($table, $fields = []){
        $keys = array_keys($fields);
        $values = '';
        $x = 1;
        //for each field we write ? so we can later bind the values
        foreach($fields as $field) {
            $values .= "?";
            if($x < count($fields)) {
                $values .= ", ";
            }
            $x++;
        }
        // insert query that will be executed
        $sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";
        // if query executed without errors return true
        if(!$this->query($sql, $fields)->error()) {
            return true;
        }

        return false;
    }
    // a query that updates records in db, params: db table, id of column/user, optional where fields
    public function update($table, $id, $fields = []) {
        $set = '';
        $x = 1;
        // for each field write ? so values can be binded later
        foreach($fields as $name => $value) {
            $set .= "{$name} = ?";
            if($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }
        // update query
        $sql = "UPDATE `{$table}` SET `{$set}` WHERE id = `{$id}``";
        // if no error return true else false
        if(!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }
    // delete query
    public function delete($table, $where = []) {
        return $this->action('DELETE', $table, $where);
    }
    // get results from queries
    public function results() {
        return $this->_results;
    }
    // count the num of records returned from query
    public function count() {
        return $this->_count;
    }
    // get the first row from the query
    public function first() {
        return $this->results()[0];
    }
    // returns if there are errors
    public function error() {
        return $this->_error;
    }
}