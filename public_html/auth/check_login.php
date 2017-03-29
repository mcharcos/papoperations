<?php
        error_log('here, ');
        
        $username = $_POST['uid'];
        $pass = $_POST['pwd'];
        error_log($username.' ' . $pass);
        include $_SERVER['DOCUMENT_ROOT'] . '/../server/accesscontrol.php';
        error_log($response);
        echo json_encode($response);
        exit;
?>