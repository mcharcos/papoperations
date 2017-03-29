<?php

# Verify if the username already exists in the database for existing users
# We assume that the funciton will be called with $link = null if
# the database was not previously open. If not, the link should be input
# the link to the database will be closed if not specified in the inputs and was open locally

function check_username($username, $link) {
    $close_link = false;

    # Open the connection to the database if it was not input
    # We assume that the funciton will be called with $link = null if
    # the database was not previously open. If not, the link should be input
    if (!$link) {
        $link = connect_db();
        $close_link = true;
    }

    # Check for existing user in database with the input user name
    $query = "SELECT COUNT(*) FROM user WHERE username = '$username'";
    $result = do_query($query, $link);

    if (!$result) {
        echo 'A database error occurred in processing your submission.';
        return -1;
    }
    if (do_mysql_result($result, 0, 0) > 0) {
        # Close DB connection if the link was open inside the function
        if ($close_link)
            do_mysql_close($link);
        $msg = 'A user already exists with your chosen userid.<br>Please try another.';
        return 1;
    }

    # Close DB connection if the link was open inside the function
    if ($close_link)
        do_mysql_close($link);

    return 0;
}

# retrieve user id in the table of the current user

function find_session_user_id($link, $username = "") {
    include_once("db_utils.php");

    # Find username and password of current session
    if (!strcmp($username, "")) {
        if (isset($_SESSION['uid'])) {
            $uid = $_SESSION['uid'];
        }
        if (isset($_SESSION['pwd'])) {
            $pwd = $_SESSION['pwd'];
        }
    } else {
        # Not sure how to handle this case to well manage the permissions of the user
        return null;
    }

    # Return null if the user id was requested when no current session
    if (!isset($uid) || !isset($pwd)) {
        error_log("No valid user session");
        return null;
    }


    # Open the connection to the database if it was not input
    # We assume that the funciton will be called with $link = null if
    # the database was not previously open. If not, the link should be input
    $close_link = false;
    if (!$link) {
        $link = connect_db();
        $close_link = true;
    }
    # search row for current username
    $query = "SELECT id, username, email, role FROM user WHERE username = '$uid' AND password_hash = '$pwd'";

    # Send SQL query
    $result = do_query($query, $link);
    if (!$result) {
        echo "A database error occurred while checking your login details.";
        exit;
    }

    # select the first row of the search result if the result is not empty in which
    # case we exit and show a retrieval error message
    if (do_mysql_num_rows($result) == 0) {
        error_log('Could not find user id for ' . $uid . ' and ' . $pwd . ' in find_session_user_id');
        return null;
    }
    # Select and return the first row of the search to find the id of the username in the table
    $firstrow = do_mysql_fetch_assoc($result);

    # Close DB connection if the link was open inside the function
    if ($close_link)
        do_mysql_close($link);

    return $firstrow;
}

# Add a new user to the user database

function add_user($post) {
    include_once("db_utils.php");
    include_once("email_actions.php");
    include_once("error_messages.php");

    # Open the connection to the database
    $link = connect_db();

    # default profile picture
    if (!isset($GLOBALS['default_user_pic_file']) || empty($GLOBALS['default_user_pic_file'])) {
        error_log("Initialization problem for global variable default_user_pic_file");
        return 3;
    }
    $default_user_pic_file = $GLOBALS['default_user_pic_file'];

    /*
      if (!isset($GLOBALS['db_profile_dir']) || empty($GLOBALS['db_profile_dir'])) {
      error_log("Initialization problem for global variable db_profile_dir");
      return 3;
      }
      $db_profile_dir = $GLOBALS['db_profile_dir'];
     */

    if (!isset($GLOBALS['profile_dir']) || empty($GLOBALS['profile_dir'])) {
        error_log("Initialization problem for global variable profile_dir");
        return 3;
    }
    $profile_dir = $GLOBALS['profile_dir'];

    if (!file_exists($profile_dir)) { // This should never happen because hopefully we setup our tree correctly with the profile main directory
        mkdir($profile_dir); //, 0755);
    }
    $default_profile_file = $profile_dir . '/' . 'default_user_picture.png'; //$_SESSION['DIRECTORY_ROOT'].'/profile/default_user_picture.png';
    # Define input parameter expected in the post
    $required_inputs = array(
        0 => array('name' => 'username', 'type' => 'string'),
        1 => array('name' => 'first_name', 'type' => 'string'),
        2 => array('name' => 'last_name', 'type' => 'string'),
        3 => array('name' => 'email', 'type' => 'string'),
        4 => array('name' => 'address', 'type' => 'string'),
        5 => array('name' => 'password_hash', 'type' => 'string')
    );

    # Check if any of the expected inputs is missed or empty
    foreach ($required_inputs as $element) {
        if (!isset($post[$element['name']]) || empty($post[$element['name']])) {
            error_log($element['name'] . ' is missed.');
            return 1;
        }
    }

    # change username to lowcases
    $post['username'] = strtolower($post['username']);


    #check if the user already exist in the database
    if (check_user_username($post['username'], $link) == 1) {
        return 2;
    }

    # Create picture profile directory and add default icon
//	$default_user_dir_name = $profile_dir.$post['username'].'/';
    $default_user_dir_name = $profile_dir . '/' . $post['username'] . '/';
    if (file_exists($default_user_dir_name)) {  // This hopefully never happens because it means that the user was previously created
        die("Fatal error when creating directory for user " . $post['username'] . ". Please contact the system administrator at support@fittycat.com");
    }
    mkdir($default_user_dir_name);
    copy($default_profile_file, $default_user_dir_name . $default_user_pic_file);
    array_push($required_inputs, array('name' => 'picurl', 'type' => 'string'));
    $post = array_merge($post, array('picurl' => $post['username'] . $default_user_pic_file));

    # Create a confirmation number
    $confirmation = $post['username'] . substr(md5(time()), 0, 20);
    array_push($required_inputs, array('name' => 'confirmation', 'type' => 'string'));
    $post = array_merge($post, array('confirmation' => $confirmation));

    # Define query to insert values in post
    $query = query_insert_post_str("user", $required_inputs, $post);

    # Send SQL query
    do_query($query, $link);

    # Close DB connection
    do_mysql_close($link);

    # Send verification email and show registration success message
    $didemail = email_new_user($post['email'], $confirmation, $post['first_name'] . " " . $post['last_name']);

    return 0;
}

# Confirm a user based on the confirmation number
# This will empty the confirmation column for that user

function confirm_user($confirmation) {

    include_once("db_utils.php");
    include_once("email_actions.php");

    # Open the connection to the database
    $link = connect_db();

    # Look for user having this confirmation
    $query = "SELECT * FROM `user` WHERE confirmation='" . $confirmation . "'";
    $result = do_query($query, $link);
    if (!$result) {
        error_log("A database error occurred while checking your login details.");
        exit;
    }

    # select the first row of the search result if the result is not empty in which
    # case we exit and show a retrieval error message
    if (do_mysql_num_rows($result) == 0) {
        error_log('Could not find user info for ' . $confirmation . ' in confirm_user');
        return "";
    }

    # Select and return the first row of the search 
    $firstrow = do_mysql_fetch_assoc($result);

    // Remove confirmation value
    $query = "UPDATE `user` SET confirmation='' WHERE id=" . $firstrow['id'];
    do_query($query, $link);

    # check address
    $did_address = 0;

    # add profile summary row
    $query = "INSERT INTO `user_profile_status` (user_id, profile_pic_done, bio_done, address_done) VALUES (" . $firstrow['id'] . ",0,0," . $did_address . ")";
    do_query($query, $link);

    # Close DB connection
    do_mysql_close($link);

    $didemail = email_confirmed_user($firstrow['email'], $firstrow['first_name'] . " " . $firstrow['last_name']);

    return $firstrow['username'];
}

# Update the basic info of the badge of the user: first/last names, email and address
# Password and username should be handled by another function

function update_user($post) {
    include_once("db_utils.php");
    include_once("email_actions.php");

    # Find username of current session
    $uid = isset($_POST['uid']) ? strtolower($_POST['uid']) : $_SESSION['uid'];
    $pwd = isset($_POST['pwd']) ? $_POST['pwd'] : $_SESSION['pwd'];

    # Define input parameter expected in the post
    $required_inputs = array(
        0 => array('name' => 'first_name', 'type' => 'string'),
        1 => array('name' => 'last_name', 'type' => 'string'),
        2 => array('name' => 'email', 'type' => 'string'),
        3 => array('name' => 'address', 'type' => 'string')
    );

    # Check if any of the expected inputs is missed or empty
    foreach ($required_inputs as $element) {
        if (!isset($post[$element['name']]) || empty($post[$element['name']])) {
            unset($required_inputs[$element['name']]);
        }
    }

    $query_change = "";

    # Open the connection to the database
    $link = connect_db();

    # search current user id in user table
    $foundid = find_session_user_id($link);
    $id = $foundid['id'];

    # Check if there is a password update
    if (isset($post['password_hash']) && !empty($post['password_hash']) && isset($post['old_password_hash']) && !empty($post['old_password_hash'])) {
        // check old password
        $query = "SELECT id FROM user WHERE id = '$id' AND password_hash = '" . $post['old_password_hash'] . "'";

        # Send SQL query
        $result = do_query($query, $link);
        if (!$result) {
            echo "A database error occurred while checking your login details";
            exit;
        }

        # select the first row of the search result if the result is not empty in which
        # case we exit and show a retrieval error message
        if (do_mysql_num_rows($result) == 0) {
            error_log("Old password does not match for user " . $foundid['uniqueId']);
            return "";
        }

        $query_change = " password_hash='" . $post['password_hash'] . "'";
    }

    if (isset($post['first_name']) && !empty($post['first_name'])) {
        if ($query_change != "") {
            $query_change .= ",";
        }

        $query_change .= " first_name='" . $post['first_name'] . "'";
    }

    if (isset($post['last_name']) && !empty($post['last_name'])) {
        if ($query_change != "") {
            $query_change .= ",";
        }

        $query_change .= " last_name='" . $post['last_name'] . "'";
    }

    if (isset($post['address']) && !empty($post['address'])) {
        if ($query_change != "") {
            $query_change .= ",";
        }

        $query_change .= " address='" . $post['address'] . "'";
    }

    $query = "UPDATE `user` SET " . $query_change . " WHERE id=" . $id;

    # Send SQL query to update the information of the user
    do_query($query, $link);

    # Update user status if address was changed and is valid
    $query = "UPDATE `user_profile_status` SET address_done=1 WHERE user_id=".$foundid['id'];
    
    do_query($query, $link);
    # Now if email change was requested we add something in the user_email_change table and send an email
    # to the new address for confirmation before pursuing
    if (isset($post['address']) && !empty($post['address']) && $post['email'] != $foundid['email']) {
        #check if there is one previous request.
        $query = "SELECT * FROM `user_email_change` WHERE user_id=$id";
        $result = do_query($query, $link);

        if (!$result) {
            echo "database error. ";
            exit;
        }

        # Create uniqueId
        $uniqueId = $foundid['username'] . "_" . preg_replace("/[^a-zA-Z]+/", "", $post['email']) . "_" . substr(md5(time()), 0, 6);
        if (do_mysql_num_rows($result) <= 0) {
            $query = "INSERT INTO `user_email_change` (`user_id`, `uniqueId`,`new_email`) VALUES (" . $id . ", '" . $uniqueId . "', '" . $post['email'] . "')";
        } else {
            $row = do_mysql_fetch_assoc($result);
            $query = "UPDATE `user_email_change` SET new_email='" . $post['email'] . "',uniqueId='" . $uniqueId . "' WHERE user_id=" . $id;
        }
        do_query($query, $link);

        # Send verification email and show registration success message
        $didemail = email_user_change_email($post['email'], $uniqueId, $post['first_name'] . " " . $post['last_name']);
        email_info_user_emailchange($foundid['email'], $post['email'], $post['first_name'] . " " . $post['last_name']);
    }

    # Close DB connection
    do_mysql_close($link);

    return $foundid['username'];
}

function confirm_user_emailchange($uniqueId) {

    include_once("db_utils.php");
    include_once("email_actions.php");

    # Open the connection to the database
    $link = connect_db();

    # Look for user having this confirmation
    $query = "SELECT * FROM `user_email_change` WHERE uniqueId='" . $uniqueId . "'";
    $result = do_query($query, $link);
    if (!$result) {
        error_log("A database error occurred while checking your email change details.");
        exit;
    }

    # select the first row of the search result if the result is not empty in which
    # case we exit and show a retrieval error message
    if (do_mysql_num_rows($result) == 0) {
        error_log('Could not find email change info for ' . $uniqueId . ' in confirm_user_emailchange');
        return "";
    }

    # Select and return the first row of the search 
    $firstrow = do_mysql_fetch_assoc($result);

    // Update email in the user table
    $query = "UPDATE `user` SET email='" . $firstrow['new_email'] . "' WHERE id=" . $firstrow['user_id'];
    do_query($query, $link);

    // Remove the request from the request table
    $query = "DELETE FROM `user_email_change` WHERE id=" . $firstrow['id'];
    $result = do_query($query, $link);

    // Get the info from the user to add it to the email
    $query = "SELECT * FROM `user` WHERE id=" . $firstrow['user_id'];
    $result = do_query($query, $link);
    if (!$result) {
        error_log("A database error occurred while checking your user details.");
        exit;
    }

    # select the first row of the search result if the result is not empty in which
    # case we exit and show a retrieval error message
    if (do_mysql_num_rows($result) == 0) {
        error_log('Could not find user info. contact your administrator');
        return "";
    }

    $row_user = do_mysql_fetch_assoc($result);

    # Close DB connection
    do_mysql_close($link);

    $didemail = email_confirmed_user_emailchange($row_user['email'], $row_user['first_name'] . " " . $row_user['last_name']);

    return $row_user['username'];
}

// Upload profile picture
function update_user_picture() {

    include_once("db_utils.php");

    # Check profile picture update
    if (!isset($_FILES["fileToUpload"]) || empty($_FILES["fileToUpload"])) {
        error_log("Request user_upload_picture does not contain files");
        return null;
    }

    # Open the connection to the database
    $link = connect_db();

    # search current user id in user table
    $foundid = find_session_user_id($link);

    $imageFileType = pathinfo(basename($_FILES["fileToUpload"]["name"]), PATHINFO_EXTENSION);
    $file_name = '/' . $foundid['username'] . '/profile_' . $foundid['username'] . '_' . substr(md5(time()), 0, 20) . '.' . $imageFileType;

    # Copy file to server
    move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $GLOBALS['profile_dir'] . $file_name);

    # update database
    $query = "UPDATE `user` SET picurl='" . $file_name . "' WHERE id=" . $foundid['id'];

    # Send SQL query to update the information of the user
    do_query($query, $link);

    # update database
    $query = "UPDATE `user_profile_status` SET profile_pic_done=1 WHERE user_id=" . $foundid['id'];

    # Send SQL query to update the information of the user
    do_query($query, $link);

    # Close DB connection
    do_mysql_close($link);


    return $file_name;
}

# Returns the information of the badge of the user, that is all the columns for current user of the seesion

function get_user_info() {
    include_once("db_utils.php");


    $uid = isset($_POST['uid']) ? strtolower($_POST['uid']) : $_SESSION['uid'];
    $pwd = isset($_POST['pwd']) ? $_POST['pwd'] : $_SESSION['pwd'];

    # Connect to database
    $link = connect_db();

    $sql = "SELECT * FROM user WHERE username = '$uid' AND password_hash = '$pwd'";
    $result = do_query($sql, $link);
    if (!$result) {
        echo "A database error occurred while checking your login details.";
        exit;
    }

    # select the first row of the search result if the result is not empty in which
    # case we exit and show a retrieval error message
    if (do_mysql_num_rows($result) == 0) {
        echo 'Could not find user info for ' . $uid . ' and ' . $pwd . ' in get_user_info';
        exit;
    }
    # Select and return the first row of the search 
    $firstrow = do_mysql_fetch_assoc($result);

    # Close DB connection
    do_mysql_close($link);

    $array_output['username'] = $firstrow['username'];
    $array_output['role'] = $firstrow['role'];
    $array_output['first_name'] = $firstrow['first_name'];
    $array_output['last_name'] = $firstrow['last_name'];
    $array_output['email'] = $firstrow['email'];
    $array_output['address'] = $firstrow['address'];
    $array_output['picurl'] = $GLOBALS['main_data_dir'] . $GLOBALS['db_profile_dir'] . '/' . $firstrow['picurl'];

    return $array_output;
}

function user_reset_password($username, $email) {

    include_once("db_utils.php");
    include_once("email_actions.php");

    # Connect to database
    $link = connect_db();

    $sql = "SELECT id, username FROM user WHERE username = '$username' AND email = '$email'";
    $result = do_query($sql, $link);
    if (!$result) {
        echo "A database error occurred while checking your login details.";
        exit;
    }

    # select the first row of the search result if the result is not empty in which
    # case we exit and show a retrieval error message
    if (do_mysql_num_rows($result) == 0) {
        error_log('Could not find user info for ' . $username . ' and ' . $email . ' in user_reset_password');
        $firstrow['msg'] = 'Could not find user info for ' . $username . ' and ' . $email . ' in database';
        $firstrow['rec'] = 1;
        return $firstrow;
    }
    # Select and return the first row of the search 
    $firstrow = do_mysql_fetch_assoc($result);

    # create a unique id based on user username
    $uniqueId = $firstrow['username'] . "_pwdreset_" . substr(md5(time()), 0, 6);

    # Create new password
    $pwd = substr(md5(time()), 0, 10);

    # add request to database
    $query = "INSERT INTO `pwd_reset` (`uniqueId`,`user_id`,`pwd`) VALUES ('" . $uniqueId . "', " . $firstrow['id'] . ", '" . $pwd . "')";
    do_query($query, $link);

    # Close DB connection
    do_mysql_close($link);

    # send email to username with new password
    $didemail = email_user_reset_password($email, $uniqueId);

    if (!$didemail) {
        error_log("Email was not sent for user mail " . $email . " and request " . $uniqueId);
        return null;
    }
    $firstrow['rec'] = 0;
    return $firstrow;
}

function user_reset_password_confirm($uniqueId) {
    include_once("db_utils.php");
    include_once("email_actions.php");
    # Connect to database
    $link = connect_cat_db();

    $sql = "SELECT * FROM pwd_reset WHERE uniqueId = '" . $uniqueId . "'";
    $result = do_query($sql, $link);
    if (!$result) {
        error_log("A database error occurred while checking your pwd reset details.");
        exit;
    }

    # select the first row of the search result if the result is not empty in which
    # case we exit and show a retrieval error message
    if (do_mysql_num_rows($result) == 0) {
        error_log('Could not find password reset info for ' . $uniqueId . ' in user_reset_password_confirm');
        return null;
    }
    # Select and return the first row of the search 
    $firstrow = do_mysql_fetch_assoc($result);

    # Create new password
    $pwd = substr(md5(time()), 0, 10);

    # add request to database
    $query = "UPDATE `user` SET `password_hash`='" . sha1($firstrow['pwd']) . "' WHERE id=" . $firstrow['user_id'];
    do_query($query, $link);


    $user_sql = "SELECT * FROM user WHERE id=" . $firstrow['user_id'];
    $result_user = do_query($user_sql, $link);
    $user = do_mysql_fetch_assoc($result_user);
    
    $didemail = email_reset_password_sendUser($user['email'], $firstrow['pwd']);
    # Close DB connection
    do_mysql_close($link);
    return $firstrow;
}


?>
