<?php
include "config/autoload.php";

$dists = $db->select('ai_districts', [], false, 'dist_name ASC')->result();
$co_type = USER_BROKER;
$page = $_GET['page'] ?? 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

$sql = "SELECT * FROM ai_users WHERE user_type = '$co_type' AND kyc_status = " . KYC_APPROVED . " ORDER BY rand() LIMIT $offset, $perPage";
$items = $db->query($sql)->result();

$total = $db->query("SELECT COUNT(*) as total FROM ai_users WHERE user_type = '$co_type' AND kyc_status = " . KYC_APPROVED)->row()->total;
include "common/header.php";
?>
<div id="origin" class="container">
    <div class="row g-0">
        <?= front_view('common/home-menu'); ?>
        <div class="col-sm-9">
            <div class="p-3 bg-white mb-2">
                <?= front_view("common/alert"); ?>
                <form action="" method="get">
                    <div class="search-area">
                        <div class="row">
                            <div class="col-sm-5">
                                <select name="dist_id" @change="set_zones" v-model="dist_id" class="form-select">
                                    <option value="">जिला</option>
                                    <?php
                                    foreach ($dists as $item) {
                                    ?>
                                        <option value="<?= $item->id; ?>"><?= $item->dist_name; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-5">
                                <select name="zone_id" v-model="zone_id" class="form-select">
                                    <option value="">अंचल</option>
                                    <option v-for="item in zones" :value="item.id">{{ item.zone_name }}</option>
                                </select>
                            </div>
                            <div class="col-sm-2 d-grid">
                                <button name="button" value="Search" class="btn btn-primary btn-sm">Filter</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="bg-white p-3 users-list">
                <h1 class="h5">All Land Brokers List - <span class="small text-info"><?= $total; ?> Found</span></h1>
                <hr />
                <?php
                foreach ($items as $item) {
                ?>
                    <div class="border-bottom py-2">
                        <a href="<?= site_url('user-profile.php?id=' . $item->id) ?>">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="p-1 photo-img">
                                        <!-- <img src="image-resize.php?file=<?= upload_dir($item->image); ?>" onerror="this.src='<?= theme_url('default.png') ?>'" class="img-fluid" /> -->
                                        <img src="<?= base_url(upload_dir($item->image)); ?>" onerror="this.src='<?= theme_url('default.png') ?>'" class="img-fluid w-100 rounded-2" />
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <h6>Name: <?= $item->first_name . ' ' . $item->last_name; ?></h6>
                                    <p class="text-muted"> <i class="bi-geo-alt"></i> <?= $item->address; ?></p>
                                    <i class="bi-telephone"></i> <?= $item->mobile_number; ?><br />
                                    <i class="bi-calendar-date"></i> <small class="text-muted"><?= $item->created; ?></small>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="text-center p-2">
                <?php
                if ($page >= 2) {
                ?>
                    <a href="<?= site_url('brokers.php?page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
                <?php
                }
                if ($page >= 1) {
                ?>
                    <a href="<?= site_url('brokers.php?page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<script>
    let vm = new Vue({
        el: '#origin',
        data: {
            zones: [],
            dist_id: '',
            zone_id: '',
            btncls: '',
            errmsg: '',
            errcls: ''
        },
        methods: {
            set_zones: function() {
                this.zones = [];
                api_call('zones', {
                    dist_id: this.dist_id
                }).then(resp => {
                    if (resp.success) this.zones = resp.data;
                })
            }
        }
    });
</script>
<?php
include "common/footer.php";
