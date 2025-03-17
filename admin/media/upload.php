<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

if (isset($_POST['btn_update'])) {

    $sb = [];
    $sb['img_title'] = $_POST['title'];
    $sb['file_name'] = do_upload('upload');
    $sb['created'] = date("Y-m-d H:i:s");
    $db->insert('ai_media', $sb);
    session()->set_flashdata('success', 'Media upload successfully');
    redirect(admin_url('media/index.php'));
}
$menu = 'members';
include "../common/header.php";
?>
<div class="page-header">
    <h5>Upload Media</h5>
</div>
<form method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-sm-6">
            <div class="card card-body">
                <div class="mb-3">
                    <label>Title</label>
                    <input name="title" required type="text" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Select File</label>
                    <input type="file" name="upload" class="form-control">
                </div>
                <div>
                    <input type="hidden" name="btn_update" value="Update">
                    <button type="submit" class="btn btn-primary btn-submit">Upload</button>
                </div>
            </div>
        </div>
    </div>
</form>
<?php
include "../common/footer.php";
?>