<?php
include "config/autoload.php";

$title  = 'Labours';

$page = $_GET['page'] ?? 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

$dist = $_GET['dist'] ?? null;
$anchal  = $_GET['anchal'] ?? null;

$sql = "SELECT * FROM ai_labours ORDER BY rand() LIMIT $offset, $perPage";

$items = $db->query($sql)->result();
$total = $db->query("SELECT COUNT(*) as total FROM ai_labours")->row()->total;

//For searching the broker code
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dist = $_POST['dist'];
    $zone = $_POST['zone'];
    $searchBy = $_POST['searchData'];
    $role = $_POST['role'];
    if ($_POST['search']) {
        if (!empty($dist) && !empty($zone) && !empty($searchBy)) {
            $query =  "SELECT * FROM ai_labours WHERE dist = $dist AND anchal = $zone AND (first_name LIKE '%$searchBy%' OR email_id LIKE '%$searchBy%' OR mobile_number LIKE  '%$searchBy%') ORDER BY created ";
        } else if (empty($dist) && empty($zone) && !empty($searchBy)) {
            $query =  "SELECT * FROM ai_labours WHERE (first_name LIKE '%$searchBy%' OR email_id LIKE '%$searchBy%' OR mobile_number LIKE  '%$searchBy%')  ORDER BY created ";
        } else if (!empty($dist) && empty($zone) && empty($searchBy)) {
            $query =  "SELECT * FROM ai_labours WHERE dist = $dist  ORDER BY created ";
        } else if (!empty($dist) && !empty($zone) && empty($searchBy)) {
            $query =  "SELECT * FROM ai_labours WHERE dist = $dist AND anchal = $zone ORDER BY created ";
        }
        $items = $db->query($query)->result();
    }
}


include "common/header.php";
?>
<script src="https://unpkg.com/infinite-scroll@4.0.1/dist/infinite-scroll.pkgd.min.js"></script>
<div id="origin" class="container">
    <div class="row g-0">
        <?= front_view('common/home-menu'); ?>
        <div class="col-sm-9">
            <div class="p-3 bg-white mb-2">
                <?= front_view("common/alert"); ?>
                <div class="row">
                    <form id="SellForm2" action="" class="d-flex gap-2" method="post">
                        <div class="col-sm-3">
                            <select @change="set_zones" name="dist" v-model="dist" class="form-select">
                                <option value="">Select District</option>
                                <?php
                                foreach ($dists as $item) {
                                ?>
                                    <option value="<?= $item->id; ?>"><?= $item->dist_name; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="zone" v-model="anchal" class="form-select">
                                <option value="">Select Circle</option>
                                <option v-for="item in zones" :value="item.id">{{ item.zone_name }}</option>
                            </select>
                        </div>
                        <div class="col-sm-5 mb-2 mb-sm-0">
                            <input type="text" name="searchData" class="form-control" placeholder="Enter Name, Mobile, or Email" aria-label="Search">
                        </div>
                        <div class="col-sm-2 d-grid">
                            <input type="hidden" name="search" value="1">
                            <input type="hidden" name="role" value="<?= $user_type ?>">
                            <button type="submit" value="submit" class="btn btn-primary btn-sm w-100">Search</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="bg-white p-3 users-list">
                <h1 class="h5"><?= $title; ?> List <span class="small text-info"><?= $total; ?> Found</span></h1>
                <hr />
                <div class="property-wrapper">
                    <?php
                    foreach ($items as $item) {
                    ?>
                        <article class="post">
                            <div class="border-bottom py-2">
                                <a href="<?= site_url('user-profile.php?id=' . $item->user_id) ?>">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="p-1 photo-img">
                                                <img src="<?= base_url(upload_dir($item->photo)); ?>" onerror="this.src='<?= theme_url('default.png') ?>'" class="img-fluid w-100 rounded-2" />
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <h6>Name: <?= $item->first_name . ' ' . $item->last_name; ?></h6>
                                            <p class="text-muted"> <i class="bi-geo-alt"></i> <?= $item->address1; ?></p>
                                            <i class="bi-telephone"></i> <?= $item->mobile_number; ?><br />
                                            <i class="bi-calendar-date"></i> <small class="text-muted"><?= $item->created; ?></small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </article>
                    <?php
                    }
                    ?>
                </div>
                <!-- <div class="text-center p-2">
                    <?php
                    if ($page >= 2) {
                    ?>
                        <a href="<?= site_url('users.php?type=' . $user_type . '&page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
                    <?php
                    }
                    if ($page >= 1 && $total > $perPage) {
                    ?>
                        <a href="<?= site_url('users.php?type=' . $user_type . '&page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
                    <?php
                    }
                    ?>
                </div> -->
            </div>
        </div>
    </div>

    <?php
    include "common/footer.php";
    ?>
    <script>
        $(document).ready(function() {
            console.log("Loading...")
            $('.property-wrapper').infiniteScroll({
                // options
                path: 'ajax-labour.php?page={{#}}',
                append: '.post',
                history: false,
            });
        });
        new Vue({
            el: '#SellForm2',
            data: {
                zones: [],
                dist: '<?= $dist; ?>',
                anchal: '<?= $anchal; ?>',
            },
            methods: {
                set_zones: function() {
                    this.zones = [];
                    api_call('zones', {
                        dist: this.dist
                    }).then(resp => {
                        if (resp.success) this.zones = resp.data;
                    })
                },
            },
            created: function() {
                if (this.dist != '') {
                    this.set_zones();
                }
            }
        });
    </script>