<?php

function add_command($command, $fname, $iridium_unit, $description = "", $type = "") {
    
    include_once("db_utils.php");
    include_once("user_actions.php");
    
    # Open the connection to the database
    $link = connect_db();

    if (!isset($GLOBALS['db_command_dir']) || empty($GLOBALS['db_command_dir'])) {
        error_log("Initialization problem for global variable db_command_dir");
        return array('status' => 'error', 'msg' => "Initialization problem for global variable db_command_dir");
    }
    $db_command_dir = $GLOBALS['db_command_dir'];

    if (!isset($GLOBALS['command_dir']) || empty($GLOBALS['command_dir'])) {
        error_log("Initialization problem for global variable command_dir");
        return array('status' => 'error', 'msg' => "Initialization problem for global variable command_dir");
    }
    $command_dir = $GLOBALS['command_dir'];

    # search current user id in user table
    $userid = find_session_user_id($link);

    # create a unique id based on user username
    $uniqueid = $userid['username'] . "_sbd_" . substr(md5(time()), 0, 6);
    
    $query = "INSERT INTO `command_log` (`uniqueId`, `user_id`,`command`,`fname`,`iridium_unit`, `description`, `type`)  VALUES ('".$uniqueid."', ".$userid['id'].", '".$command."', '".$fname."', '".$iridium_unit."', '". $description."', '".$type."')";

    do_query($query, $link);

    # Close DB connection
    do_mysql_close($link);

    return array('status' => 'success', 'msg' => $query);
}

function create_command_file($command) {
		
    if (!isset($GLOBALS['command_dir']) || empty($GLOBALS['command_dir'])) {
        include_once("db_utils.php");
        get_config_globals(true);
    }
    $command_dir = $GLOBALS['command_dir'];
    
    $relpath = date("Y") . DIRECTORY_SEPARATOR . date("M");
    
    $dir = $command_dir . DIRECTORY_SEPARATOR . $relpath;
    
	if (!file_exists($dir)) {
		mkdir($dir);
	}
	
	$command_str = str_replace("#CMD,", "", $command);
	$command_str = str_replace(",", "-", $command_str);
	$command_str = str_replace("=", "", $command_str);
	$command_str = str_replace(";", "", $command_str);
	
	$fname = date("YmdHis") . "_" . $command_str . ".sbd";
    
	$myfile = fopen($dir . DIRECTORY_SEPARATOR . $fname, "w");
	fwrite($myfile, $command."\n");
	fclose($myfile);	
	
	return array('fname' => $fname, 'rootpath' => $command_dir, 'relpath' => $relpath);
}

function get_command_list ($iridium_unit, $command="", $type="", $datetime_min="", $datetime_max="", $description="", $username="") {
   	include_once("db_utils.php");	
    
    # Open the connection to the database
   	$link=connect_db();
	
    # search current user id in user table
    $query_username = "";
	if ($username != "") {
		$query = "SELECT id, username, email, role FROM user WHERE username LIKE '%".$username."%'";
		# Send SQL query
		$result = do_query($query, $link);
		if (!$result) {
			error_log("Username $username does not exists in our database.");
			return array('status' => 'notfound', 'msg' => 'Username '.$username .' does not exists in our database.');
		}
		$firstrow = do_mysql_fetch_assoc($result);
		$query_username = " AND user_id=".$firstrow['id'];
	}
	
	// Manage description entry
	$query_description="";
	if ($description != "") {
		$query_description = " AND description LIKE '%".$description."%'";
	}
	
	// Type of test
	$query_type = "";
	if ($type != "") {
		$query_type = " AND type='".$type."'";
	}
	
	// Manage command entry
	$query_command="";
	if ($command != "") {
		$query_command = " AND command LIKE '%".$command."%'";
	}
		
	// Date range
	$query_daterange = '';
	if ($datetime_min != "" && $datetime_max != ""){
	    $query_daterange = " AND date_sent BETWEEN '" . $datetime_min . "' AND '" . $datetime_max . "'";
	} else if ($datetime_min != "") {
	    $query_daterange = " AND date_sent >= '" . $datetime_min . "'";
	} else if ($datetime_max != "") {
	    $query_daterange = " AND date_sent <= '" . $datetime_max . "'";
	}
	
	// Combine all extra query parameters
	$query_extra = $query_daterange.$query_command.$query_type.$query_description.$query_username;
	
	# find activity for a specific cat id
	$query = "SELECT uniqueId, iridium_unit, date_sent, fname, command, description, type FROM `command_log` WHERE iridium_unit LIKE '%".$iridium_unit."%'". $query_extra;
	
   	$result = do_query($query,$link);
	if (!$result)
	{
	    error_log ("A database error occurred while checking your command log.");
		return array('status' => 'error', 'msg' => 'A database error occurred while checking your command log.');
	}
	
	# select the first row of the search result if the result is not empty in which
	# case we exit and show a retrieval error message
	if (do_mysql_num_rows($result) == 0)
	{
	    error_log ("Query at get_command_list is empty");
		return array('status' => 'notfound', 'msg' => $query); //'Query at get_command_list is empty.');
	}
	
	# create output array with cat list found for this user
	while($row = do_mysql_fetch_assoc($result))
	{
	    $array_output[] = $row;	    
	}
	
        # Close DB connection
	do_mysql_close($link);
	
	# return list page
	return array('status' => 'success', 'msg' => $array_output);
}

?>