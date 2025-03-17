<?php
include "config/autoload.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = isset($_POST['fullname']) ? $_POST['fullname'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $mobile_number = isset($_POST['mobile']) ? $_POST['mobile'] : '';
    $designation = isset($_POST['designation']) ? $_POST['designation'] : '';
    // Insert into the users table
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
        $id = $db->id();
        redirect('upload-resume.php', "successfull Submitted !!");
    } else {
        redirect('upload-resume.php', "Faild to submit");
    }
} else {

    redirect('upload-resume.php');
}
