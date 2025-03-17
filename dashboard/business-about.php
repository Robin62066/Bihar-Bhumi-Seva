<?php
require('../config/autoload.php');
if (!is_login()) redirect(base_url('login.php'), 'You must login to continue');
$user_id = user_id();
$item = $db->select('ai_users', ['id' => $user_id], 1)->row();
$dists = $db->select('ai_districts', [], false, 'dist_name ASC')->result();

if (isset($_POST['clicked'])) {
    $sb = $_POST['form'];
    if (isset($_POST['remove'])) {
        $sb['logo'] = '';
    }
    if ($_FILES['logo']['name'] != '') {
        $sb['logo'] = do_upload('logo', true);
    }

    $db->update('ai_profiles', $sb, ['user_id' => $user_id]);
    session()->set_flashdata('success', "Profile Details updated");
    redirect(base_url('dashboard/profile.php'));
}

$profile = $db->select('ai_profiles', ['user_id' => $user_id], 1)->row();


include "../common/header.php";
?>
<div class="dashboard">
    <div class="container py-5">
        <div class="user-panel">
            <div class="row">
                <?php
                $subpage = 'business';
                include_once "dashboard-menu.php"; ?>
                <div class="col-sm-9">
                    <?= front_view('common/alert'); ?>
                    <div id="origin">
                        <form enctype="multipart/form-data" action="" method="post">
                            <div class="bg-white shadow-sm p-4 rounded-sm">
                                <div class="page-header">
                                    <h5>Create Business Profile - Step 2</h5>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <label>About your Business</label>

                                        <textarea rows="6" name="form[about_text]" class="form-control"><?= $profile->about_text; ?></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <label>Logo</label>
                                        <input type="file" name="logo" class="form-control" />
                                        <?php
                                        if ($profile->logo != '') {
                                        ?>
                                            <img src="<?= base_url(upload_dir($profile->logo)) ?>" alt="" class="img-fluid" /> <br />
                                            <label>
                                                <input type="checkbox" name="remove" value="1" />
                                                Remove
                                            </label>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <label>Facebook Link</label>
                                        <input type="text" name="form[fb_link]" value="<?= $profile->fb_link; ?>" class="form-control" />
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Twitter Link</label>
                                        <input type="text" name="form[tw_link]" value="<?= $profile->tw_link; ?>" class="form-control" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <label>Youtube Link</label>
                                        <input type="text" name="form[yt_link]" value="<?= $profile->yt_link; ?>" class="form-control" />
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Instagram Link</label>
                                        <input type="text" name="form[insta_link]" value="<?= $profile->insta_link; ?>" class="form-control" />
                                    </div>
                                </div>
                                <input type="hidden" name="clicked" value="1">
                                <button class="btn btn-primary btn-submit">SAVE</button>
                                <a href="<?= site_url('dashboard'); ?>" class="btn btn-dark">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include "../common/footer.php";
?>