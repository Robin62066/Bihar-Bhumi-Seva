<?php
include_once "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$id = input_get('ticket_id');

if (input_post('button')) {
    $status = input_post('status');
    $db->update('ai_complains', ['status' => $status, 'updated' => date('Y-m-d H:i:s')], ['id' => $id]);
    set_flashdata('success_msg', "Status updated successfully");
}
$item = $db->select("ai_complains", ['id' => $id], 1)->row();
$menu = 'supports';
include "../common/header.php";

?>
<div id="origin">

    <div class="page-header">
        <h5>Ticket Id: #<?= $id; ?></h5>
    </div>
    <?php
    if ($item->status == 0) {
    ?>
        <div class="alert alert-dark">This complain has been closed.</div>
    <?php
    }
    ?>
    <div class="bg-white p-3">
        <form action="" method="post">
            <table class="table m-0">
                <tbody>
                    <tr>
                        <td>Full name</td>
                        <td><?= $item->yourname; ?></td>
                    </tr>
                    <tr>
                        <td>Mobile no</td>
                        <td><?= $item->mobile; ?></td>
                    </tr>
                    <tr>
                        <td>Email Id</td>
                        <td><?= $item->email_id; ?></td>
                    </tr>
                    <tr>
                        <td>Created</td>
                        <td class="text-success"><?= $item->created; ?></td>
                    </tr>
                    <tr>
                        <td>Last Updated</td>
                        <td class="text-danger"><?= $item->updated; ?></td>
                    </tr>
                    <tr>
                        <td>Details</td>
                        <td><?= $item->details; ?></td>
                    </tr>
                    <tr>
                        <td>Status </td>
                        <td>
                            <select name="status" class="form-select" style="width: 200px;">
                                <option value="">Select</option>
                                <option value="1" <?= $item->status == 1 ? 'selected' : ''; ?>>Open</option>
                                <option value="0" <?= $item->status == 0 ? 'selected' : ''; ?>>Closed</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <button name="button" value="update" class="btn btn-primary">UPDATE</button>
                            <a href="<?= admin_url('supports') ?>" class="btn btn-dark">CANCEL</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>
<?php
include "../common/footer.php";
