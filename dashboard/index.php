<?php
include "../config/autoload.php";
if (!is_login()) redirect(base_url('login.php'), 'Please Login to continue', 'danger');
$user_id = user_id();
$user = $db->select('ai_users', ['id' => $user_id], 1)->row();
if ($user->kyc_status != KYC_APPROVED) {
    set_userdata("user", $user);
    // if ($user->mobile_verified == 0) {
    //     $otp = rand(1111, 9999);
    //     $db->update('ai_users', ['mobile_otp' => $otp], ['id' => $user->id]);
    //     $_SESSION['verification_id'] = $user->id;
    //     sendSMS($user->first_name, $otp, $user->mobile_number);
    //     redirect(base_url('account-verify.php'), "Please verify your mobile");
    // }
    // if ($user->signup_completed == 0) {
    //     redirect(base_url('photo-upload.php'), "Kindly upload your profile photo.");
    // }
    // if ($user->user_type != USER_CUSTOMER) {
    //     if ($user->pan_verified == 0) {
    //         redirect(base_url('pan-verification.php'), "Kindly verify your PAN before starting.");
    //     } else if ($user->aadhar_verified == 0) {
    //         redirect(base_url('aadhar-verification.php'), "Kindly verify your Aadhar Number.");
    //     } else if ($user->image == '') {
    //         redirect(base_url('photo-upload.php'), "Kindly upload your profile photo.");
    //     }
    // }
}
session()->bhumilogin = null; // clear bhumi login
include "../common/header.php";
?>
<div class="dashboard">
    <div class="container py-5">
        <div class="user-panel">
            <?php
            if ($user->admin_verified == 0) {
            ?>
                <!-- <div class="alert alert-warning">Your Account is under review. Please wait for Confirmation.</div> -->
            <?php
            }
            ?>
            <div class="row">
                <?php
                include_once "dashboard-menu.php"; ?>
                <div class="col-sm-9">
                    <div class="page-header">
                        <h5>Dashboard</h5>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-sm-4">
                            <div class="bg-white p-2 rounded-sm shadow-sm text-center py-4">
                                <h6>Total Properties</h6>
                                <b>00</b>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="bg-white p-2 rounded-sm shadow-sm text-center py-4">
                                <h6>My Services</h6>
                                <b>000</b>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="bg-white p-2 rounded-sm shadow-sm text-center py-4">
                                <h6>Notifications</h6>
                                <b>00</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include "../common/footer.php";
