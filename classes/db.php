<?php
require_once 'config.php';

class db extends Config{
    public function __construct(){
        parent::__construct();
    }
    
    public function query($query) {
        $return = array();

        if(!$result = $this->mysqli->query($query))
        {
            $return['success'] = false;
            $return['error'] = $this->mysqli->error;

            return $return;
        }

        $return['success'] = true;
        $return['affected_rows'] = $this->mysqli->affected_rows;
        $return['insert_id'] = $this->mysqli->insert_id;

        if(0 == $this->mysqli->insert_id)
        {
            $return['count'] = $result->num_rows;
            $return['rows'] = array();
            /* fetch associative array */
            while ($row = $result->fetch_assoc()) {
                $return['rows'][] = $row;
            }

            /* free result set */
            $result->close();
        }

        return $return;
    }
    
    public function __destruct() {
        $this->mysqli->close()
            OR die("There was a problem disconnecting from the database.");
    }
}
