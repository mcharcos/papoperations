<?php
	function get_config_globals($dofolders=false) {
		
		$ini_array = parse_ini_file($_SERVER["DOCUMENT_ROOT"]."/../init.conf");
		$server=$ini_array['url'];
		$user=$ini_array['user'];
		$pass=$ini_array['pass'];
		$db=$ini_array['db'];
		
	   	$GLOBALS['sqli_on']=$ini_array['sqli_on'];
		
		if ($dofolders || !isset($GLOBALS['main_data_dir'])) {
			// main data folder definitions
			$GLOBALS['main_data_dir']=$ini_array['main_data_dir'];
			
			// profile data definitions
			$GLOBALS['db_profile_dir']=$ini_array['db_profile_dir'];
			$GLOBALS['profile_dir'] = realpath(dirname(__FILE__) . '/../public_html'.$ini_array['main_data_dir']) . $GLOBALS['db_profile_dir'];
			
			// default picture names 
			$GLOBALS['default_user_pic_file'] = $ini_array['default_user_pic_file'];
			
			// Commands folder lives outside the public domain but main_data_dir is within to access the pics
			$GLOBALS['db_command_dir']=$ini_array['db_command_dir'];
			$GLOBALS['command_dir'] = realpath(dirname(__FILE__) . '/../' . $GLOBALS['db_command_dir']);
		}
		
		return array(	'server' => $server,
						'user' =>$user,
						'pass' => $pass,
						'db' => $db
					);
	}
	
	// This function connects to the data base pap_db
	function connect_db(){
		
		$credentials = get_config_globals(false);
		
		if (!$GLOBALS['sqli_on'])
		{
			$connection = mysql_connect($credentials['server'], $credentials['user'], $credentials['pass']);
		}
		else
		{
			$connection = mysqli_connect($credentials['server'], $credentials['user'], $credentials['pass'], $credentials['db']);
                        mysqli_set_charset($connection, "utf8");
		}
		
		if (!$connection)
		{
			die('MySQL ERROR: ' . mysql_error());
		}
		
		if (!$GLOBALS['sqli_on'])
		{
			mysql_select_db($credentials['db']) or die( 'MySQL ERROR: '. mysql_error() );
		}
		
		return $connection;
	}
	
	
	// Create a SQL request based on the elements of request_inputs and the values of these elements in post_arr
	// This function assumes that the post_arr has been checked and contains all the required values
	function query_insert_post_str($table, $requested_inputs, $post_arr, $type_inputs = null)
	{
		
		// Define in the query string the action and table
		$query = "INSERT INTO `" . $table . "`";
		
		// Create string elements and values to be inserted
		$query_params = " (";
		$query_values = " (";
		$separator = "";
		
		foreach ($requested_inputs as $element)
		{
			if (!strcmp($element['name'], "password"))
			{
				$query_params = $query_params . $separator . "`password_hash`";
				$query_values = $query_values . $separator . "'" . sha1($post_arr[$element['name']]) . "'";
			}
			else
			{
				if (!strcmp($element['type'], "string"))      // We add the quotes around the value
				{
					$query_params = $query_params . $separator . "`" . $element['name'] . "`";
					$query_values = $query_values . $separator . "'" . $post_arr[$element['name']] . "'";
				}
				else if (!strcmp($element['type'], "date"))      // We add the quotes around the value. It may be something different
				{
					$query_params = $query_params . $separator . "`" . $element['name'] . "`";
					$query_values = $query_values . $separator . "'" . $post_arr[$element['name']] . "'";
				}
				else if (!strcmp($element['type'], "datetime"))      // We add the quotes around the value. It may be something different
				{
					$query_params = $query_params . $separator . "`" . $element['name'] . "`";
					$query_values = $query_values . $separator . "'" . $post_arr[$element['name']] . "'";
				}
				else if (!strcmp($element['type'], "number"))  // We don't add the quotes around the value
				{
					$query_params = $query_params . $separator . "`" . $element['name'] . "`";
					$query_values = $query_values . $separator . $post_arr[$element['name']];
				}
				else // Treat it as a string
				{
					$query_params = $query_params . $separator . "`" . $element['name'] . "`";
					$query_values = $query_values . $separator . "'" . $post_arr[$element['name']] . "'";
				}
			}
			$separator = ", ";
		}
		$query_params = $query_params . ")";
		$query_values = $query_values . ")";
		
		// Put all the pieces together
		$query = $query . $query_params . " VALUES" . $query_values;
		
		return $query;
	}
		
	// finds the post for the specific request and update the values of the first found row
	// Must be used with caution in order to make sure the search is made on some unique column id
	function query_update_post_str($table, $requested_inputs, $post_arr, $idvalue, $uniqueId_set = false)
	{
		
		// Define in the query string the action and table
		$query = "UPDATE `" . $table . "` SET";
		
		// Create string elements and values to be inserted
		$query_params = " ";
		$separator = "";
		foreach ($requested_inputs as $element)
		{
			
			if (!strcmp($element['name'], "password"))
			{
				$query_params = $query_params . $separator . "`password_hash` = " . "'" . sha1($post_arr[$element['name']]) . "'";
			}
			else
			{
				if (!strcmp($element['type'], "string"))      // We add the quotes around the value
				{
					$query_params = $query_params . $separator . "`" . $element['name'] . "` = " . "'" . $post_arr[$element['name']] . "'";
				}
				else if (!strcmp($element['type'], "number"))  // We don't add the quotes around the value
				{
					$query_params = $query_params . $separator . "`" . $element['name'] . "` = " . $post_arr[$element['name']];
				}
				else // Treat it as a string
				{
					$query_params = $query_params . $separator . "`" . $element['name'] . "` = " . "'" . $post_arr[$element['name']] . "'";
				}
			}
			$separator = ", ";
		}
		if ($uniqueId_set)
			$query_params = $query_params . " WHERE `uniqueId` = '" . $idvalue . "'";		
		else
			$query_params = $query_params . " WHERE `id` = " . $idvalue;
		
		// Put all the pieces together
		$query = $query . $query_params;
		
		return $query;
	}
	
	// Allows to search using SQL or SQLI depending on what version of mysql we are using as defined by $GOBAL['sqli_on']
	function do_query($query, $connection=null)
	{
		if (!$GLOBALS['sqli_on'])
		{
			$result = mysql_query($query);
		}
		else
		{
			if ($connection == null)
			{
				die('MySQL ERROR: SQLi queries require connection link input');	
			}
			$result = mysqli_query($connection, $query);
		}
		
		return $result;
	}
	
	// Allows to close link using SQL or SQLI depending on what version of mysql we are using as defined by $GOBAL['sqli_on']
	function do_mysql_close($con)
	{
		if (!$GLOBALS['sqli_on'])
		{
			$result = mysql_close($con);
		}
		else
		{
			$result = mysqli_close($con);
		}
		
		return $result;
	}
	
	// Analog to mysql_result (https://mariolurig.com/coding/mysqli_result-function-to-match-mysql_result/)
	function do_mysql_result($res,$row=0,$col=0)
	{ 
		if (!$GLOBALS['sqli_on'])
		{
			$result = mysql_result($res,$row,$col);
		}
		else
		{
			$numrows = mysqli_num_rows($res); 
			if ($numrows && $row <= ($numrows-1) && $row >=0)
			{
			    mysqli_data_seek($res,$row);
			    $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
			    if (isset($resrow[$col]))
			    {
				return $resrow[$col];
			    }
			}
			return false;
		}
	}
	
	// Allows to fetch row using SQL or SQLI depending on what version of mysql we are using as defined by $GOBAL['sqli_on']
	function do_mysql_fetch_assoc($result)
	{
		if (!$GLOBALS['sqli_on'])
		{
			$row = mysql_fetch_assoc($result);
		}
		else
		{
			$row = mysqli_fetch_assoc($result);
		}
		
		return $row;
	}
	
	// Allows to get number of rows using SQL or SQLI depending on what version of mysql we are using as defined by $GOBAL['sqli_on']
	function do_mysql_num_rows($result)
	{
		if (!$GLOBALS['sqli_on'])
		{
			$num = mysql_num_rows($result);
		}
		else
		{
			$num = mysqli_num_rows($result);
		}
		
		return $num;
	}
	
	
	function pr($data, $status = 0) {
            echo "<pre>";
            print_r($data);
            echo "</pre>";
            if ($status == 1) {
                exit;
            }
        }
?>
