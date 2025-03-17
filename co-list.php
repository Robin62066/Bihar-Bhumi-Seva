<?php
include "config/autoload.php";

$dists = $db->select('ai_districts', [], false, 'dist_name ASC')->result();
$co_type = USER_CO;
$items = $db->select('ai_users', ['user_type' => $co_type, 'kyc_status' => KYC_APPROVED])->result();
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
                <h1 class="h5">All CO List - <span class="small text-info"><?= count($items); ?> Found</span></h1>
                <hr />
                <?php
                foreach ($items as $item) {
                    $dist = $db->select('ai_districts', ['id' => $item->dist_id], 1)->row();
                    $zone = $db->select('ai_zones', ['id' => $item->zone_id], 1)->row();
                ?>
                    <div class="border-bottom py-2">
                        <a href="<?= site_url('user-profile.php?id=' . $item->id) ?>">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="bg-light p-1">
                                        <?php
                                        if ($item->image != '') {
                                        ?>
                                            <img src="<?= base_url(upload_dir($item->image)) ?>" class="img-fluid" />
                                        <?php
                                        } else {
                                        ?>
                                            <img src="<?= theme_url('default.png') ?>" class="img-fluid" />
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <h6>Name: <?= $item->first_name . ' ' . $item->last_name; ?></h6>
                                    <p class="text-muted"> <i class="bi-geo-alt"></i> <?= $item->address; ?></p>
                                    <p class="text-dark"><i class="bi-person-workspace"></i>
                                        <?= is_object($dist) ? $dist->dist_name : ''; ?>,
                                        <?= is_object($zone) ? $zone->zone_name : ''; ?>
                                    </p>
                                    <i class="bi-telephone"></i> <?= $item->mobile_number; ?><br />
                                    <i class="bi-calendar3"></i> <span class="small text-muted">Member Since: <?= date('d M Y', strtotime($item->created)); ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
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
