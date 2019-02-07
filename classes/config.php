<?php
class Config {	
	private $_host = 'localhost';
	private $_username = 'root';
	private $_password = '';
	private $_database = 'php_bot';
	
	protected $connection;
	
	public function __construct(){
		$this->mysqli = new mysqli($this->_host, $this->_username, $this->_password)
                OR die("There was a problem connecting to the database.");

                
            if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
            }

            $this->mysqli->select_db($this->_database);

            if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
            }

            return true;
	}
}
?>