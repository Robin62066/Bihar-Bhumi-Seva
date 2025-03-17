<?php
include "config/autoload.php";
if (isset($_SESSION['verification_id'])) {
    if (isset($_POST['otp'])) {
        $otp  = $_POST['otp'];
        $id = $_SESSION['verification_id'];
        $us = $db->select('ai_users', ['id' => $id], 1)->row();
        if ($us->mobile_otp == $otp) {
            $sb = [];
            $sb['mobile_verified'] = 1;
            $sb['admin_verified'] = 0;
            $sb['signup_completed'] = 0;
            $db->update('ai_users', $sb, ['id' => $id]);
            $_SESSION['user'] = $us;
            redirect(site_url('dashboard/index.php'), "Your account has been verified succssfully", "success");
        } else {
            redirect(site_url('account-verify.php'), "OTP is invalid", "danger");
        }
    } else {
        redirect(site_url('account-verify.php'), "Something went wrong, try again", "danger");
    }
}
redirect('index.php');
