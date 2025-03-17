<?php
require('../config/autoload.php');
if (!is_login()) redirect(base_url('login.php'), 'You must login to continue');

$user_id = user_id();
$id = $_GET['id'] ?? null;
$item = $db->select('ai_bhumifiles', ['id' => $id], 1)->row();
if ($id == null || $item == null || $item->user_id != $user_id) {
    redirect('bhumi-locker.php', "Something wrong. Try again.");
}
if (isset($_POST['clicked'])) {
    $sb = [];
    $sb['prop_id'] = $id;
    $sb['file_name'] = do_upload('docs');
    $sb['created'] = date("Y-m-d H:i:s");
    $db->insert('ai_bhumi_docs', $sb);
    session()->set_flashdata('success', 'File uploaded Successfully');
}
$files = $db->select('ai_bhumi_docs', ['prop_id' => $id])->result();

include "../common/header.php";
?>


<div class="dashboard">
    <div class="container py-5">
        <div class="user-panel">
            <div class="row">
                <?php
                $subpage = 'bhumi';
                include_once "dashboard-menu.php"; ?>
                <div class="col-sm-9">
                    <?= front_view('common/alert'); ?>
                    <div id="origin">
                        <div class="bg-white p-4 rounded-1">
                            <div class="page-header">
                                <h5>Property Details</h5>
                                <a href="<?= base_url('dashboard/add-document.php') ?>" class="btn btn-sm btn-primary"><i class="bi-plus"></i> Add Property</a>
                            </div>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Property Id</td>
                                        <td>#<?= $item->id; ?></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>रैयत का नाम</td>
                                        <td><?= $item->raiyat_name; ?></td>
                                        <td>अभिभावक का नाम</td>
                                        <td><?= $item->guardian_name; ?></td>
                                    </tr>
                                    <tr>
                                        <td>खाता सख्या</td>
                                        <td><?= $item->khata_no; ?></td>
                                        <td>खेसरा सख्या</td>
                                        <td><?= $item->khesra_no; ?></td>
                                    </tr>
                                    <tr>
                                        <td>हल्का सख्या</td>
                                        <td><?= $item->halka_no; ?></td>
                                        <td>मौजा सख्या</td>
                                        <td><?= $item->mauja_no; ?></td>
                                    </tr>
                                    <tr>
                                        <td>जमाबंदी सख्या</td>
                                        <td><?= $item->jamabandi; ?></td>
                                        <td>रकबा / डिसमिल</td>
                                        <td><?= $item->rakba; ?></td>
                                    </tr>
                                    <tr>
                                        <td>प्लाट का पता</td>
                                        <td colspan="3"><?= $item->address; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Attachments</td>
                                        <td>
                                            <?php
                                            foreach ($files as $fn) {
                                            ?>
                                                <div class="mb-2">
                                                    <a href="<?= base_url(upload_dir($fn->file_name)) ?>" target="_blank"><?= $fn->file_name; ?></a>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                        <td colspan="2">
                                            <?php
                                            if (count($files) < 5) {
                                            ?>
                                                <form action="" enctype="multipart/form-data" method="post">
                                                    <label>Add More Files</label>
                                                    <div class="mb-2">
                                                        <input type="file" name="docs" class="form-controle" />
                                                    </div>
                                                    <input type="hidden" name="clicked" value="1">
                                                    <button class="btn btn-sm btn-primary btn-submit">Upload</button>
                                                </form>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include "../common/footer.php";
?>
<script>
    let vm = new Vue({
        el: '#origin',
        data: {
            zones: [],
            dist_id: '',
            zone_id: '',
            btncls: '',
            errmsg: '',
            errcls: '',
            gps_on: false,
            files: [1]
        },
        methods: {
            set_zones: function() {
                this.zones = [];
                api_call('zones', {
                    dist_id: this.dist_id
                }).then(resp => {
                    if (resp.success) this.zones = resp.data;
                })
            },
            addFile: function() {

                this.files.push(this.files.length + 1)
            }
        }
    });
</script>