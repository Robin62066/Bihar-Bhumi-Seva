<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

if (isset($_GET['act']) && $_GET['act'] == 'del') {
    $id = $_GET['id'];
    $db->delete('ai_media', ['id' => $id]);
    set_flashdata("success", "Media Item deleted");
}
$items = $db->select('ai_media')->result();
$menu = 'cms';
include '../common/header.php';
$perm = getPermission(Permission::MEDIA);

?>
<div class="page-header">
    <h5>All Media</h5>
    <?php
    if ($perm->canCreateNew()) {
    ?>
        <a href="<?= admin_url('media/upload.php'); ?>" class="btn btn-sm btn-primary"><i class="bi-plus"></i> Upload</a>
    <?php
    }
    ?>
</div>
<div class="card">
    <div class="card-body">
        <table class="table data-table">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Url</th>
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
                        <td>
                            <img src="<?= base_url(upload_dir($item->file_name)) ?>" width="80" />
                        </td>
                        <td><?= $item->img_title; ?></td>
                        <td><a href="<?= base_url(upload_dir($item->file_name)) ?>"><?= $item->file_name; ?></a></td>
                        <td>
                            <?php
                            if ($perm->canEdit()) {
                            ?>
                                <a href="<?= admin_url('media/edit.php?id=' . $item->id); ?>" class="btn btn-sm btn-primary">Edit </a>
                            <?php
                            }
                            if ($perm->canDelete()) {
                            ?>
                                <a href="<?= admin_url('media/index.php?id=' . $item->id . '&act=del'); ?>" class="btn btn-sm btn-danger btn-delete">Delete </a>
                            <?php
                            }
                            ?>
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
