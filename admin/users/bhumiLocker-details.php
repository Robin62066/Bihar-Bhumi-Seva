<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');


$id = $_GET['id'];
$user = $db->select('ai_users', ['id' => $id], 1)->row();

$item = $db->select('ai_bhumifiles', ['user_id' => $id], 1)->row();

include "../common/header.php";
?>
<div id="origin">
    <div class="page-header">
        <h5>Details #<?= $id; ?></h5>
        <div>
            <a href="<?= admin_url('users/edit-user.php?id=' . $user->id) ?>" class='btn btn-primary btn-sm'>Edit</a>
            <buttton @click="sendPassword('<?= $user->first_name; ?>', '<?= $user->mobile_number; ?>', '<?= $user->passwd; ?>')" class='btn btn-dark btn-sm'>Send Login</buttton>
            <a href="<?= site_url('user-profile.php?id=' . $user->id) ?>" target="_blank" class='btn btn-sm btn-info'>Public View Page</a>
        </div>
    </div>
    <div class="mb-3">
        <a href="<?= admin_url('users/view.php?id=' . $user->id) ?>" class='btn btn-sm btn-secondary'>Basic Details </a>
        <a href="<?= admin_url('users/property-details.php?id=' . $user->id) ?>" class='btn btn-sm btn-secondary'>Property Details</a>
        <a href="<?= admin_url('users/business-profile-details.php?id=' . $user->id) ?>" class='btn btn-sm btn-secondary'>Business Profile</a>
        <a href="<?= admin_url('users/mutation-details.php?id=' . $user->id) ?>" class='btn btn-sm btn-secondary'>Mutation Details </a>
        <a href="<?= admin_url('users/service-details.php?id=' . $user->id) ?>" class='btn btn-sm btn-secondary'>Services</a>
        <a href="<?= admin_url('users/bhumiLocker-details.php?id=' . $user->id) ?>" class='btn btn-sm btn-secondary'>Bhumi Locker </a>
    </div>
    <?php if ($item) { ?>
        <div class="row ">
            <div class="col">
                <div class="card">
                    <div class="card-header">Bhumi Locker Details</div>
                    <table class="table m-0">
                        <tbody>
                            <tr>
                                <td>Id No</td>
                                <td><?= $item->id; ?></td>
                            </tr>
                            <tr>
                                <td>File Name</td>
                                <td><?= $item->file_name; ?></td>
                            </tr>
                            <tr>
                                <td>Raiyat Name</td>
                                <td><?= $item->raiyat_name; ?></td>
                            </tr>
                            <tr>
                                <td>Guardian Name</td>
                                <td><?= $item->guardian_name; ?></td>
                            </tr>
                            <tr>
                                <td>Khata No</td>
                                <td><?= $item->khata_no; ?></td>
                            </tr>
                            <tr>
                                <td>Khesra No</td>
                                <td><?= $item->khesra_no; ?></td>
                            </tr>
                            <tr>
                                <td>Jamabandi</td>
                                <td><?= $item->jamabandi; ?></td>
                            </tr>
                            <tr>
                                <td>Mauja No</td>
                                <td><?= $item->mauja_no; ?></td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td><?= $item->address; ?></td>
                            </tr>

                            <tr>
                                <td>Created</td>
                                <td><?= $item->created; ?></td>
                            </tr>
                            <tr>
                                <td>Updated</td>
                                <td><?= $item->updated; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="bg-danger text-white p-4">No Data Found</div>
    <?php } ?>

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