<!DOCTYPE html>
<html lang="en">

<head>
    <title>Secure Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300&display=swap" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/bootstrap.min.css'); ?>" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/style.css'); ?>" />

    <script type="text/javascript" src="<?= base_url('assets/admin/js/jquery-3.2.1.min.js') ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.0.0/axios.min.js"></script>
    <script src="<?= base_url('assets/admin/js/vue.js'); ?>"></script>
    <script>
        var ApiUrl = '<?= base_url('api.php') ?>';
        var apiUrl = '<?= base_url('api.php') ?>';
        $(document).ready(function() {
            $('.btn-menu').click(function() {
                $('.sidebar').toggle();
            });
        });
    </script>
</head>
<?php
$menu = isset($menu) ? $menu : '';
$perm = getPermission();
?>

<body>
    <div class="main-outer">
        <div class="sidebar">
            <div class="userinfo bg-white">
                <img src="<?= theme_url('img/logo.png') ?>" class="img-fluid circle" />
                <div class="user-details text-dark">
                    Welcome <b>Admin</b> <br />
                    <small><?php echo date("jS M, h:i A"); ?></small><br />
                    <a href="<?= admin_url('index.php') ?>" class="btn btn-light btn-logout">Logout <span class="fa fa-sign-out"></span></a>
                </div>
            </div>
            <ul class="menu">
                <li><a href="<?= admin_url('dashboard.php') ?>"><i class="bi-speedometer"></i> Dashboard </a></li>
                <li class="has-submenu <?= $menu == 'members' ? 'active' : null; ?>"><a href="#"><i class="bi-people"></i> Users<span class="bi-chevron-right"></span></a></a>
                    <ul>
                        <!-- <li><a href="<?= admin_url('users/new-users.php') ?>"><span class="bi-chevron-right"></span> New Users</a></li> -->
                        <li><a href="<?= admin_url('users') ?>"><span class="bi-chevron-right"></span> All Users(web)</a></li>
                        <li><a href="<?= admin_url('users/user-from-app.php') ?>"><span class="bi-chevron-right"></span> All Users(app)</a></li>
                        <li><a href="<?= admin_url('users/?user_type=' . USER_BROKER) ?>"><span class="bi-chevron-right"></span> Land Brokers/Builders</a></li>
                        <!-- <li><a href="<?= admin_url('users/?user_type=' . USER_LAND_OWNER) ?>"><span class="bi-chevron-right"></span> Land Owners</a></li> -->
                        <li><a href="<?= admin_url('users/add-co.php') ?>"><span class="bi-chevron-right"></span> Add New CO</a></li>
                        <li><a href="<?= admin_url('users/all-co-list.php') ?>"><span class="bi-chevron-right"></span> All CO List</a></li>
                        <li><a href="<?= admin_url('users/?user_type=' . USER_MUNSI) ?>"><span class="bi-chevron-right"></span> All Munsi List</a></li>
                        <li><a href="<?= admin_url('users/?user_type=' . USER_AMIN) ?>"><span class="bi-chevron-right"></span> Amin List</a></li>

                        <li><a href="<?= admin_url('users/?user_type=' . USER_LABOUR) ?>"><span class="bi-chevron-right"></span> Labours </a></li>
                        <li><a href="<?= admin_url('users/?user_type=' . USER_BRICKS_MFG) ?>"><span class="bi-chevron-right"></span> Bricks Mfgs. </a></li>
                        <li><a href="<?= admin_url('users/?user_type=' . USER_SAND_SUPPLIER) ?>"><span class="bi-chevron-right"></span> Sand Supplier. </a></li>

                        <li><a href="<?= admin_url('users/?user_type=' . USER_BHUMI_LOCKER) ?>"><span class="bi-chevron-right"></span> Bhumi Locker List</a></li>
                        <li><a href="<?= admin_url('users/pending-accounts.php') ?>"><span class="bi-chevron-right"></span> New Pending Accounts</a></li>
                        <li><a href="<?= admin_url('users/kyc-uploads.php') ?>"><span class="bi-chevron-right"></span> Manual KYC Upload</a></li>

                        <!-- <li><a href="<?= admin_url('users/user-info.php') ?>"><span class="bi-chevron-right"></span> KYC Updated</a></li>
                        <li><a href="<?= admin_url('users/kyc-rejected.php') ?>"><span class="bi-chevron-right"></span> KYC Rejected</a></li> -->
                    </ul>
                </li>
                <li><a href="<?= admin_url('bp-list.php') ?>"><i class="bi-file-text"></i> Business Profile</a></li>
                <li><a href="<?= admin_url('labours.php') ?>"><i class="bi-file-text"></i> Labour Registration </a></li>
                <?php
                $perm->setModule(Permission::PROPERTIES);
                ?>
                <li class="has-submenu <?= $menu == 'properties' ? 'active' : null; ?>"><a href="#"><i class="bi-building"></i> Properties<span class="bi-chevron-right"></span></a></a>
                    <ul>
                        <?php
                        if ($perm->canCreateNew()) {
                        ?>
                            <li><a href="<?= admin_url('properties/add-new.php') ?>"><span class="bi-chevron-right"></span> Add New Property</a></li>
                        <?php
                        }
                        ?>
                        <li><a href="<?= admin_url('properties') ?>"><span class="bi-chevron-right"></span> All Land & Plots</a></li>
                        <li><a href="<?= admin_url('properties/featured-property.php') ?>"><span class="bi-chevron-right"></span>Featured Property</a></li>
                        <li><a href="<?= admin_url('properties/property-enquiry.php') ?>"><span class="bi-chevron-right"></span>Property Enquiry</a></li>
                    </ul>
                <li class="has-submenu <?= $menu == 'services' ? 'active' : null; ?>"><a href="#"><i class="bi-building"></i> Services<span class="bi-chevron-right"></span></a></a>
                    <ul>
                        <li><a href="<?= admin_url('services/mutations.php') ?>"><span class="bi-chevron-right"></span> Mutation Applications(Web)</a></li>
                        <li><a href="<?= admin_url('services/mutations-app.php') ?>"><span class="bi-chevron-right"></span> Mutation Applications(App)</a></li>
                        <li><a href="<?= admin_url('services/payments.php') ?>"><span class="bi-chevron-right"></span> Payments Reports</a></li>
                        <li><a href="<?= admin_url('services/bhumi-locker-properties.php') ?>"><span class="bi-chevron-right"></span> Bhumi Locker Properties</a></li>
                    </ul>
                </li>
                <?php
                $perm->setModule(Permission::MEDIA);
                if ($perm->canView()) {
                ?>
                    <li class="has-submenu <?= $menu == 'cms' ? 'active' : null; ?>"><a href="#"><i class="bi-box"></i> CMS<span class="bi-chevron-right"></span></a>
                        <ul>
                            <?php
                            ?>
                            <li><a href="<?= admin_url('media') ?>"><span class="bi-chevron-right"></span>Media Manager </a></li>
                            <li><a href="<?= admin_url('media/staff.php') ?>"><span class="bi-chevron-right"></span>Staff Manager</a></li>
                            <li><a href="<?= admin_url('media/resume-uploaded.php') ?>"><span class="bi-chevron-right"></span>Resume Uploaded</a></li>
                            <li><a href="<?= admin_url('media/notifications.php') ?>"><span class="bi-chevron-right"></span> Notifications</a></li>

                        </ul>
                    </li>
                <?php
                }
                ?>
                <li class="has-submenu <?= $menu == 'supports' ? 'active' : null; ?>"><a href="#"><i class="bi-info-square"></i> Supports & Guide<span class="bi-chevron-right"></span></a>
                    <ul>
                        <li><a href="<?= admin_url('supports/index.php?status=1') ?>"><span class="bi-chevron-right"></span>Open Complain </a></li>
                        <li><a href="<?= admin_url('supports') ?>"><span class="bi-chevron-right"></span>Complain History</a></li>

                    </ul>
                </li>
                <li class="has-submenu <?= $menu == 'reports' ? 'active' : null; ?>"><a href="#"><i class="bi-info-square"></i> Reports<span class="bi-chevron-right"></span></a>
                    <ul>
                        <li><a href="<?= admin_url('reports/index.php') ?>"><span class="bi-chevron-right"></span>Signup Reports</a></li>
                        <li><a href="<?= admin_url('reports/payments.php') ?>"><span class="bi-chevron-right"></span>Payment Report</a></li>
                        <li><a href="<?= admin_url('reports/leads.php') ?>"><span class="bi-chevron-right"></span>Leads Report</a></li>

                    </ul>
                </li>

                <li class="has-submenu <?= $menu == 'settings' ? 'active' : null; ?>"><a href="#"><i class="bi-gear"></i> Settings<span class="bi-chevron-right"></span></a>
                    <ul>
                        <?php
                        $perm->setModule(Permission::SETTINGS);
                        if (admin_id() == 1) {
                        ?>
                            <li><a href="<?= admin_url('settings/users.php') ?>"><span class="bi-chevron-right"></span> Manage Admin</a></li>
                            <li><a href="<?= admin_url('settings') ?>"><span class="bi-chevron-right"></span> General Settings</a></li>
                        <?php
                        }
                        if ($perm->canView()) {
                        ?>
                            <li><a href="<?= admin_url('states/districts.php') ?>"><span class="bi-chevron-right"></span> Manage Districts</a></li>
                            <li><a href="<?= admin_url('states/dzones.php') ?>"><span class="bi-chevron-right"></span> Manage Zones</a></li>
                        <?php
                        }
                        ?>
                        <li><a href="<?= admin_url('settings/edit-profile.php') ?>"><span class="bi-chevron-right"></span> Edit Profile</a></li>
                        <li><a href="<?= admin_url('settings/change-password.php') ?>"><span class="bi-chevron-right"></span> Change Password </a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="main">
            <div class="topbar bg-primary">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col col-sm-4">
                            <button class="btn btn-menu btn-outline-light">
                                <i class="fa fa-navicon"></i>
                                Dashboard
                            </button>
                        </div>

                        <div class="col col-sm-8">
                            <ul class="qmenu">
                                <li><a class="text-white" target="_blank" href="<?= base_url() ?>"><i class="fa fa-desktop"></i> Company </a></li>
                                <li><a class="text-white" href="<?= admin_url('index.php') ?>"><i class="fa fa-sign-out"></i> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main-content">
                <?php include ROOT_PATH . "common/alert.php" ?>