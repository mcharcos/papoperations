<?php
    include $_SERVER['DOCUMENT_ROOT'] . '/auth/check_admin.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/../server/email_actions.php';
    
    if (!isset($_REQUEST['command']) || empty($_REQUEST['command'])){
        echo "Command was not input";
        exit;
    }
    
    echo json_encode(email_command($_REQUEST['command'], "1111111111"));
?>
