<?php
include "config/autoload.php";

$user_type = $_GET['type'] ?? '';
$role   = '';
$title  = '';
if ($user_type == 'building-contructor') {
    $role = USER_BUILDER_CONSTRUCTON;
    $title = "Builders";
} else if ($user_type == 'bricks-mfgs') {
    $role = USER_BRICKS_MFG;
    $title = "Bricks Manufacturer";
} else if ($user_type == 'sand-suppliers') {
    $role = USER_SAND_SUPPLIER;
    $title = "Sand Supplier";
} else if ($user_type == 'co') {
    $role = USER_CO;
    $title = "CO";
} else if ($user_type == 'amin') {
    $role = USER_AMIN;
    $title = "Amin";
} else if ($user_type == 'munsi') {
    $role = USER_MUNSI;
    $title = "Munsi";
} else if ($user_type == 'brokers') {
    $role = USER_BROKER;
    $title = "Brokers";
} else {
    redirect(site_url());
}

$page = $_GET['page'] ?? 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

$dist_id = $_GET['dist'] ?? null;
$zone_id  = $_GET['anchal'] ?? null;

$sql1 = "SELECT * FROM ai_users WHERE user_type = '$role' AND isPromoted = 0 ORDER BY created DESC LIMIT $offset, $perPage";
if ($dist_id != null || $zone_id != null) {
    $sql1 = "SELECT * FROM ai_users WHERE user_type = '$role' AND dist_id ='$dist_id' AND zone_id ='$zone_id' AND isPromoted = 0 ORDER BY created DESCLIMIT $offset, $perPage";
} else if ($dist_id > 0 && $zone_id != null) {
    $sql1 = "SELECT * FROM ai_users WHERE user_type = '$role' AND dist_id ='$dist_id' AND isPromoted = 0 ORDER BY created DESC LIMIT $offset, $perPage";
}

$sql = "SELECT * FROM ai_users WHERE user_type = '$role' AND isPromoted = 1 ORDER BY created DESC LIMIT $offset, $perPage";
if ($dist_id != null || $zone_id != null) {
    $sql = "SELECT * FROM ai_users WHERE user_type = '$role' AND dist_id ='$dist_id' AND zone_id ='$zone_id' AND isPromoted = 1 ORDER BY created DESCLIMIT $offset, $perPage";
} else if ($dist_id > 0 && $zone_id != null) {
    $sql = "SELECT * FROM ai_users WHERE user_type = '$role' AND dist_id ='$dist_id' AND isPromoted = 1  ORDER BY created DESC LIMIT $offset, $perPage";
}



$users = $db->query($sql1)->result();
$sponsorUsers = $db->query($sql)->result();
$total = $db->query("SELECT COUNT(*) as total FROM ai_users WHERE user_type = '$role'")->row()->total;

$items = [];

// If there are no sponsors, add all users to $items
if (empty($sponsorUsers)) {
    $items = $users;
} else {
    while (!empty($sponsorUsers) && !empty($users)) {
        if (!empty($sponsorUsers)) {
            $items[] = array_shift($sponsorUsers);
            $items = array_merge($items, array_splice($users, 0, 4));
        }
    }

    if (!empty($sponsorUsers)) {
        $items = array_merge($items, $sponsorUsers);
    }
    // If there are remaining users, add them to $items
    if (!empty($users)) {
        $items = array_merge($items, $users);
    }
}
// echo "<pre>";
// print_r($items);
// echo "</pre>";
// die;
function roles($role)
{
    if ($role == "brokers") {
        return 3;
    } else if ($role == "munsi") {
        return 4;
    } else if ($role == "amin") {
        return 5;
    } else if ($role == "co") {
        return 6;
    } else if ($role == "building-contructor") {
        return 12;
    } else if ($role == "bricks-mfgs") {
        return 10;
    } else if ($role == "sand-suppliers") {
        return 11;
    } else {
        return 0;
    }
}

//For searching the broker code
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dist = $_POST['dist'];
    $zone = $_POST['zone'];
    $searchBy = $_POST['searchData'];
    $role = roles($_POST['role']);
    if ($_POST['search']) {
        if (!empty($dist) && !empty($zone) && !empty($searchBy)) {
            $query =  "SELECT * FROM ai_users WHERE dist_id = $dist AND zone_id = $zone AND user_type ='$role' AND  (first_name LIKE '%$searchBy%' OR email_id LIKE '%$searchBy%' OR mobile_number LIKE  '%$searchBy%') ORDER BY created DESC LIMIT $offset, $perPage";
        } else if (empty($dist) && empty($zone) && !empty($searchBy)) {
            $query =  "SELECT * FROM ai_users WHERE user_type ='$role' AND  (first_name LIKE '%$searchBy%' OR email_id LIKE '%$searchBy%' OR mobile_number LIKE  '%$searchBy%') ORDER BY created DESC LIMIT $offset, $perPage";
        } else if (!empty($dist) && empty($zone) && empty($searchBy)) {
            $query =  "SELECT * FROM ai_users WHERE dist_id = $dist AND user_type ='$role' ORDER BY created DESC LIMIT $offset, $perPage";
        } else if (!empty($dist) && !empty($zone) && empty($searchBy)) {
            $query =  "SELECT * FROM ai_users WHERE dist_id = $dist AND zone_id = $zone AND user_type ='$role'  ORDER BY created DESC LIMIT $offset, $perPage";
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
                            <select @change="set_zones" name="dist" v-model="dist_id" class="form-select">
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
                            <select name="zone" v-model="zone_id" class="form-select">
                                <option value="">Select Circle</option>
                                <option v-for="item in zones" :value="item.id">{{ item.zone_name }}</option>
                            </select>
                        </div>
                        <div class="col-sm- mb-2 mb-sm-0">
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
                            <?php
                            $cls = "";
                            if ($item->isPromoted == 1) {
                                $cls = "border border-2 rounded p-3  bg-white";
                            } ?>
                            <div class="border-bottom py-2 <?= $cls; ?>">
                                <a href="<?= site_url('user-profile.php?id=' . $item->id) ?>">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="p-1 photo-img">
                                                <!-- <img src="image-resize.php?file=<?= upload_dir($item->image); ?>" onerror="this.src='<?= theme_url('default.png') ?>'" class="img-fluid" /> -->
                                                <img src="<?= base_url(upload_dir($item->image)); ?>" onerror="this.src='<?= theme_url('default.png') ?>'" class="img-fluid w-100 rounded-2" />
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <?php
                                            if ($item->isPromoted == 1) { ?>
                                                <p style="color: gray; " class="mb-0">Sponsored</p>
                                            <?php }
                                            ?>
                                            <h6>Name: <?= $item->first_name . ' ' . $item->last_name; ?></h6>
                                            <p class="text-muted"> <i class="bi-geo-alt"></i> <?= $item->address; ?></p>
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
                if ($page >= 1) {
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
                path: "ajax-users.php?page={{#}}&type=<?= urlencode($user_type) ?>",
                append: '.post',
                history: false,
            });
        });
        new Vue({
            el: '#SellForm2',
            data: {
                zones: [],
                dist_id: '<?= $dist_id; ?>',
                zone_id: '<?= $zone_id; ?>',
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
            },
            created: function() {
                if (this.dist_id != '') {
                    this.set_zones();
                }
            }
        });
    </script>