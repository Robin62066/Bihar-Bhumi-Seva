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
        <div><a href="<?= admin_url('users/edit-user.php?id=' . $user->id) ?>" class='btn btn-primary btn-sm'>Edit</a>
            <buttton @click="sendPassword('<?= $user->first_name; ?>', '<?= $user->mobile_number; ?>', '<?= $user->passwd; ?>')" class='btn btn-dark btn-sm'>Send Login</buttton>
            <a href="<?= site_url('user-profile.php?id=' . $user->id) ?>" target="_blank" class='btn btn-sm btn-info'>Public View Page</a>
        </div>
    </div>
    <div class="mb-3">
        <a href="<?= admin_url('users/view.php?id=' . $user->id) ?>" class='btn btn-sm btn-secondary'>Basic Details </a>
        <a href="<?= admin_url('users/property-details.php?id=' . $user->id) ?>" class='btn btn-sm btn-secondary'>Property Details</a>
        <a href="<?= admin_url('users/business-profile-details.php?id=' . $user->id) ?>" class='btn btn-sm btn-secondary'>Business Profile</a>
        <a href="<?= admin_url('users/mutation-details.php?id=' . $user->id) ?>" class='btn btn-sm btn-secondary'>Mutation Details </a>
        <a href="<?= admin_url('users/service-details.php?id=' . $user->id) ?>" class='btn btn-sm btn-secondary'>servicess</a>
        <a href="<?= admin_url('users/bhumiLocker-details.php?id=' . $user->id) ?>" class='btn btn-sm btn-secondary'>Bhumi Locker </a>

    </div>
    <div class="row mb-3">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">Personal Details</div>
                <table class="table m-0">
                    <tbody>
                        <tr>
                            <td>Id No</td>
                            <td><?= $user->id; ?></td>
                        </tr>
                        <tr>
                            <td>Name</td>
                            <td><?= $user->first_name . ' ' . $user->last_name; ?></td>
                        </tr>
                        <tr>
                            <td>Mobile Number</td>
                            <td><?= $user->mobile_number; ?></td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td><?= $user->passwd; ?></td>
                        </tr>
                        <tr>
                            <td>login OTP</td>
                            <td><?= $user->mobile_otp; ?></td>
                        </tr>
                        <tr>
                            <td>User Type</td>
                            <td><?= user_type_string($user->user_type); ?></td>
                        </tr>
                        <tr>
                            <td>PAN</td>
                            <td><?= $user->pan_number; ?></td>
                        </tr>
                        <tr>
                            <td>Aadhar Number</td>
                            <td><?= $user->aadhar_number; ?></td>
                        </tr>
                        <tr>
                            <td>Created</td>
                            <td><?= $user->created; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">Payment Details</div>
                <table class="table m-0">
                    <tbody>
                        <tr>
                            <td>Bhumi Locker</td>
                            <td><?php
                                if ($user->pay_status == 0) echo '<span class="badge bg-warning">Pending</span>';
                                if ($user->pay_status == 1) echo '<span class="badge bg-success">Paid</span>';
                                if ($user->pay_status == 2) echo '<span class="badge bg-danger">Failed</span>';
                                ?></td>
                        </tr>
                        <tr>
                            <td>Payment Date</td>
                            <td><?= $user->pay_date; ?></td>
                        </tr>
                        <tr>
                            <td>Pay Mode</td>
                            <td><?= $user->pay_mode; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
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