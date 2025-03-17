<?php
include "config/autoload.php";
include_once "common/header.php";
?>
<div id="origin" class="container">
    <main class="row py-5">
        <div class="col-sm-10 m-auto">
            <?php include "common/alert.php"; ?>
            <div class="bg-white rounded shadow-sm overflow-hidden mb-3">
                <div class="bg-primary d-sm-flex justify-content-end">
                    <img src="<?= theme_url('img/login.png'); ?>" class="login-img object-fit-cover" />
                    <div class="p-3 bg-white login-form">
                        <h5>Verify Account</h5>
                        <hr />
                        <p><em>Enter the OTP Shared on your mobile.</em></p>
                        <form action="mobile-verify.php" method="POST">
                            <div class="mb-2">
                                <label>OTP</label>
                                <input name="otp" type="text" class="form-control" placeholder="XXXX" required />
                            </div>
                            <button type="submit" class="btn btn-primary">Verify</button>
                            <a href="index.php" class="btn btn-dark">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
            <div class="ath-note text-center tc-light"> OTP Not Received? <a href="resend-otp.php"> <strong>Resend</strong></a></div>
        </div>
    </main>
</div>
<?php
include_once "common/footer.php";
