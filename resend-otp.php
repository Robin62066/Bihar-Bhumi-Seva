<?php
include "config/autoload.php";
if (isset($_SESSION['verification_id'])) {
    $id = $_SESSION['verification_id'];
    $us = $db->select('ai_users', ['id' => $id], 1)->row();
    sendSMS($us->first_name, $us->mobile_otp, $us->mobile_number, 'signup');
    redirect('account-verify.php', "OTP Resent on your mobile.", 'success');
}
redirect('index.php');
