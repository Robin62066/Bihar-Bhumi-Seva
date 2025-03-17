<?php
require('../config/autoload.php');
if (!is_login()) redirect(base_url('login.php'), 'You must login to continue');
$user_id = user_id();
// $item = $db->select('ai_membership', ['user_id' => $user_id, 'plan_id' => 3, 'status' => 2], 1)->row();
$item = $db->select('ai_users', ['id' => $user_id], 1)->row();
// if ($item->pay_status != 1) {
//     redirect('bhumi-locker-pricing.php', "Subscribe Bhumi Locker to use.");
// }

$user = $db->select('ai_users', ['id' => $user_id], 1)->row();
if ($user->kyc_status == 0) {
    if ($user->pan_verified == 0) {
        redirect(site_url('pan-verification.php'), "You must Verify your KYC.", "danger");
    } else if ($user->aadhar_verified == 0) {
        redirect(site_url('aadhar-verification.php'), "You must Verify your Aadhar Number.", "danger");
    }
}

$dists = $db->select('ai_districts', [], false, 'dist_name ASC')->result();

if (isset($_POST['clicked'])) {
    $sb = $_POST['form'];
    $sb['user_id'] = user_id();
    $sb['created'] = date("Y-m-d H:i:s");
    $sb['updated'] = date("Y-m-d H:i:s");
    $sb['is_deleted'] = 0;
    $db->insert("ai_bhumifiles", $sb);
    $id  = $db->id();

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
    redirect(base_url('dashboard/bhumi-locker.php'));
}

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
                        <div class="bg-white p-3 mb-3 shadow-sm">
                            जिस नाम पर KYC सत्यापित किया गया है वह सम्पति के मालिक के नाम से मेल खाना चाहिए। जमीन पर ताला लगाकर सम्पति बनाई जाती है और यदि नाम मेल नहीं खता है तो आपका निरस्त एंड अस्वीकृत कर दिया जायेगा।
                        </div>

                        <div class="bg-white shadow-sm p-4 rounded-sm">
                            <div class="page-header">
                                <h5>Property Details</h5>
                                <a href="<?= base_url('dashboard/add-document.php') ?>" class="btn btn-sm btn-primary"><i class="bi-plus"></i> Add Property</a>
                            </div>
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row mb-3">
                                    <div class="col-sm-4">
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
                                    <div class="col-sm-4">
                                        <label>Circle</label>
                                        <select v-model="zone_id" name="form[zone_id]" class="form-select">
                                            <option value="">Select</option>
                                            <option v-for="item in zones" :value="item.id">{{ item.zone_name }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <label>रैयत का नाम</label>
                                        <input type="text" name="form[raiyat_name]" required class="form-control">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>अभिभावक का नाम </label>
                                        <input type="text" name="form[guardian_name]" required class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <label>खाता सख्या</label>
                                        <input type="text" name="form[khata_no]" required class="form-control">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>खेसरा सख्या</label>
                                        <input type="text" name="form[khesra_no]" required class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <label>हल्का सख्या</label>
                                        <input type="text" name="form[halka_no]" required class="form-control">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>मौजा सख्या</label>
                                        <input type="text" name="form[mauja_no]" required class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <label>जमाबंदी सख्या</label>
                                        <input type="text" name="form[jamabandi]" required class="form-control">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>रकबा / डिसमिल</label>
                                        <input type="text" name="form[rakba]" required class="form-control">
                                    </div>
                                </div>
                                <div class="py-2 mb-3">
                                    नीचे दी गयी सारी जानकारी की स्कैन कॉपी एक ही PDF में जोड़े एंड अपलोड करें , PDF फाइल की साइज 2MB से काम होनी चाहिए।
                                </div>
                                <div class="bg-light border p-3 rounded-1 mb-3">
                                    <div v-for="file in files" class="mb-2">
                                        <input type="file" name="docs[]" class="form-cotrol">
                                    </div>
                                    <button :disabled="files.length>=5" type="button" @click="addFile" class="btn btn-sm btn-primary">+ Add More Files</button>
                                </div>
                                <div class="mb-3">
                                    <label>प्लाट का पता जोड़े। </label>
                                    <input type="text" name="form[address]" required class="form-control">
                                </div>
                                <div>
                                    <input type="hidden" name="clicked" value="1">
                                    <button class="btn btn-primary btn-submit">SUBMIT</button>
                                    <a href="<?= base_url('dashboard/bhumi-locker.php') ?>" class="btn btn-secondary">CANCEL</a>
                                </div>
                            </form>
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