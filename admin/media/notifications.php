<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

if (isset($_GET['act']) && $_GET['act'] == 'del') {
    $id = $_GET['id'];
    $db->delete('ai_notifications', ['id' => $id]);
    set_flashdata("success", "Notification Item deleted");
}
$items = $db->select('ai_notifications')->result();
$menu = 'cms';
include '../common/header.php';
$perm = getPermission(Permission::MEDIA);

?>
<div class="page-header">
    <h5>Notifications</h5>
    <?php
    if ($perm->canCreateNew()) {
    ?>
        <a href="<?= admin_url('media/create-notifications.php'); ?>" class="btn btn-sm btn-primary"><i class="bi-plus"></i> Create</a>
    <?php
    }
    ?>
</div>
<div id="origin">
    <div v-if="errmsg.length>0" class="alert" :class="errcls">{{errmsg}}</div>
    <div class="card">
        <div class="card-body">
            <table class="table data-table">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Title</th>
                        <th>Notice Text</th>
                        <th>Created</th>
                        <th>Send To</th>
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
                            <td><?= $item->title; ?></td>
                            <td><?= $item->message; ?></td>
                            <td><?= $item->created; ?></td>
                            <td><?= $item->sendto; ?></td>

                            <td>
                                <a href="<?= admin_url('media/notifications.php?id=' . $item->id . '&act=del'); ?>" class="btn btn-xs btn-danger btn-confirm" data-msg="Are you sure to Delete?">Delete</a>
                                <button @click="sendNotice('<?= $item->title; ?>', '<?= $item->message; ?>')" class="btn btn-primary btn-xs">Send Notice</button>
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

<script>
    new Vue({
        el: '#origin',
        data: {
            errmsg: '',
            errcls: ''
        },
        methods: {
            sendNotice: function(title, message) {
                let url = 'https://services.biharbhumiseva.in/api/send-push'
                axios.post(url, {
                    title: title,
                    message: message
                }, {
                    'headers': {
                        'Content-Type': 'text/plain'
                    }
                }).then(result => {
                    let resp = result.data;
                    this.errmsg = resp.message;
                    if (resp.success) {
                        this.errcls = 'alert-success';
                    } else {
                        this.errcls = 'alert-danger';
                    }
                })
            }
        }
    })
</script>