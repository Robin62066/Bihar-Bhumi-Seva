<?php
include "config/autoload.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_type = USER_CUSTOMER;
    $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
    $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
    $mobile_number = isset($_POST['mobile_number']) ? $_POST['mobile_number'] : '';
    $password = isset($_POST['passwd']) ? $_POST['passwd'] : '';
    $email_id = $_POST['email_id'] ?? '';

    // Example: Check if required fields are empty
    if (empty($mobile_number)) {
        $_SESSION['error_msg'] = 'Please fill out all required fields.';
        header("location: signup.php");
        exit;
    }

    // Duplicate checking of Aadhar, PAN, and mobile
    $check_resp = $db->select('ai_users', "mobile_number = '$mobile_number'", 1);
    if ($check_resp->count() > 0) {
        $_SESSION['error_msg'] = 'Mobile already registered. Use different details.';
        header("location: signup.php");
        exit;
    }

    $otp = rand(1111, 9999);
    sendSMS($first_name, $otp, $mobile_number, 'signup');

    // Insert into the users table
    $user = [
        'id' => time(),
        'first_name'    => $first_name,
        'last_name'     => $last_name,
        'user_type'     => $user_type,
        'email_id'      => $email_id,
        'created'       => date("Y-m-d H:i:s"),
        'status'        => 1,
        'kyc_status'    => KYC_PENDING,
        'mobile_otp'    => $otp,
        'mobile_verified' => 0,
        'mobile_number' => $mobile_number,
        'passwd'        => $password
    ];

    if ($db->insert('ai_users', $user)) {
        $id = $db->id();
        $_SESSION['success_msg'] = 'Your account has been created successfully';
        $_SESSION['verification_id'] = $id;
        redirect('account-verify.php');
    } else {
        $_SESSION['error_msg'] = "Error registering the user. Please try again later.";
        redirect('signup.php');
    }
} else {
    $_SESSION['error_msg'] = "Invalid request.";
    redirect('signup.php');
}
