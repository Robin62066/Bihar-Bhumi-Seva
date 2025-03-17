<?php
include "../../config/autoload.php";
error_reporting(E_ERROR);
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$menu = 'services';
$id = $_GET['id'] ?? null;
$item = $row = $db->select('ai_mutations_app', ['id' => $id])->row();

if (isset($_POST['clicked'])) {
    $form = $_POST['form'];
    if (isset($_FILES['attachment']['name']) && $_FILES['attachment']['name'] != '') {
        $form['attachment'] = do_upload('attachment');
    }
    $db->update('ai_mutations_app', $form, ['id' => $id]);
    session()->set_flashdata('success', 'Details Updated Successfully');
}
include "../common/header.php";
?>
<style>
    .card {
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 20px;
    }
</style>
<div id="origin">
    <div class="page-header">
        <h5>Mutation App Details: <?= $item->token; ?></h5>
    </div>
    <div class="bg-white border rounded-1 p-2 mb-2">
        <button class="btn btn-sm btn-primary" @click="basicDetails">Basic Details</button>
        <button class="btn btn-sm btn-primary" @click="moreDetails">More Details</button>
    </div>
    <div v-if="btn ==='basicDetails'" class="card mb-2 p-2">
        <h5>Basic Details</h5>
        <table class="table">
            <tbody>
                <tr>
                    <th>User Id</th>
                    <td><?= $item->id; ?></td>
                    <th>Deed Id</th>
                    <td><?= $item->deed_no; ?></td>
                    <th>Status Id</th>
                    <td><?= $item->status; ?></td>
                </tr>
                <tr>
                    <th>Email Id</th>
                    <td><?= $item->email_id; ?></td>
                    <th>Whatsapp Number</th>
                    <td><?= $item->whatsapp; ?></td>
                    <th>Document</th>
                    <td><?= $item->documents; ?></td>
                </tr>
                <tr>
                    <th>Attachment </th>
                    <td><?= $item->attachment; ?></td>
                    <th>Pay Data</th>
                    <td><?= $item->pay_data; ?></td>
                    <th>Pay Date</th>
                    <td><?= $item->pay_date; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div v-if="btn ==='moreDetails'" class="card mb-2 p-2">
        <h5>More Details</h5>
        <table class="table">
            <tbody>
                <tr>
                    <th>Years</th>
                    <td><?= $item->years; ?></td>
                    <th>Created</th>
                    <td><?= $item->created; ?></td>
                    <th>Comments</th>
                    <td><?= $item->comments; ?></td>
                </tr>
                <tr>
                    <th>Updated</th>
                    <td><?= $item->updated; ?></td>
                    <th>Admin Id</th>
                    <td><?= $item->admin_id; ?></td>
                    <th>Oreder Id</th>
                    <td><?= $item->rzp_order_id; ?></td>
                </tr>
            </tbody>
        </table>
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
                        <div class="mb-3">
                            <label>Attachment</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Memo</label>
                            <textarea name="form[comments]" class="form-control" rows="3"><?= $row->notes; ?></textarea>
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
?>
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script>
    new Vue({
        el: '#origin',
        data: {
            btn: 'basicDetails' // Default view
        },
        methods: {
            basicDetails: function() {
                this.btn = 'basicDetails';
            },
            moreDetails: function() {
                this.btn = 'moreDetails';
            }
        }
    });
</script>