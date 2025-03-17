<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$permission = getPermission();
$permission->setModule(Permission::USERS);

$id = $_GET['id'];
$act = $_GET['act'] ?? null;

$user = $db->select('ai_profiles', ['user_id' => $id], 1)->row();

$menu = 'users';
include "../common/header.php";
?>
<div class="page-header">
    <h5>Business Peofile Details #<?= $id; ?></h5>
</div>
<div class="mb-3">
    <a href="<?= admin_url('users/view.php?id=' . $id) ?>" class='btn btn-sm btn-secondary'>Basic Details </a>
    <a href="<?= admin_url('users/property-details.php?id=' . $id) ?>" class='btn btn-sm btn-secondary'>Property Details</a>
    <a href="<?= admin_url('users/business-profile-details.php?id=' . $id) ?>" class='btn btn-sm btn-secondary'>Business Profile</a>
    <a href="<?= admin_url('users/mutation-details.php?id=' . $id) ?>" class='btn btn-sm btn-secondary'>Mutation Details </a>
    <a href="<?= admin_url('users/service-details.php?id=' . $id) ?>" class='btn btn-sm btn-secondary'>Services</a>
    <a href="<?= admin_url('users/bhumiLocker-details.php?id=' . $id) ?>" class='btn btn-sm btn-secondary'>Bhumi Locker </a>
</div>


<div class="card p-3">
    <div class="card-header">Business Profile details</div>
    <table class="table">
        <?php if ($user) { ?>
            <thead>
                <tr>
                    <th>Legal Name</th>
                    <td><?= $user->legal_name; ?></td>
                    <th>Business Type</th>
                    <td><?= $user->business_type; ?></td>
                </tr>
                <tr>

                    <th>Address</th>
                    <td><?= $user->address; ?></td>

                    <th>City</th>
                    <td><?= $user->city; ?></td>


                </tr>
                <tr>
                    <th>Dist</th>
                    <td><?= $user->dist_id; ?></td>
                    <th>GSTIN</th>
                    <td><?= $user->gstin; ?></td>
                </tr>
                <tr>
                    <th>Contact Numbers</th>
                    <td><?= $user->mobile;  ?></td>
                    <th>Whatsapp Number</th>
                    <td><?= $user->mobile2; ?></td>

                </tr>
                <tr>
                    <th>About Text</th>
                    <td><?= $user->about_text; ?></td>
                    <th>Contact Text</th>
                    <td><?= $user->contact_text; ?></td>
                </tr>
                <tr>
                    <th>Created</th>
                    <td><?= $user->created; ?></td>
                    <th>Updated</th>
                    <td><?= $user->updated; ?></td>
                </tr>

                <tr>
                    <th>Fb Link</th>
                    <td><?= $user->fb_link; ?></td>
                    <th>Tw Link</th>
                    <td><?= $user->tw_link; ?></td>
                </tr>
                <tr>
                    <th>Yt Link</th>
                    <td><?= $user->yt_link; ?></td>
                    <th>insta Link</th>
                    <td><?= $user->insta_link; ?></td>
                </tr>
                <tr>
                    <th>snap Link</th>
                    <td><?= $user->snap_link; ?></td>
                    <th>Staus</th>
                    <td><?= $user->status; ?></td>
                </tr>
                <tr>
                </tr>
            </thead>
        <?php } else { ?>
            <div class="bg-danger text-white p-4">No data Found</div>
        <?php } ?>
    </table>
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