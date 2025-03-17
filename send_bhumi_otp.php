<?php
include "config/autoload.php";

    if(isset($_POST['mobile']) && !empty($_POST['mobile'])){
        $mobile = $_POST['mobile'];

        $us = $db->select('bhumi_locker', ['mobile_number' => $mobile], 1)->row();
        if (is_object($us)) {
            echo json_encode(array("success" => false, "message" => "Mobile number already registered."));
            exit();
        }
        $otp = rand(1111, 9999);
        $created = date('Y-m-d H:i:s');
        sendSMS("Guest", $otp, $mobile, 'bhumi-locker');
        $user = [
            'id' => time(),
            'created' => $created,
            'status' => 0,
            'otp_mobile' => $otp,
            'mobile_verified' => 0,
            'mobile_number' => $mobile
        ];

    if ($db->insert('bhumi_locker', $user)) {
        $id = $db->id();
        echo json_encode(array("success" => true, "message" => "Your OTP sent on your mobile number."));
        $_SESSION['verification_id'] = $id;
    }


    }