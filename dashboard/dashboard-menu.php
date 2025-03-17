<?php
$user = userdata('user');
$name = $user->first_name . ' ' . $user->last_name;
$subpage = isset($subpage) ? $subpage : 'index';
$image = $user->image == '' ? theme_url('img/broker.jpg') : base_url(upload_dir($user->image));
$link = site_url('user-profile.php?id=' . $user->id);
?>
<div class="col-sm-3">

    <div class="user-menu-photo">

        <a href="<?= $link; ?>">
            <div class="bg-white rounded-sm p-2 mb-2 shadow-sm d-flex gap-2 align-items-center">
                <img src="<?= $image ?>" width="40" />
                <div class="m-0"><small>Welcome</small><br /> <span class="text-info"><?= $name; ?></span></div>
                <?php
                if ($user->kyc_status == 1) {
                ?>

                    <div class="bg-success w-4 h-5 p-1 m-auto">
                        <small class="text-white">Verified</small>
                    </div>

                <?php } ?>
                <div>
                </div>


            </div>
        </a>
    </div>
    <div class="d-block d-sm-none">
        <div class="p-2 bg-dark mb-2 text-white d-flex gap-2 align-items-center">
            <button id="btnmenu" class="btn btn-sm btn-light"><i class="bi-list"></i> </button>
            <div>MENU</div>
        </div>
    </div>
    <div class="user-menu bg-white rounded-sm shadow-sm">
        <ul>
            <li class="<?= $subpage == 'index' ? 'active' : ''; ?>"><a href="<?= base_url('dashboard') ?>"> <i class="bi-tv"></i> <span>Dashboard</span> <i class="bi-chevron-right"></i></a> </li>
            <li class="<?= $subpage == 'leads' ? 'active' : ''; ?>"><a href="<?= base_url('dashboard/leads.php') ?>"> <i class="bi-file-earmark-text"></i> <span>Leads/Enquiries</span> <i class="bi-chevron-right"></i></a></li>
            <li class="<?= $subpage == 'properties' ? 'active' : ''; ?>"><a href="<?= base_url('dashboard/properties.php') ?>"> <i class="bi-box"></i> <span>My Ads</span> <i class="bi-chevron-right"></i></a></li>
            <li class="<?= $subpage == 'edit-profile' ? 'active' : ''; ?>"><a href="<?= base_url('dashboard/edit-profile.php') ?>"> <i class="bi-person"></i> <span>My Profile</span> <i class="bi-chevron-right"></i></a></li>
            <li class="<?= $subpage == 'business' ? 'active' : ''; ?>">
                <?php
                $isBusinessProfile = false;
                $ab = $db->select('ai_profiles', ['user_id' => $user->id], 1)->row();
                if (is_object($ab)) {
                    $isBusinessProfile = true;
                ?>
                    <a href="<?= site_url('dashboard/profile.php'); ?>"><i class="bi-buildings"></i> <span>Business Profile</span> <i class="bi-chevron-right"></i></a>
                <?php
                } else {
                ?>
                    <a href="<?= site_url('dashboard/create-profile.php'); ?>"><i class="bi-buildings"></i> <span> Add Business Profile</span> <i class="bi-chevron-right"></i></a>
                <?php
                }
                ?>
            </li>
            <li class="<?= $subpage == 'services' ? 'active' : ''; ?>"><a href="<?= base_url('dashboard/services.php') ?>"> <i class="bi-box"></i> <span>Service Catalog</span> <i class="bi-chevron-right"></i></a></li>
            <li class="<?= $subpage == 'mutations' ? 'active' : ''; ?>"><a href="<?= base_url('dashboard/mutations.php') ?>"> <i class="bi-heart"></i> <span>Mutation Applications</span> <i class="bi-chevron-right"></i></a></li>
            <li class="<?= $subpage == 'bhumi' ? 'active' : ''; ?>"><a href="<?= base_url('dashboard/bhumi-locker.php') ?>"> <i class="bi-lock"></i> <span>Bhumi Locker</span> <i class="bi-chevron-right"></i></a></li>
            <li class="<?= $subpage == 'membership' ? 'active' : ''; ?>"><a href="<?= base_url('dashboard/membership.php') ?>"> <i class="bi-lock"></i> <span>Membership</span> <i class="bi-chevron-right"></i></a></li>
            <?php
            if (!$isBusinessProfile) {
            ?>
                <li class="<?= $subpage == 'labour' ? 'active' : ''; ?>"><a href="<?= base_url('dashboard/labour-registration.php') ?>"> <i class="bi-person"></i> <span>Labour Registration</span> <i class="bi-chevron-right"></i></a></li>
            <?php
            }
            ?>
            <li class="<?= $subpage == 'wishlist' ? 'active' : ''; ?>"><a href="<?= base_url('dashboard/wishlist.php') ?>"> <i class="bi-bookmark"></i> <span>Wishlist</span> <i class="bi-chevron-right"></i></a></li>
            <li class="<?= $subpage == 'notifications' ? 'active' : ''; ?>"><a href="<?= base_url('dashboard/notifications.php') ?>"> <i class="bi-bell"></i> <span>Notifications</span> <i class="bi-chevron-right"></i></a></li>
            <li class="<?= $subpage == 'change-password' ? 'active' : ''; ?>"><a href="<?= base_url('dashboard/change-password.php') ?>"> <i class="bi-lock"></i> <span>Change Password</span> <i class="bi-chevron-right"></i></a></li>
            <li><a href="<?= base_url('logout.php') ?>"> <i class="bi-box-arrow-right"></i> <span>Logout</span> <i class="bi-chevron-right"></i></a></li>
        </ul>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#btnmenu').click(function() {
            $('.user-menu').slideToggle('slow');
        })
    })
</script>