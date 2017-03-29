<?php

require_once 'DB.class.php';
class CATEGORY
{
    public function __construct() {
		
	}
	
	// This function gets the list of categories
	public function get_list(){
				
        // Open db instance
        $db = new DB();
		
		$conn = $db->connect_db();
        $result = pg_query($conn, "select * from category where 1"); // should return only a given number of parameters not all
		return pg_fetch_all($result);
	}
	
	// This function returns the details of given category
	public function get_details($category_id){
				
        // Open db instance
        $db = new DB();
		
		$conn = $db->connect_db();
        $result = pg_query($conn, "select * from category where category_id=".$category_id);
		return pg_fetch_all($result);
	}
	
	// This function returns the books of given category
	public function get_books($category_id){
				
        // Open db instance
        $db = new DB();
		
		$conn = $db->connect_db();
        $result = pg_query($conn, "select * from category_books where category_id=".$category_id);
		
		// For each row of the results, retrieve the book info
		while($row = do_mysql_fetch_assoc($result)){
			$result_book = pg_query($conn, "select * from book where category_id=".$row['id']);
			$firstrow = do_mysql_fetch_assoc($result_book);
			$array_output[] = $firstrow; //array('book' => $firstrow['name'];	    
		}
		return $array_output;
	}
}
?>