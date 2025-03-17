<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$id = $_GET['id'];
$user = $db->select('ai_profiles', ['user_id' => $id], 1)->row();
$dist = $db->select('ai_districts', ['id' => $user->dist_id], 1)->row();

if (isset($_POST['btn_update'])) {
    $fields = $_POST['form'];
    if (isset($_FILES['logo']['name']) && $_FILES['logo']['name'] != '') {
        $fields['logo'] = do_upload('logo');
    }

    $db->update('ai_profiles', $fields, ['user_id' => $id]);
    set_flashdata('success_msg', 'Profile updated successfully');
    redirect(admin_url('users/edit-business.php?id=' . $id));
}
$menu = 'members';
include "../common/header.php";
?>
<div class="page-header">
    <h5>Edit Business Profile</h5>
</div>
<form method="post">
    <div class="row">
        <div class="col-sm-8">
            <div class="card p-3">
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Category name</label>
                        <input name="form[category]" type="text" value="<?= user_type_string($user->category); ?>" class="form-control">
                    </div>
                    <div class="col-sm-6">
                        <label>Business Type</label>
                        <input name="form[business_type]" type="text" value="<?= businessAccountType($user->business_type); ?>" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Legal Name</label>
                        <textarea name="form[legal_name]" rows="4" type="text" class="form-control"><?= $user->legal_name; ?></textarea>

                    </div>
                    <div class="col-sm-6">
                        <label>Gstin</label>
                        <input name="form[gstin]" type="text" value="<?= $user->gstin; ?>" class="form-control">
                        <label>mobile</label>
                        <input name="form[mobile]" type="text" value="<?= $user->mobile; ?>" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Email id</label>
                        <input name="form[email_id]" type="text" value="<?= $user->email_id; ?>" class="form-control">
                    </div>

                    <div class="col-sm-6">
                        <label>Whatsapp Number</label>
                        <input name="form[mobile2]" type="text" value="<?= $user->mobile2; ?>" class="form-control">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Address</label>
                        <input name="form[address]" type="text" value="<?= $user->address; ?>" class="form-control">
                    </div>

                    <div class="col-sm-6">
                        <label>About</label>
                        <input name="form[about_text]" type="text" value="<?= $user->about_text; ?>" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Contact</label>
                        <input name="form[contact_text]" $id type="text" value="<?= $user->contact_text; ?>" class="form-control">
                    </div>
                    <div class="col-sm-6">
                        <label>logo</label>
                        <input name="logo" type="file" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Facebook Link</label>
                        <input name="form[fb_link]" type="text" value="<?= $user->fb_link; ?>" class="form-control">
                    </div>
                    <div class="col-sm-6">
                        <label>Twitter Link</label>
                        <input name="form[tw_link]" $id type="text" value="<?= $user->tw_link; ?>" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Insta Link</label>
                        <input name="form[insta_link]" type="text" value="<?= $user->insta_link; ?>" class="form-control">
                    </div>
                    <div class="col-sm-6">
                        <label>Snap Link</label>
                        <input name="form[snap_link]" $id type="text" value="<?= $user->snap_link; ?>" class="form-control">
                    </div>
                </div>



                <div>
                    <button name="btn_update" value="Update" type="submit" class="btn btn-primary">Update User</button>
                </div>
            </div>
        </div>

    </div>
</form>
<?php
include "../common/footer.php";
?>