<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/../server/accesscontrol.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/../server/command_actions.php';
    
    $command = "";
    if (isset($_REQUEST['command']) && !empty($_REQUEST['command'])){
        $command = $_REQUEST['command'];
    }
    $operation_type = "";
    if (isset($_REQUEST['operation_type']) && !empty($_REQUEST['operation_type'])){
        $operation_type = $_REQUEST['operation_type'];
    }
    $mindate = "";
    if (isset($_REQUEST['mindate']) && !empty($_REQUEST['mindate'])){
        $mindate = $_REQUEST['mindate'];
    }
    $maxdate = "";
    if (isset($_REQUEST['maxdate']) && !empty($_REQUEST['maxdate'])){
        $maxdate = $_REQUEST['maxdate'];
    }
    $description = "";
    if (isset($_REQUEST['description']) && !empty($_REQUEST['description'])){
        $description = $_REQUEST['description'];
    }
    $username = "";
    if (isset($_REQUEST['username']) && !empty($_REQUEST['username'])){
        $username = $_REQUEST['username'];
    }
    $success = get_command_list($_REQUEST['unitid'], $command, $operation_type, $mindate, $maxdate, $description, $username);
    
    if ($success['status'] == 'error') {
        error_log("Error sending command at send_command_handler.php");
        echo json_encode($success);
        return;
    } else if ($success['status'] == 'notfound') {
        echo json_encode($success);
        return;
    }
    echo json_encode($success);
?>