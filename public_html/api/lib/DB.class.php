<?php

class DB
{
    
    private $host;
    private $user;
    private $pass;
    private $db;
    
    // This extracts the db information from the configuration file
    // For more security, the conf file could be encrypted 
    public function __construct() {
		
		$ini_array = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/api/config/db.conf");
		$this->host=$ini_array['host'];
		$this->user=$ini_array['user'];
		$this->pass=$ini_array['pass'];
		$this->db=$ini_array['db'];
	}
	
	// This function connects to the data base
	public function connect_db(){
		
        $connStr = "host=".$this->host." user=".$this->user." password=".$this->pass." dbname=".$this->db;
		$connection = pg_connect($connStr);
        
		return $connection;
	}
}
?>