<?php
include "../config/autoload.php";
if (!is_login()) redirect(base_url('login.php'), 'Please Login to continue', 'danger');
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (isset($_POST['button'])) {
    $form = $_POST['form'];
    $db->update('ai_sites', $form, ['id' => $id]);
    set_flashdata('success_msg', 'Details updated Successfully');
}

$site = $db->select('ai_sites', ['id' => $id])->row();
include "../common/header.php";
?>
<div class="dashboard">
    <div class="container py-5">
        <div class="user-panel">
            <div class="row">
                <?php
                $subpage = 'properties';
                include_once "dashboard-menu.php"; ?>
                <div class="col-sm-9">
                    <?= front_view('common/alert'); ?>
                    <div class="page-header">
                        <h5>Edit Details</h5>
                    </div>
                    <form action="" method="post">
                        <div class="bg-white p-3 shadow-sm">
                            <div class="mb-2">
                                <label>Property Title</label>
                                <input type="text" name="form[site_title]" value="<?= $site->site_title; ?>" class="form-control" />
                            </div>
                            <div class="mb-2">
                                <label>Address</label>
                                <input type="text" name="form[address]" value="<?= $site->address; ?>" class="form-control" />
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <label>Mauja</label>
                                    <input type="text" name="form[mauja]" value="<?= $site->mauja; ?>" class="form-control" />
                                </div>
                                <div class="col-sm-6">
                                    <label>Jamabandi No</label>
                                    <input type="text" name="form[jamabandi_no]" value="<?= $site->jamabandi_no; ?>" class="form-control" />
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label>Bhag Vartman</label>
                                    <input type="text" name="form[bhag_vartman]" value="<?= $site->bhag_vartman; ?>" class="form-control" />
                                </div>
                                <div class="col-sm-4">
                                    <label>Page No</label>
                                    <input type="text" name="form[page_no]" value="<?= $site->page_no; ?>" class="form-control" />
                                </div>
                                <div class="col-sm-4">
                                    <label>Thana No</label>
                                    <input type="text" name="form[thana_no]" value="<?= $site->thana_no; ?>" class="form-control" />
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <label>Jamabandi Raiyat</label>
                                    <input type="text" name="form[jamabani_raiyat_name]" value="<?= $site->jamabani_raiyat_name; ?>" class="form-control" />
                                </div>
                                <div class="col-sm-6">
                                    <label>Gaurdian Name</label>
                                    <input type="text" name="form[guardian_name]" value="<?= $site->guardian_name; ?>" class="form-control" />
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label>Khata No</label>
                                    <input type="text" name="form[khata_no]" value="<?= $site->khata_no; ?>" class="form-control" />
                                </div>
                                <div class="col-sm-4">
                                    <label>Khesra No</label>
                                    <input type="text" name="form[khesra_no]" value="<?= $site->khesra_no; ?>" class="form-control" />
                                </div>
                                <div class="col-sm-4">
                                    <label>Total Area</label>
                                    <input type="text" name="form[total_area]" value="<?= $site->total_area; ?>" class="form-control" />
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label>Total Amount</label>
                                    <input type="text" name="form[total_amount]" value="<?= $site->total_amount; ?>" class="form-control" />
                                </div>

                            </div>
                            <button name="button" value="Update" class="btn btn-primary">UPDATE</button>
                            <a class="btn btn-dark" href="<?= base_url('dashboard/properties.php') ?>">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include "../common/footer.php";
