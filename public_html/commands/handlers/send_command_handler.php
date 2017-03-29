<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/../server/accesscontrol.php';
    include_once($_SERVER['DOCUMENT_ROOT']. "/../server/email_actions.php");
    
    // Create command from $_POST
    $command  = "#CMD," . $_REQUEST['command'] . ";";

    $success = email_command($command,$_REQUEST['iridium_unit'],$_REQUEST['description'],$_REQUEST['operation_type']);
    
    if ($success['status'] != 'success') {
        error_log("Error sending command at send_command_handler.php");
        echo json_encode($success);
        return;
    }
    echo json_encode($success);
?>