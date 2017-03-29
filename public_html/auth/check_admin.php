<?php
    include $_SERVER['DOCUMENT_ROOT'] . '/../server/accesscontrol.php';
    include_once($_SERVER['DOCUMENT_ROOT'] . '/../server/user_actions.php');
    $userinfo = get_user_info();
    
    if ($userinfo['role'] != 'admin') {
        echo "No admin user";
        exit;
    }
    
    echo '';
?>