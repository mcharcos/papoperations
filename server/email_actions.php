<?php

function get_url() {

    if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS'])) {
        $protocol = 'https://';
    } else {
        $protocol = 'http://';
    }

    $folder = $protocol . $_SERVER['HTTP_HOST'];

    return $folder;
}

function email_config() {

    require_once($_SERVER['DOCUMENT_ROOT'] . "/../PHPMailer_5.2.0/class.phpmailer.php");

    $mail = new PHPMailer();

    $mail->IsSMTP();                                      // set mailer to use SMTP
    $mail->SMTPDebug = 0; //0: none, 1 low level messages, 2 more details
    
	$mail->Host = "smtp.gmail.com";  // specify main and backup server
	$mail->SMTPSecure = "ssl";
	$mail->Port = 465;
	$mail->SMTPAuth = true;     // turn on SMTP authentication
	$mail->Username = "noc.iridiumsbd@gmail.com";  // SMTP username
	$mail->Password = "1r1d1umsbd"; // SMTP password
	
	$mail->From = "noc.iridiumsbd@gmail.com";
	$mail->FromName = "SBD commands";

    $mail->isHTML(true);
    return $mail;
}

function email_command($command, $buoy_unit_id, $description="", $type="debug") {
	
	include_once 'command_actions.php';
	
	// Create a file with the command to be sent
	$fname = create_command_file($command);
	
	if ($fname == null) {
		error_log("Problem when creating file for command ".$command);
		return array('status' => 'error', 'msg' => "Could not create command file");
	}
	
	$filename = $fname['rootpath'] . DIRECTORY_SEPARATOR . $fname['relpath'] . DIRECTORY_SEPARATOR . $fname['fname'];
	
	$email = "data@sbd.iridium.com";
	$name = "SBD Command";
    $folder = get_url();

    $mail = email_config();

    $mail->AddAddress($email, $name);
	$mail->addAttachment($filename);
	
    $mail->WordWrap = 50;                                 // set word wrap to 50 characters
    $mail->IsHTML(true);                                  // set email format to HTML

    $mail->Subject = $buoy_unit_id;
    $mail->Body = "Buoy command ";

    $mail->AltBody = "Buoy command ";


    if (!$mail->Send()) {
        error_log("Message could not be sent.");
        error_log("Mailer Error: " . $mail->ErrorInfo);
		
		// Add command error to database
		return array('status' => 'error', 'msg' => "Error sending email: ".$mail->ErrorInfo);
    }
	// On success, record command to database command logs
	$result = add_command($command, $fname['relpath'] . DIRECTORY_SEPARATOR . $fname['fname'], $buoy_unit_id, $description, $type);
	return $result;

	if ($result['status'] != 'success'){
        error_log("Error when adding command to database");
		return $result;
	}
	
	// Add command success to database
    return array('status' => 'success' , 'msg' => $filename);
}


?>