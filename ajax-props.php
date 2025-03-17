<?php
include "config/autoload.php";
$page = $_GET['page'] ?? 1;
$limit = 5;
$offset = ($page - 1) * $limit;
$sql = "SELECT * FROM ai_sites WHERE (status = 1 OR status = 3) ORDER BY created DESC LIMIT $limit OFFSET $offset";
$items = $db->query($sql)->result();

// $sql = "SELECT * FROM ai_sites WHERE (status = 1 OR status = 3) AND isPromoted = 0 ORDER BY created DESC LIMIT $limit OFFSET $offset";
// $sql1 = "SELECT * FROM ai_sites WHERE (status = 1 OR status = 3) AND isPromoted = 1 ORDER BY created DESC LIMIT $limit OFFSET $offset";
// $Props = $db->query($sql)->result();
// $property = $db->query($sql1)->result();
// $items = [];
// while (!empty($property) && !empty($Props)) {
//     if (!empty($property)) {

//         $items[] = array_shift($property);

//         $items = array_merge($items, array_splice($Props, 0, 4));
//     } else {

//         $items = array_merge($items, $Props);
//     }
// }
foreach ($items as $item) {
    $user = $db->select('ai_users', ['id' => $item->user_id], 1)->row();
    $isWishlisted = false;
    if (is_login()) {
        $isWishlisted = $db->select("ai_wishlist", ['pid' => $item->id, 'user_id' => user_id()], 1)->row();
    }

?>
    <article class="post">
        <?php
        $cls = "";
        if ($item->status == 3) {
            $cls = "border border-danger rounded p-3  bg-danger-subtle";
        } else if ($item->isPromoted == 1 || $item->status == 3) {
            $cls = "border border-2 rounded p-3  bg-white";
        } ?>
        <div class="row <?= $cls; ?>">
            <div class="col-12 col-sm-3">
                <div class="property-list-thumbnail">
                    <?php if ($item->status == 3) { ?>
                        <a href="#">
                        <?php } else { ?>
                            <a href="<?= base_url('property-view.php?id=' . $item->id); ?>">
                            <?php } ?>
                            <img src="<?= base_url(upload_dir($item->photo_front)) ?>"
                                alt="" class="img-fluid w-100">
                            </a>
                </div>
            </div>
            <div class="col-12 col-sm-9">
                <div class="p-2 d-flex justify-content-between flex-column h-100">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h1 class="h5 mb-0 site-title ">
                                <a href="<?= base_url('property-view.php?id=' . $item->id); ?>">
                                    <?= $item->site_title; ?>
                                </a>
                            </h1>
                            <div class="text-muted mb-2"> <i class="bi-home"></i> <?= $item->address; ?></div>
                        </div>
                        <div class="text-end">
                            <div class="mb-3">
                                <?php
                                if ($isWishlisted) {
                                ?>
                                    <button @click="removeWishlist('<?= $item->id ?>')" class="btn btn-sm"><i class="bi-bookmark-fill text-success"></i></button>
                                <?php
                                } else {
                                ?>
                                    <button @click="addWishlist('<?= $item->id ?>')" class="btn btn-sm"><i class="bi-bookmark"></i></button>
                                <?php
                                }
                                ?>

                            </div>
                            <div class="mb-1">
                                <!-- <button @click="sendSMS('<?= $item->id ?>')" class="btn btn-sm btn-outline-dark">Send SMS</button> -->
                                <button @click="pid=<?= $item->id ?>" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-sm btn-outline-dark">Send Msg</button>
                            </div>
                            <?php
                            if ($user->whatsapp != '') {
                            ?>
                                <a href="https://api.whatsapp.com/send?phone=91<?= $user->whatsapp; ?>" class="btn btn-sm btn-success">WhatsApp</a>
                            <?php
                            }
                            ?>
                            <?php
                            if ($item->isPromoted == 1) { ?>
                                <p style="color: gray; " class="mt-1 mb-0 text-center">Sponsored</p>
                            <?php }
                            ?>
                            <?php
                            if ($item->status == 3) { ?>
                                <img src="image/giphy.gif" alt="" height="80" width="80">
                            <?php }
                            ?>

                        </div>
                    </div>
                    <div class="property-info">
                        <div> <span class="text-danger"> <i class="bi-geo-alt"></i> </span> <?= $item->address; ?></div>
                        <div class="my-1"> <span class="text-danger"> <i class="bi-currency-rupee"></i> </span> <?= $item->total_amount; ?> - <?= $item->total_area . ' ' . $item->area_unit; ?></div>
                        <div> <span class="text-danger"> <i class="bi-telephone"></i> </span>
                            <a href="tel:<?= $user->mobile_number; ?>" class="text-danger"><small><?= $user->mobile_number; ?></small></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>
<?php
}
?>