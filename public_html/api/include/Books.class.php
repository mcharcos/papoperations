<?php

require_once 'DB.class.php';
class BOOKS
{
    public function __construct() {
		
	}
		
	// This function returns the details of given book
	public function get_details($book_id){
				
        // Open db instance
        $db = new DB();
		
		$conn = $db->connect_db();
        $result = pg_query($conn, "select * from book where book_id=".$book_id);
		return pg_fetch_all($result);
	}
}
?>
    