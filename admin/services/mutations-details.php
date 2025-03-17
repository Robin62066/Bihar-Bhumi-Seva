<?php
include "../../config/autoload.php";
error_reporting(E_ERROR);
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$menu = 'services';
$id = $_GET['id'] ?? null;

if (isset($_POST['clicked'])) {
    $form = $_POST['form'];
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
        $fields['image'] = do_upload('image');
    }
    $db->update('ai_mutations', $form, ['id' => $id]);
    session()->set_flashdata('success', 'Details Updated Successfully');
}

$item = $row = $db->select('ai_mutations', ['id' => $id])->row();
$tab = $_GET['tab'] ?? 'basic';
$url = admin_url('services/mutations-details.php?id=' . $id);
include "../common/header.php";
?>
<div id="origin">
    <div class="page-header">
        <h5>Mutation Details: <?= $item->token; ?></h5>
    </div>
    <div class="bg-white border rounded-1 p-2 mb-2">
        <a href="<?= $url; ?>" class="btn btn-sm <?= $tab == 'basic' ? 'btn-primary' : 'btn-secondary'; ?>">Basic Details</a>
        <a href="<?= $url; ?>&tab=documents" class="btn btn-sm <?= $tab == 'documents' ? 'btn-primary' : 'btn-secondary'; ?>">Document Details</a>
        <a href="<?= $url; ?>&tab=buyer" class="btn btn-sm <?= $tab == 'buyer' ? 'btn-primary' : 'btn-secondary'; ?>">Buyer Details</a>
        <a href="<?= $url; ?>&tab=seller" class="btn btn-sm <?= $tab == 'seller' ? 'btn-primary' : 'btn-secondary'; ?>">Seller Details</a>
        <a href="<?= $url; ?>&tab=plot" class="btn btn-sm <?= $tab == 'plot' ? 'btn-primary' : 'btn-secondary'; ?>">Plot Details</a>
        <a href="<?= $url; ?>&tab=uploads" class="btn btn-sm <?= $tab == 'uploads' ? 'btn-primary' : 'btn-secondary'; ?>">Upload Details</a>
    </div>
    <div class="card mb-2 p-2">
        <?php
        if ($tab  == 'basic') {
        ?>
            <h5>Basic Details</h5>
            <table class="table">
                <tbody>
                    <tr>
                        <th>Applicat Id:</th>
                        <td> <?= $item->token; ?></td>
                        <th>Name</th>
                        <td><?= $item->fname; ?></td>
                        <th>Case Year</th>
                        <td><?= $item->case_year; ?></td>
                    </tr>
                    <tr>
                        <th>Guardian:</th>
                        <td> <?= $item->guardian; ?></td>
                        <th>Relation</th>
                        <td><?= $item->relation; ?></td>
                        <th>Applied On</th>
                        <td><?= $item->created; ?></td>
                    </tr>
                </tbody>
            </table>
        <?php
        } else if ($tab == 'documents') {
            $ab1 = $db->select("ai_mutation_data", ['app_id' => $id, 'meta_name' => 'doc_type'])->row();
            $ab2 = $db->select("ai_mutation_data", ['app_id' => $id, 'meta_name' => 'doc_details'])->row();
            $items = json_decode($ab2->meta_value);
        ?>
            <h5>Document Details</h5>
            <div class="card">
                <div class="card-header">Document Type: <?= $ab1->meta_value; ?></div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Doc Type</th>
                            <th>Doc Number</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Authority</th>
                            <th>Dist name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sl = 1;
                        foreach ($items as $item) {
                        ?>
                            <tr>
                                <td><?= $sl++; ?></td>
                                <td><?= $item->doc_type; ?></td>
                                <td><?= $item->doc_number; ?></td>
                                <td><?= $item->doc_date; ?></td>
                                <td><?= $item->amount; ?></td>
                                <td><?= $item->authority; ?></td>
                                <td><?= $item->dist_name; ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php
        } else if ($tab == 'buyer') {
            $ab2 = $db->select("ai_mutation_data", ['app_id' => $id, 'meta_name' => 'buyer_details'])->row();
            $items = json_decode($ab2->meta_value);
        ?>
            <h5>Buyers Details</h5>
            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Name</th>
                            <th>Guardian</th>
                            <th>Relation</th>
                            <th>Caste</th>
                            <th>Gender</th>
                            <th>Mobile</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sl = 1;
                        foreach ($items as $item) {
                        ?>
                            <tr>
                                <td><?= $sl++; ?></td>
                                <td><?= $item->name; ?></td>
                                <td><?= $item->guardian; ?></td>
                                <td><?= $item->relation; ?></td>
                                <td><?= $item->caste; ?></td>
                                <td><?= $item->gender; ?></td>
                                <td><?= $item->mobile; ?></td>
                                <td><?= $item->address; ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php
        } else if ($tab == 'seller') {
            $ab2 = $db->select("ai_mutation_data", ['app_id' => $id, 'meta_name' => 'seller_details'])->row();
            $items = json_decode($ab2->meta_value);
        ?>
            <h5>Seller Details</h5>
            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Name</th>
                            <th>Guardian</th>
                            <th>Relation</th>
                            <th>Caste</th>
                            <th>Gender</th>
                            <th>Mobile</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sl = 1;
                        foreach ($items as $item) {
                        ?>
                            <tr>
                                <td><?= $sl++; ?></td>
                                <td><?= $item->name; ?></td>
                                <td><?= $item->guardian; ?></td>
                                <td><?= $item->relation; ?></td>
                                <td><?= $item->caste; ?></td>
                                <td><?= $item->gender; ?></td>
                                <td><?= $item->mobile; ?></td>
                                <td><?= $item->address; ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php
        } else if ($tab == 'plot') {
            $a = $db->select("ai_mutation_data", ['app_id' => $id, 'meta_name' => 'dist_id'])->row();
            $b = $db->select("ai_mutation_data", ['app_id' => $id, 'meta_name' => 'sub_division'])->row();
            $c = $db->select("ai_mutation_data", ['app_id' => $id, 'meta_name' => 'circle'])->row();
            $d = $db->select("ai_mutation_data", ['app_id' => $id, 'meta_name' => 'mauja'])->row();
            $e = $db->select("ai_mutation_data", ['app_id' => $id, 'meta_name' => 'halka'])->row();
            $f = $db->select("ai_mutation_data", ['app_id' => $id, 'meta_name' => 'thana'])->row();
        ?>
            <h5>Plot Details</h5>
            <div class="card">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Dist Name</td>
                            <td><?= $a->meta_value; ?></td>
                            <td>Sub-Division</td>
                            <td><?= $b->meta_value; ?></td>
                            <td>Circle</td>
                            <td><?= $c->meta_value; ?></td>
                        </tr>
                        <tr>
                            <td>Mauja</td>
                            <td><?= $d->meta_value; ?></td>
                            <td>Halka</td>
                            <td><?= $e->meta_value; ?></td>
                            <td>Thana</td>
                            <td><?= $f->meta_value; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php
        } else if ($tab == 'uploads') {
            $f = $db->select("ai_mutation_data", ['app_id' => $id, 'meta_name' => 'pdf'])->row();
        ?>
            <h5>Uploaded Document Details</h5>
            <div class="card">
                <div class="card-body">
                    <?php
                    if (is_object($f) && $f->meta_value != '') {
                    ?>
                        <a href="<?= base_url(upload_dir($f->meta_value)) ?>" class="btn btn-sm btn-primary" download="download">Download Documents</a>
                    <?php
                    }
                    ?>
                </div>
            </div>
        <?php
        }
        ?>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="col-sm-4">
                        <div class="mb-3">
                            <label>Payment Status</label>
                            <select name="form[pay_status]" class="form-select">
                                <option value="">Select Status</option>
                                <option <?= $row->pay_status == 0 ? 'selected' : ''; ?> value="0">Pending</option>
                                <option <?= $row->pay_status == 1 ? 'selected' : ''; ?> value="1">Paid</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Application Status</label>
                            <select name="form[status]" class="form-select">
                                <option value="">Select Status</option>
                                <option <?= $row->status == 0 ? 'selected' : ''; ?> value="0">Pending</option>
                                <option <?= $row->status == 2 ? 'selected' : ''; ?> value="2">Processing</option>
                                <option <?= $row->status == 4 ? 'selected' : ''; ?> value="4">Additional Documents Required</option>
                                <option <?= $row->status == 3 ? 'selected' : ''; ?> value="3">Rejected</option>
                                <option <?= $row->status == 1 ? 'selected' : ''; ?> value="1">Mutation Completed</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <label>Memo</label>
                                <textarea name="form[notes]" class="form-control" rows="3"><?= $row->notes; ?></textarea>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label>User Message</label>
                                <textarea name="form[user_msg]" class="form-control" rows="3"><?= $row->notes; ?></textarea>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Upload</label>
                            <input type="file" name="image">
                        </div>

                        <input type="hidden" name="clicked" value="1" />
                        <button class="btn btn-submit btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
include "../common/footer.php";
