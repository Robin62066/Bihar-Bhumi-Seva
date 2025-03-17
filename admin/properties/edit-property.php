<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$user_id = $_GET['id'];
$user = $db->select('ai_properties', ['id' => $user_id])->row();

if (isset($_POST['btn_update'])) {

    $fields = $_POST['form'];
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
        $fields['image'] = do_upload('image');
    }


    $db->update('ai_properties', $fields, ['id' => $user_id]);
    set_flashdata('success_msg', 'Profile updated successfully');
    redirect(admin_url('properties/edit-property.php?id=' . $user_id));
}
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
        <form method="POST" enctype="multipart/form-data">
            <div class="bg-white p-3 shadow-sm1 rounded">

                <div class="row">
                    <div class="col-sm-12">
                        <label>Property Title:</label>
                        <input type="text" name="form[title]" class="form-control" value="<?= $user->title; ?>">

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label>Property Type:</label>
                        <select class="form-select" style="width: 300px;" name="form[property_type]">
                            <option <?= $user->property_type == "Land" ? 'selected' : ''; ?> value="Land">Land</option>
                            <option <?= $user->property_type == "Office" ? 'selected' : ''; ?> value="Office">Office</option>
                            <option <?= $user->property_type == "Flat" ? 'selected' : ''; ?> value="Flat">Flat</option>
                            <option <?= $user->property_type == "House" ? 'selected' : ''; ?> value="House">House</option>
                            <option <?= $user->property_type == "Villa" ? 'selected' : ''; ?> value="Villa">Villa</option>
                            <option <?= $user->property_type == "Farm House" ? 'selected' : ''; ?> value="Farm House">Farm House</option>
                        </select>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label>Property For:</label>
                        <select class="form-select" style="width: 300px;" name="form[property_for]">
                            <option <?= $user->property_type == "Sell" ? 'selected' : ''; ?> value="Sell">Sell</option>
                            <option <?= $user->property_type == "Rent" ? 'selected' : ''; ?> value="Rent">Rent</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label>Address:</label>
                        <textarea name="form[address]" rows="4" type="text" class="form-control"><?= $user->address; ?></textarea>

                    </div>
                    <div class="col-sm-6 mb-3">
                        <label>Cost:</label>
                        <input type="number" name="form[cost]" class="form-control" value="<?= $user->cost; ?>">
                    </div>


                </div>

                <div class="row">
                    <div class="col mb-3">
                        <label>Description:</label>
                        <textarea name="form[description]" rows="4" type="text" class="form-control"><?= $user->description; ?></textarea>

                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label>Owner Name:</label>
                        <input type="text" name="form[owner]" class="form-control" value="<?= $user->owner; ?>">

                    </div>
                    <div class="col-sm-6 mb-3">
                        <label>Status:</label>
                        <select class="form-select" style="width: 300px;" name="form[status]">
                            <option <?= $user->status == 1 ? 'selected' : ''; ?> value="1">Activated</option>
                            <option <?= $user->status == 0 ? 'selected' : ''; ?> value="0">Deactivated</option>
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

                <button type="submit" name="btn_update" value="Update" class="btn btn-primary px-4 py-2 mt-3">Submit</button>

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