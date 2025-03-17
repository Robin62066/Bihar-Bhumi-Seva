<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

if (isset($_GET['act']) && $_GET['act'] == 'del') {
    $id = $_GET['id'];

    $db->delete('ai_resume_upload', ['id' => $id]);
    set_flashdata("success", "Record deleded successfully");
}
$items = $db->select('ai_resume_upload')->result();
$menu = 'cms';
include '../common/header.php';
$perm = getPermission(Permission::MEDIA);

?>
<div class="page-header">
    <h5>Uploaded Resumes</h5>

</div>
<div class="card">
    <div class="card-body">
        <table class="table data-table">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>Name</th>
                    <th>Email Id</th>
                    <th>Mobile</th>
                    <th>Designaton</th>
                    <th>Resume</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sl = 0;
                foreach ($items as $item) {
                ?>
                    <tr>
                        <td><?= $sl++; ?></td>
                        <td><?= $item->fullname; ?></td>
                        <td><?= $item->email; ?></td>
                        <td><?= $item->mobile; ?></td>
                        <td><?= $item->designation; ?></td>
                        <td>
                            <?php
                            if ($item->resume != '') {
                            ?>
                                <img src="<?= base_url(upload_dir($item->resume)); ?>" width="100" />
                            <?php
                            }
                            ?>
                        </td>
                        <td><?= $item->created; ?></td>
                        <td>
                            <div class="d-flex gap-2">

                                <a href="<?= base_url(upload_dir($item->resume)); ?>" class="btn btn-xs btn-primary" download>download</a>
                                <a href="<?= admin_url('media/resume-uploaded.php?id=' . $item->id); ?>&act=del" class="btn btn-xs btn-danger btn-delete">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php
include "../common/footer.php";
