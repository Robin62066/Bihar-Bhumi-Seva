<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');


$id = $_GET['id'];
$user = $db->select('ai_users', ['id' => $id], 1)->row();
$items = $db->select('ai_sites', ['user_id' => $id])->result();
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
        <div class="card-header"><b>Property Details</b></div>
        <table class="table">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>Photos</th>
                    <th>Site Title</th>
                    <th>Details</th>
                    <th>Address</th>
                    <th>Mauja</th>
                    <th>Jamabandi_no</th>
                    <th>Page No</th>
                    <th>jamabani Rayati Name</th>
                    <th>Guardian Name</th>
                    <th>Location</th>
                    <th>Amount</th>
                    <th>Pay Status</th>
                    <th>Order Id</th>
                    <th>Pay Date</th>
                    <th>Membership</th>
                    <th>Property For</th>
                    <th>Property Type</th>
                    <th>Actions</th>
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
                            <?php
                            if ($item->photo_front != '' && $item->photo_back != '') {
                            ?>
                                <img src="<?= base_url(upload_dir($item->photo_front)) ?>" width="100" class="p-2" />
                                <img src="<?= base_url(upload_dir($item->photo_back)) ?>" width="100" class="p-2" />
                            <?php
                            }
                            ?>
                        </td>
                        <td><?= $item->site_title; ?></td>
                        <td><?= $item->details; ?></td>
                        <td><?= $item->address; ?></td>
                        <td><?= $item->mauja; ?></td>
                        <td><?= $item->jamabandi_no; ?></td>
                        <td><?= $item->page_no; ?></td>
                        <td><?= $item->jamabani_raiyat_name; ?></td>
                        <td><?= $item->guardian_name; ?></td>
                        <td><?= $item->location; ?></td>
                        <td><?= $item->amount; ?></td>
                        <td><?= $item->pay_status; ?></td>
                        <td><?= $item->order_id; ?></td>
                        <td><?= $item->pay_date; ?></td>
                        <td><?= $item->memberhsip; ?></td>
                        <td><?= $item->property_for; ?></td>
                        <td><?= $item->property_type; ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="<?= admin_url('properties/details.php?id=' . $item->id); ?>" class="btn btn-xs btn-primary">Details</a>
                                <?php
                                if ($perm->canDelete()) {
                                ?>
                                    <a href="<?= admin_url('properties/?id=' . $item->id); ?>&act=del" class="btn btn-xs btn-danger btn-delete">Delete</a>
                                <?php
                                }
                                ?>
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