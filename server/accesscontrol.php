<?php
    
    if (!session_id()) {
       @session_start();
    }
    
    if (isset($_POST['uid']))
    {
        $uid =  strtolower($_POST['uid']);
    }
    else
    {
        if (isset($_SESSION['uid']))
        {
            $uid =  $_SESSION['uid'];
        } 
    }
   
    if (isset($_POST['pwd']))
    {
        $pwd =  sha1($_POST['pwd']);
        $_POST['pwd'] = $pwd;
    }
    else
    {
        if (isset($_SESSION['pwd']))
        {
            $pwd =  $_SESSION['pwd'];
        } 
    }
    
    # If the user id was not in a specific session, then show login page
    if(!isset($uid) || empty($uid) || !isset($pwd) || empty($pwd))
    {
        # If accesscontrol from index we exit if no logged in
        if (!isset($_POST['nologingrequired']))
        {
            $_POST['sessionrestart'] = 1;
            echo '<script type="text/javascript">window.location.hash = "";</script>';
            header("Location: /index.php");
            exit;
        }
        return;
    }
    
    # Define a session with the input username and password
    $_SESSION['uid'] = strtolower($uid);
    $_SESSION['pwd'] = $pwd;
     $response = array();
    # Connect to database
    include_once($_SERVER["DOCUMENT_ROOT"]."/../server/db_utils.php");
    $link = connect_db();

    $sql = "SELECT * FROM user WHERE username = '$uid' AND password_hash = '$pwd'";
    $result = do_query($sql, $link);

    if (!$result)
    {
        unset($_SESSION['uid']);
        unset($_SESSION['pwd']);
        echo "A database error occurred while checking your login details.";
        exit;
    }

    if (do_mysql_num_rows($result) == 0) {
        unset($_SESSION['uid']);
        unset($_SESSION['pwd']);
        $response['msg'] = 'Invalid Username or Password';
        $res = 0;
    }else{
         $response['msg'] = 'success';
         $res = 1;
    }
    
    # Select and return the first row of the search 
    $firstrow = do_mysql_fetch_assoc($result);

    # if user is not confirmed we can't log in
    if ($firstrow['confirmation'] != "") {
        unset($_SESSION['uid']);
        unset($_SESSION['pwd']);
//        exit;
        
         $response['msg'] = "You haven't confirm you email. Please confirm your Email.";
         $res = 2;
    }
    $_SESSION['role'] = $firstrow['role'];
    $username = $uid; 
    
    # Close DB connection
    do_mysql_close($link);
    $response['res'] = $res;
?>