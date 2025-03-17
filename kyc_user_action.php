<?php
include "config/autoload.php";

if (isset($_POST["btn_submit"])) {

    $user = $_SESSION['user'];
    $user_id = $user->id;
    $aadhar_name = $_POST['aadhar_name'];
    $father_name = $_POST['father_name'];
    $aadhar_number = $_POST['aadhar_number'];
    $pan_number = $_POST['pan_number'];
    $aadress = $_POST['aadress'];
    $city = $_POST['city'];
    $pincode = $_POST['pincode'];
    $dob = $_POST['dob'];

    $sb = [
        'aadhar_name' => $aadhar_name,
        'father_name' => $father_name,
        'address' => $aadress,
        'city' => $city,
        'pincode' => $pincode,
        'dob' => $dob,
        'kyc_status' => KYC_PROCESSING,
    ];

    // Define the condition for updating
    $where = ['id' => $user_id];
    $db->update('ai_users', $sb, ['id' => $user_id]);
    redirect('login.php', 'KYC Submitted. Please wait for Approval', 'success');
}
