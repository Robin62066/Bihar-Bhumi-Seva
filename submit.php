<?php
include "config/autoload.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = isset($_POST['fullname']) ? $_POST['fullname'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $mobile_number = isset($_POST['mobile']) ? $_POST['mobile'] : '';
    $designation = isset($_POST['designation']) ? $_POST['designation'] : '';

    $user = [

        'fullname'    => $full_name,
        'email'     => $email,
        'mobile' => $mobile_number,
        'designation' => $designation
    ];
    if (isset($_FILES['resume']['name']) && $_FILES['resume']['name'] != '') {
        $user['resume'] = do_upload('resume');
    }
    if ($db->insert('ai_resume_upload', $user)) {
        echo "Form submitted successfully! File uploaded.";
    } else {
        echo "File not received.";
    }
} else {
    echo "Invalid request.";
}
