<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$user_id = admin_id();
if (isset($_POST['conf_changepass'])) {
    $current_pass = $_POST['current_pass'];
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    if ($new_pass == $confirm_pass) {
        $user = $db->select('ai_admin', ['id' => $user_id])->row();
        if ($user->password == $current_pass) {
            $sb = [];
            $sb['password'] = $new_pass;
            $db->update('ai_admin', $sb, ['id' => $user_id]);
            session()->set_flashdata('success', "Your password has been changed");
            redirect('change-password.php');
        } else {
            session()->set_flashdata('danger', "Old password is not matching");
            redirect('change-password.php');
        }
    } else {
        session()->set_flashdata('danger', "New password and confirm password not matching.");
        redirect('change-password.php');
    }
}

$menu = 'settings';
include '../common/header.php';
?>
<div class="page-header">
    <h5>Change Password</h5>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="old_password">Current Password</label>
                        <input type="password" class="form-control" name="current_pass" id="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control" name="new_pass" id="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" class="form-control" name="confirm_pass" id="confirm_password" required>
                    </div>
                    <input type="hidden" name="conf_changepass" value="Submit">
                    <button class="btn btn-primary btn-submit">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include "../common/footer.php";
