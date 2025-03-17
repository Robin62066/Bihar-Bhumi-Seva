<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

if (isset($_GET['act']) && $_GET['act'] == 'del') {
    $id = $_GET['id'];
    $db->delete('ai_resume_upload', ['id' => $id]);
    set_flashdata("success", "Notification Item deleted");
}
$items = $db->select('ai_resume_upload')->result();
$menu = 'cms';
include '../common/header.php';
$perm = getPermission(Permission::MEDIA);

?>
<div class="page-header">
    <h5>Careers</h5>


</div>
<div id="origin">
    <div class="card">
        <div class="card-body">
            <table class="table data-table">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Resume</th>
                        <th>Designation</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sl = 1;
                    foreach ($items as $item) {
                    ?>
                        <tr>
                            <td><?= $sl++; ?></td>
                            <td><?= $item->fullname; ?></td>
                            <td><?= $item->email; ?></td>
                            <td><?= $item->mobile; ?></td>
                            <td><?php
                                if ($item->resume != '') {
                                ?>
                                    <img src="<?= base_url(upload_dir($item->resume)) ?>" width="100" height="100" />
                                <?php
                                }
                                ?>
                            </td>
                            <td><?= $item->designation; ?></td>
                            <td><?= $item->created; ?></td>

                            <td>
                                <a href="<?= admin_url('media/careers.php?id=' . $item->id . '&act=del'); ?>" class="btn btn-xs btn-danger btn-confirm" data-msg="Are you sure to Delete?" style="padding: 0 32px;">Delete</a>
                                <a href="<?php $item->resume ?>" download="image.jpg" class="btn btn-primary btn-xs">Download Image</a>

                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
include "../common/footer.php";
?>