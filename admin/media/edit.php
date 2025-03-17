<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$id = $_GET['id'] ?? null;
if ($id == null) {
    redirect(admin_url('media/index.php'));
}
if (isset($_POST['btn_update'])) {

    $sb = [];
    $sb['img_title'] = $_POST['title'];
    $db->update('ai_media', $sb, ['id' => $id]);
    session()->set_flashdata('success', 'Media updated successfully');
    redirect(admin_url('media/index.php'));
}
$item = $db->select('ai_media', ['id' => $id], 1)->row();
$menu = 'members';
include "../common/header.php";
?>
<div class="page-header">
    <h5>Edit Media</h5>
</div>
<form method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-sm-6">
            <div class="card card-body">
                <div class="mb-3">
                    <label>Title</label>
                    <input name="title" required type="text" value="<?= $item->img_title; ?>" class="form-control">
                </div>
                <div>
                    <input type="hidden" name="btn_update" value="Update">
                    <button type="submit" class="btn btn-primary btn-submit">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>
<?php
include "../common/footer.php";
?>