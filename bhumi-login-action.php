<?php
include "config/autoload.php";
$otp = $_POST['otp'];
$id = $_SESSION['verification_id'];
$us = $db->select('bhumi_locker', ['id' => $id], 1)->row();
    if ($us->otp_mobile == $otp) {
        $sb = [];
        $sb['password'] = $_POST['password'];
        $sb['mobile_verified'] = 1;
        $sb['status'] = 1;
        $db->update('bhumi_locker', $sb, ['id' => $id]);
        $_SESSION['user'] = $us;
        echo json_encode(array("success" => true, "message" => "Your account has been verified successfully"));
	    exit;
    }
    else{
    	echo json_encode(array("success" => false, "message" => "Incorrect OTP"));
    	exit;
    }
    
