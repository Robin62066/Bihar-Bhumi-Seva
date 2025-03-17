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

$sql = "SELECT * FROM ai_users WHERE user_type = '$role' ORDER BY created DESC LIMIT $offset, $perPage";
if ($dist_id != null || $zone_id != null) {
    $sql = "SELECT * FROM ai_users WHERE user_type = '$role' AND dist_id ='$dist_id' AND zone_id ='$zone_id' ORDER BY created DESCLIMIT $offset, $perPage";
} else if ($dist_id > 0 && $zone_id != null) {
    $sql = "SELECT * FROM ai_users WHERE user_type = '$role' AND dist_id ='$dist_id'  ORDER BY created DESC LIMIT $offset, $perPage";
}

$items = $db->query($sql)->result();
$total = $db->query("SELECT COUNT(*) as total FROM ai_users WHERE user_type = '$role'")->row()->total;

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


foreach ($items as $item) {

?>
    <article class="post">
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
    </article>
<?php
}
?>