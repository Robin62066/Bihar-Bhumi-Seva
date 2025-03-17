<?php
include "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$perm = getPermission(Permission::BHUMI_LOCKER);
if (!$perm->canView()) {
    session()->set_flashdata('danger', 'Your do not have access permissions');
    redirect(admin_url('dashboard.php'));
}
$id = $_GET['id'] ?? null;
if ($id == null) {
    redirect(admin_url('services/bhumi-locker-properties.php'), 'Details not found', 'error');
}

$p = $db->select('ai_bhumifiles', ['id' => $id], 1)->row();
if ($p == null) {
    redirect(admin_url('services/bhumi-locker-properties.php'), 'Details not found', 'error');
}

if (isset($_GET['del'])) {
    $db->delete('ai_bhumi_docs', ['id' => $_GET['del']]);
}

$dists = $db->select('ai_districts', [], false, 'dist_name ASC')->result();
if (isset($_POST['clicked'])) {
    $sb = $_POST['form'];
    $sb['updated'] = date("Y-m-d H:i:s");
    $db->update("ai_bhumifiles", $sb, ['id' => $id]);

    // Add Docuemnts to ai_bhumi_docs
    foreach ($_FILES["docs"]["tmp_name"] as $key => $tmp_name) {
        $file_name = $_FILES["docs"]["name"][$key];
        if ($file_name == '') continue;

        $file_tmp = $_FILES["docs"]["tmp_name"][$key];
        $extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $sv_name = time() . '_' . bin2hex(random_bytes(10)) . '.' . $extension;
        $target_path =  ROOT_PATH . upload_dir($sv_name);
        if (move_uploaded_file($file_tmp, $target_path)) {
            $sb = [];
            $sb['prop_id'] = $id;
            $sb['file_name'] = $sv_name;
            $sb['created'] = date("Y-m-d");
            $sb['downloads'] = 0;
            $db->insert("ai_bhumi_docs", $sb);
        }
    }
    session()->set_flashdata('success', "File added to your Locker");
    redirect(admin_url('services/edit-bhumifiles.php?id=' . $id));
}
$menu = 'services';
include "../common/header.php";
?>
<div id="origin">
    <div class="page-header">
        <h5>Bhumi Locker Details</h5>
    </div>
    <div class="row">
        <div class="col-sm-8">
            <div class="card p-4">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label>District</label>
                            <select @change="set_zones" name="form[dist_id]" v-model="dist_id" class="form-select">
                                <option value="">Select</option>
                                <?php
                                foreach ($dists as $item) {
                                ?>
                                    <option value="<?= $item->id; ?>"><?= $item->dist_name; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label>Circle</label>
                            <select v-model="zone_id" name="form[zone_id]" class="form-select">
                                <option value="">Select</option>
                                <option v-for="item in zones" :value="item.id">{{ item.zone_name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label>रैयत का नाम</label>
                            <input type="text" name="form[raiyat_name]" value="<?= $p->raiyat_name; ?>" required class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label>अभिभावक का नाम </label>
                            <input type="text" name="form[guardian_name]" value="<?= $p->guardian_name; ?>" required class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label>खाता सख्या</label>
                            <input type="text" name="form[khata_no]" value="<?= $p->guardian_name; ?>" required class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label>खेसरा सख्या</label>
                            <input type="text" name="form[khesra_no]" value="<?= $p->khesra_no; ?>" required class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label>हल्का सख्या</label>
                            <input type="text" name="form[halka_no]" value="<?= $p->halka_no; ?>" required class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label>मौजा सख्या</label>
                            <input type="text" name="form[mauja_no]" value="<?= $p->mauja_no; ?>" required class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label>जमाबंदी सख्या</label>
                            <input type="text" name="form[jamabandi]" value="<?= $p->jamabandi; ?>" required class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label>रकबा / डिसमिल</label>
                            <input type="text" name="form[rakba]" value="<?= $p->rakba; ?>" required class="form-control">
                        </div>
                    </div>
                    <div class="py-2 mb-3">
                        नीचे दी गयी सारी जानकारी की स्कैन कॉपी एक ही PDF में जोड़े एंड अपलोड करें , PDF फाइल की साइज 2MB से काम होनी चाहिए।
                    </div>
                    <div class="bg-light border p-3 rounded-1 mb-3">
                        <?php
                        $arrFiles = $db->select('ai_bhumi_docs', ['prop_id' => $p->id])->result();
                        foreach ($arrFiles as $file) {
                            if ($file->file_name  == '') continue;
                        ?>
                            <div class="py-2 border-bottom mb-2 d-flex justify-content-between align-items-center">
                                <a href="<?= base_url(upload_dir($file->file_name)) ?>"><?= $file->file_name; ?></a>
                                <a href="<?= admin_url('services/edit-bhumifiles.php?id=' . $p->id . '&del=' . $file->id) ?>" class="btn btn-xs btn-danger btn-confirm" data-msg="Are you sure to delete?">Delete</a>
                            </div>
                        <?php
                        }
                        ?>
                        <div v-for="file in files" class="mb-2">
                            <input type="file" name="docs[]" class="form-cotrol">
                        </div>
                        <button :disabled="files.length>=5" type="button" @click="addFile" class="btn btn-sm btn-primary">+ Add More Files</button>
                    </div>
                    <div class="mb-3">
                        <label>प्लाट का पता जोड़े। </label>
                        <input type="text" name="form[address]" value="<?= $p->address; ?>" required class="form-control">
                    </div>
                    <div>
                        <input type="hidden" name="clicked" value="1">
                        <button class="btn btn-primary btn-submit">UPDATE</button>
                        <a href="<?= base_url('dashboard/bhumi-locker.php') ?>" class="btn btn-secondary">CANCEL</a>
                    </div>
                </form>
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
            dist_id: '<?= $p->dist_id; ?>',
            zone_id: '<?= $p->zone_id; ?>',
            btncls: '',
            errmsg: '',
            errcls: '',
            gps_on: false,
            files: [1]
        },
        methods: {
            set_zones: function() {
                console.log('hi');
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
        },
        created: function() {
            this.set_zones();
        }
    });
</script>