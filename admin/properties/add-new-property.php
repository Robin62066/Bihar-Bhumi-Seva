<?php
include "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$dists = $db->select('ai_districts', [], false, 'dist_name ASC')->result();
$arr_owners = $db->select('ai_users', ['status' => 1], false, "first_name ASC")->result();
$arr_brokers = $db->select('ai_users', ['status' => 1, 'user_type' => USER_BROKER, 'kyc_status' => 1], false, "first_name ASC")->result();
$arr_munsi = $db->select('ai_users', ['status' => 1, 'user_type' => USER_MUNSI, 'kyc_status' => 1], false, "first_name ASC")->result();


$menu = 'properties';
include "../common/header.php";
?>
<style>
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
</style>
<div id="origin">
    <div class="page-header">
        <h5>Add Your New Property</h5>
    </div>
    <div class="card card-body" style="width: 700px;">
        <form action="save-new-property.php" method="POST" enctype="multipart/form-data">
            <div class="bg-white p-3 shadow-sm1 rounded">

                <div class="row">
                    <div class="col-sm-12">
                        <label>Property Title:</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label>Property Type:</label>
                        <select class="form-select" style="width: 300px;" name="property_type">
                            <option selected>Select</option>
                            <option value="Land">Land</option>
                            <option value="Office">Office</option>
                            <option value="Flat">Flat</option>
                            <option value="House">House</option>
                            <option value="Villa">Villa</option>
                            <option value="Farm House">Farm House</option>
                        </select>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label>Property For:</label>
                        <select class="form-select" style="width: 300px;" name="property_for">
                            <option selected>Select</option>
                            <option value="Sell">Sell</option>
                            <option value="Rent ">Rent</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label>Address:</label>
                        <textarea class="form-control" placeholder="Full Address" name="address" rows="3"></textarea>

                    </div>
                    <div class="col-sm-6 mb-3">
                        <label>Cost:</label>
                        <input type="number" name="cost" class="form-control">
                    </div>


                </div>

                <div class="row">
                    <div class="col mb-3">
                        <label>Description:</label>
                        <textarea class="form-control" placeholder="Property Description" name="description" rows="3"></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label>Owner Name:</label>
                        <input type="text" name="owner" class="form-control" id="owner">

                    </div>
                    <div class="col-sm-6 mb-3">
                        <label>Status:</label>
                        <select class="form-select" style="width: 300px;" id="status" name="status">
                            <option selected>Select</option>
                            <option value="1">Activated</option>
                            <option value="0">Deactivated</option>

                        </select>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label>Upload Image:</label>
                        <input type="file" name="image" class="form-control">
                        <small class="form-text text-muted">Allowed formats: jpg, png, jpeg</small>
                    </div>

                </div>

                <button type="submit" name="submit" class="btn btn-primary px-4 py-2 mt-3">Submit</button>

            </div>
    </div>
    </form>
</div>
</div>
</div>

</div>
<?php
include "../common/footer.php";
?>