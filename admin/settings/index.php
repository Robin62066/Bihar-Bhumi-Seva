<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$menu = 'settings';
include '../common/header.php';
?>
<div class="page-header">
    <h5>General Settings</h5>
</div>
<div class="card p-4">

</div>
<?php
include "../common/footer.php";
