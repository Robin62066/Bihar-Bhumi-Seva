<?php
include "../config/autoload.php";
if (!is_login()) redirect(base_url('login.php'), 'You must login to continue');

if (isset($_GET['id'])) {
    $act = $_GET['act'] ?? '';
    $id = $_GET['id'] ?? 0;
    if ($act == 'remove') {
        $db->delete('ai_wishlist', ['id' => $id, 'user_id' => user_id()]);
        set_flashdata('success', 'Item removed from wishlist');
    }
}

$items = $db->select('ai_wishlist', ['user_id' => user_id()])->result();
include "../common/header.php";
?>
<div class="dashboard">
    <div class="container py-5">
        <div class="user-panel">
            <div class="row">
                <?php
                $subpage = 'wishlist';
                include_once "dashboard-menu.php"; ?>
                <div class="col-sm-9">
                    <?= front_view('common/alert'); ?>
                    <div id="origin">
                        <div class="bg-white p-3 shadow-sm rounded-sm">
                            <div class="page-header">
                                <h5>Wishlist</h5>
                            </div>

                            <?php
                            foreach ($items as $item) {
                                $p = $db->select('ai_sites', ['id' => $item->pid], 1)->row();
                                if ($p == null) continue;
                            ?>
                                <div class="py-2 border-bottom">
                                    <div class="text-muted small">Property Id: #<?= $p->id; ?></div>
                                    <h6><a href="/property-view.php?id=<?= $p->id; ?>"><?= $p->site_title; ?></a></h6>
                                    <div class="text-muted">Address</div>
                                    <p><?= $p->address; ?></p>
                                    <div>
                                        <a href="/property-view.php?id=<?= $p->id; ?>" class="btn btn-xs btn-primary">View</a>
                                        <a href="<?= site_url('dashboard/wishlist.php?id=' . $item->id . '&act=remove') ?>" class="btn btn-xs btn-danger btn-confirm" data-msg="Are you sure to Delete?">Delete</a>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include "../common/footer.php";
