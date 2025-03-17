<?php
include "config/autoload.php";
if (!isset($_SESSION['user'])) {
    $_SESSION['error_msg'] = 'Please login to continue';
    header('location: login.php');
}
$user = $_SESSION['user'];
$user_id = $user->id;
include_once "common/header.php";


?>
<div id="origin" class="container">
    <main class="row py-5">
        <div class="col-sm-8 m-auto">
            <div class="bg-white rounded shadow-sm overflow-hidden mb-3">
                <div class="p-3">
                    <h5>KYC Complete - Munsi Signup</h5>
                    <hr />
                    <!-- Form Start -->
                    <form action="kyc_user_action.php" method="post">
                        <div class="mb-2">
                            <label>Name(As per Aadhar Card)</label>
                            <input type="text" class="form-control" name="aadhar_name">
                        </div>
                        <div class="mb-2">
                            <label>Father/Wife/Husband Name</label>
                            <input type="text" class="form-control" name="father_name">
                        </div>
                        <div class="mb-2">
                            <label>Address</label>
                            <input type="text" class="form-control" name="aadress">
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <label>CITY</label>
                                <input type="text" class="form-control" name="city" required />
                            </div>
                            <div class="col-sm-6">
                                <label>PINCODE</label>
                                <input type="text" class="form-control" name="pincode" required />
                            </div>
                        </div>
                        <div class="mb-2">
                            <label>DATE OF BIRTH</label>
                            <input type="date" class="form-control" name="dob">
                        </div>

                        <h6>Upload Documents</h6>
                        <hr />
                        <div class="row mb-3">
                            <!-- Add document upload fields here -->
                        </div>

                        <h6>Verification Details</h6>
                        <hr />
                        <div class="mb-3">
                            <label>Aadhar Verify</label>
                            <div class="d-flex gap-3 w-50 mb-3">
                                <input type="text" value="<?= $user->aadhar_number; ?>" class="form-control" name="aadhar_number">
                                <button class="btn btn-warning">Verify</button>
                            </div>
                            <label>PAN Verify</label>
                            <div class="d-flex gap-3 w-50">
                                <input type="text" value="<?= $user->pan_number; ?>" class="form-control" name="pan_number">
                                <button class="btn btn-warning">Verify</button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <b>Disclaimer: </b>A views expressed disclaimer asserts that the opinions expressed in an article or any written material are those of the author and not the opinion of the website. Publishers usually use this to protect themselves from liability. Also, persons belonging to an organization use this disclaimer to clarify that anything they say is their individual opinion, not their organization’s official stance.
                        </div>
                        <button type="submit" class="btn btn-primary" value="submit" name="btn_submit">Submit</button>
                        <a href="login.php" class="btn btn-dark">Cancel</a>
                    </form>
                    <!-- Form End -->
                </div>
            </div>
        </div>
    </main>
</div>
<?php
include_once "common/footer.php";
?>