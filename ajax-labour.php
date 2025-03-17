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