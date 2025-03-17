<?php
include "./../config/autoload.php";
if (!is_login()) redirect(base_url('login.php'), 'You must login to continue');

include "../common/header.php";
?>
<div class="container py-5">
    <div class="row">
        <div class="col-sm-6 m-auto text-center">
            <div class="bg-white p-2 shadow-sm  mb-3">
                <video src="../assets/video.mp4" autoplay loop width="400" height="300" class="w-100" />
            </div>
        </div>
    </div>
    <div class="text-center">
        <a href="subscribe-bhumi-locker.php" class="btn btn-primary">Continue to Pay</a>
    </div>
</div>
<?php
include "../common/footer.php";
