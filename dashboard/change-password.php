<?php
include "../config/autoload.php";
if (!is_login()) redirect(base_url('login.php'), 'You must login to continue');

if (isset($_POST['conf_changepass'])) {
    $current_pass = $_POST['old-password'];
    $new_pass = $_POST['new-password'];
    $confirm_pass = $_POST['confirm-password'];

    if ($new_pass == $confirm_pass) {
        $user = $db->select('ai_users', ['id' => $user_id])->row();
        if ($user && isset($user->passwd) && $user->passwd == $current_pass) {
            $sb = [];
            $sb['passwd'] = $new_pass;
            $db->update('ai_users', $sb, ['id' => $user_id]);

            set_flashdata('success_msg', "Your password has been changed");
            redirect("index.php");
        } else {
            set_flashdata('error_msg', "Old password is not matching");
            redirect("change-password.php");
        }
    } else {
        set_flashdata('error_msg', "New password and confirm password not matching.");
        redirect("change-password.php");
    }
}
include "../common/header.php";
?>
<div class="dashboard">
    <div class="container py-5">
        <div class="user-panel">
            <div class="row">
                <?php
                $subpage = 'change-password';
                include_once "dashboard-menu.php";
                ?>
                <div class="col-sm-9">
                    <?= front_view('common/alert'); ?>
                    <div id="origin">
                        <div class="bg-white p-3 rounded-1">
                            <div class="page-header">
                                <h5>Change Password</h5>
                            </div>
                            <!-- Add the <form> tag here -->
                            <form action="" method="post">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="card1">
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <label>Old Password</label>
                                                    <input v-model="opass" type="password" class="form-control" name="old-password">
                                                </div>
                                                <div class="mb-2">
                                                    <label>New Password</label>
                                                    <input v-model="npass" type="password" class="form-control" name="new-password">
                                                </div>
                                                <div class="mb-2">
                                                    <label>Confirm Password</label>
                                                    <input v-model="cpass" type="password" class="form-control" name="confirm-password">
                                                </div>
                                                <input type="hidden" name="conf_changepass" value="change">
                                                <button class="btn btn-submit btn-primary">SUBMIT</button>
                                                <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary">Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!-- End of the <form> tag -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include "../common/footer.php";
?>