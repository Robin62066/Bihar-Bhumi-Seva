<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');


$id = $_GET['id'];
$user = $db->select('ai_users', ['id' => $id], 1)->row();
$items = $db->select('ai_services', ['user_id' => $id])->result();
include "../common/header.php";
?>
<div id="origin">
    <div class="page-header">
        <h5>Details: #<?= $id; ?></h5>
        <div>
            <a href="<?= admin_url('users/edit-user.php?id=' . $user->id) ?>" class='btn btn-primary btn-sm'>Edit</a>
            <buttton @click="sendPassword('<?= $user->first_name; ?>', '<?= $user->mobile_number; ?>', '<?= $user->passwd; ?>')" class='btn btn-dark btn-sm'>Send Login</buttton>
            <a href="<?= site_url('user-profile.php?id=' . $user->id) ?>" target="_blank" class='btn btn-sm btn-info'>Public View Page</a>
        </div>
    </div>
    <div class="m-2">
        <a href="<?= admin_url('users/view.php?id=' . $user->id) ?>" class='btn btn-sm btn-secondary'>Basic Details </a>
        <a href="<?= admin_url('users/property-details.php?id=' . $user->id) ?>" class='btn btn-sm btn-secondary'>Property Details</a>
        <a href="<?= admin_url('users/business-profile-details.php?id=' . $user->id) ?>" class='btn btn-sm btn-secondary'>Business Profile</a>
        <a href="<?= admin_url('users/mutation-details.php?id=' . $user->id) ?>" class='btn btn-sm btn-secondary'>Mutation Details </a>
        <a href="<?= admin_url('users/service-details.php?id=' . $user->id) ?>" class='btn btn-sm btn-secondary'>Services</a>
        <a href="<?= admin_url('users/bhumiLocker-details.php?id=' . $user->id) ?>" class='btn btn-sm btn-secondary'>Bhumi Locker </a>
    </div>
    <div class="card">
        <div class="card-header"><b>Service Details</b></div>
        <table class="table">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>Service_name</th>
                    <th>Amount</th>
                    <th>Details</th>
                    <th>Location</th>
                    <th>Image</th>
                    <th>Created</th>
                    <th>status</th>

                </tr>
            </thead>
            <tbody>
                <?php
                $sl = 1;
                foreach ($items as $item) {
                ?>
                    <tr>
                        <td><?= $sl++; ?></td>
                        <td><?= $item->service_name; ?></td>
                        <td><?= $item->amount; ?></td>
                        <td><?= $item->details; ?></td>
                        <td><?= $item->locations; ?></td>
                        <td>
                            <?php
                            if ($item->image != '') {
                            ?>
                                <img src="<?= base_url(upload_dir($item->image)) ?>" width="100" />
                            <?php
                            }
                            ?>
                        </td>

                        <td><?= $item->created; ?></td>
                        <td>
                            <?php if ($item->status == 1) { ?>
                                <p class="p-2 bg-success text-white">active</p>
                            <?php } else { ?>
                                <p class="p-2 bg-danger text-white">pending</p>
                            <?php } ?>
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
?>
<script>
    new Vue({
        el: '#origin',
        data: {},
        methods: {
            sendPassword: function(name, mobile, passwd) {
                let url = ApiUrl + 'send-password'
                api_call('send-password', {
                    name,
                    mobile,
                    passwd
                }).then(resp => {
                    if (resp.success) {
                        alert(resp.message);
                    }
                })
            }
        }
    })
</script>