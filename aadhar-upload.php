<?php
include "config/autoload.php";
if (!is_login()) redirect(base_url('login.php'), 'Please Login to continue', 'danger');

if (isset($_POST['btn'])) {
    $sb = $_POST['form'];
    $sb['user_id'] = user_id();
    $sb['created'] = date("Y-m-d H:i:s");
    $sb['status'] = 0;
    $sb['image'] = do_upload('aadhar1');
    $sb['image_back'] = do_upload('aadhar2');
    $db->insert('ai_kyc_upload', $sb);

    redirect(base_url('dashboard/index.php'), 'Your Aadhar KYC has been uploaded. Wait for approval.', 'success');
}
include "common/header.php";
?>
<div class="login-wrapper py-5">
    <div id="origin" class="container">
        <div class="row">
            <div class="col-sm-10 m-auto">
                <ul class="progressbar">
                    <li>Mobile Verification</li>
                    <li>PAN Verification</li>
                    <li class="active">Aadhar Upload</li>
                    <li>Photo Upload</li>
                </ul>
                <div class="bg-white rounded shadow-sm overflow-hidden mb-3 p-3">
                    <div class="row">
                        <h6 class="text-center"><b>Aadhar Upload</b></h6>
                        <div class="col-sm-6">
                            <hr />
                            <form method="post" enctype="multipart/form-data">
                                <div class="mb-2">
                                    <label>Aadhar Number</label>
                                    <input type="text" name="form[aadhar_no]" required class="form-control" />
                                </div>
                                <div class="mb-2">
                                    <label>Your name (As per Aadhar Card)</label>
                                    <input type="text" name="form[aadhar_name]" required class="form-control" />
                                </div>
                                <div class="mb-2">
                                    <label>Aadhar Photo (Front)</label>
                                    <input type="file" name="aadhar1" required accept="image/*" class="form-control mb-1" />
                                    <div class="small text-danger">Supported Format: JPG/JPEG/PNG Only</div>
                                </div>
                                <div class="mb-2">
                                    <label>Aadhar Photo (Back)</label>
                                    <input type="file" name="aadhar2" required accept="image/*" class="form-control mb-1" />
                                    <div class="small text-danger">Supported Format: JPG/JPEG/PNG Only</div>
                                </div>
                                <input type="hidden" name="btn" value="1">
                                <button class="btn btn-primary btn-submit">Submit</button>
                                <a href="aadhar-verification.php" class="btn btn-dark">Change</a>
                            </form>

                        </div>
                        <div class="border-start col-sm-6 mb-2">
                            <hr>
                            <div class="ridge" align="center">
                                <img src="assets/front/img/adhar-card.png" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include "common/footer.php";
