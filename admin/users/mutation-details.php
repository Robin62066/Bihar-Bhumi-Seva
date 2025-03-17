<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');


$id = $_GET['id'];
$user = $db->select('ai_users', ['id' => $id], 1)->row();
$itemApp = $db->select('ai_mutations_app', ['user_id' => $id], 1)->row();
$itemWeb = $db->select('ai_mutations', ['user_id' => $id], 1)->row();

include "../common/header.php";
?>
<div id="origin">
    <div class="page-header">
        <h5>All Mutations dtails #<?= $id; ?></h5>
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
        <a href="<?= admin_url('users/bhumiLocker-details.php?id=' . $user->id) ?>" class='btn btn-sm btn-secondary'>Bhumi Loger </a>
    </div>
    <?php if ($itemWeb) { ?>
        <div class="row ">
            <div class="col">
                <div class="card">
                    <div class="card-header">Mutations Details (Web)</div>
                    <table class="table m-0">
                        <tbody>
                            <tr>
                                <th>Id No </th>
                                <td><?= $itemWeb->id; ?></td>
                                <th>Name </th>
                                <td><?= $itemWeb->fname; ?></td>
                                <th>Guardian</th>
                                <td><?= $itemWeb->guardian;  ?></td>
                                <th>Relation </th>
                                <td><?= $itemWeb->relation;  ?></td>

                            </tr>

                            <tr>
                                <th>Mutation Type</th>
                                <td><?= $itemWeb->mutation_type; ?></td>
                                <th>Email ID </th>
                                <td><?= $itemWeb->email_id; ?></td>
                                <th>Mobile </th>
                                <td><?= $itemWeb->mobile; ?></td>
                                <th>Village </th>
                                <td><?= $itemWeb->village; ?></td>
                            </tr>
                            <tr>
                                <th>address </th>
                                <td><?= $itemWeb->address;  ?></td>
                                <th>state code</th>
                                <td><?= $itemWeb->state_id; ?></td>
                                <th>pincode </th>
                                <td><?= $itemWeb->pincode; ?></td>
                                <th>Dist</th>
                                <td><?= $itemWeb->dist_id; ?></td>


                            </tr>
                            <tr>
                                <th>Created </th>
                                <td><?= $itemWeb->created; ?></td>
                                <th>Updated </th>
                                <td><?= $itemWeb->updated; ?></td>
                                <th>Status </th>
                                <td><?= $itemWeb->status; ?></td>
                                <th>order_id </th>
                                <td><?= $itemWeb->order_id; ?></td>
                            </tr>
                            <tr>
                                <th>Pay Date </th>
                                <td><?= $itemWeb->pay_date; ?></td>
                                <th>Notes </th>
                                <td><?= $itemWeb->notes; ?></td>
                                <th>Ing </th>
                                <td><?= $itemWeb->lng; ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php }
    if ($itemApp) { ?>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">Mutations Details (App)</div>
                    <table class="table m-0">
                        <tbody>
                            <tr>
                                <th>Id No </th>
                                <td><?= $itemApp->id; ?></td>
                                <th>Deed No </th>
                                <td><?= $itemApp->deed_no; ?></td>
                                <th>Document </th>
                                <td><?= $itemApp->documents; ?></td>
                                <th>Created </th>
                                <td><?= $itemApp->created; ?></td>

                            </tr>

                            <tr>
                                <th>updated </th>
                                <td><?= $itemApp->updated;  ?></td>
                                <th>Comments </th>
                                <td><?= $itemApp->comments; ?></td>
                                <th>Status </th>
                                <td><?= $itemApp->status; ?></td>
                                <th>Email Id </th>
                                <td><?= $itemApp->email_id; ?></td>

                            </tr>
                            <tr>
                                <th>Whatsapp </th>
                                <td><?= $itemApp->whatsapp; ?></td>
                                <th>Years </th>
                                <td><?= $itemApp->years; ?></td>
                                <th>Attachment </th>
                                <td><?= $itemApp->attachment; ?></td>
                                <th>Pay status </th>
                                <td><?= $itemApp->pay_status; ?></td>

                            </tr>
                            <tr>
                                <th>Pay date </th>
                                <td><?= $itemApp->pay_date; ?></td>
                                <th>pay data </th>
                                <td><?= $itemApp->pay_data; ?></td>
                                <th> Rzp order id </th>
                                <td><?= $itemApp->rzp_order_id; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php }
    if (($itemApp) == null && ($itemWeb) == null) {
    ?>
        <div class="bg-danger text-white p-4">No data Found</div>
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