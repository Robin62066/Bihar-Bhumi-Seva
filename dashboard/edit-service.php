<?php
require('../config/autoload.php');
if (!is_login()) redirect(base_url('login.php'), 'You must login to continue');
$user_id = user_id();

$id = $_GET['id'] ?? null;
if ($id == null) {
    redirect(base_url('dashboard/services.php'), "Opps!! Some error.");
}
$item = $db->select('ai_services', ['id' => $id], 1)->row();


if (isset($_POST['clicked'])) {
    $sb = $_POST['form'];
    $db->update("ai_services", $sb, ['id' => $id]);
    session()->set_flashdata('success', "Services Updated Successfully");
    redirect(base_url('dashboard/services.php'));
}

include "../common/header.php";
?>


<div class="dashboard">
    <div class="container py-5">
        <div class="user-panel">
            <div class="row">
                <?php
                $subpage = 'bhumi';
                include_once "dashboard-menu.php"; ?>
                <div class="col-sm-9">
                    <?= front_view('common/alert'); ?>
                    <div id="origin">
                        <div class="bg-white shadow-sm p-4 rounded-sm">
                            <div class="page-header">
                                <h5>Service Details</h5>
                                <a href="<?= base_url('dashboard/add-services.php') ?>" class="btn btn-sm btn-primary"><i class="bi-plus"></i> New Service</a>
                            </div>
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label>Service name</label>
                                    <input type="text" name="form[service_name]" value="<?= $item->service_name; ?>" required class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label>Details</label>
                                    <textarea rows="3" name="form[details]" required class="form-control"><?= $item->details; ?></textarea>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <label>Status</label>
                                        <select name="form[status]" required class="form-select">
                                            <option <?= $item->status == 1 ? 'selected' : '' ?> value="1">Active</option>
                                            <option <?= $item->status == 0 ? 'selected' : '' ?> value="0">Draft</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3 align-items-end">
                                    <div class="col-sm-3">
                                        <label>Locations</label>
                                        <input type="text" v-model="city_name" class="form-control">
                                    </div>
                                    <div class="col-sm-2 d-grid">
                                        <button :disabled="city_name.length==0" type="button" @click="addItem" class="btn btn-primary">Add More</button>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Sl</th>
                                                    <th>Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item,sl) in items">
                                                    <td>{{ sl + 1 }}</td>
                                                    <td>{{ item.city }}</td>
                                                    <td>
                                                        <button @click="removeItem(sl)" type="button" class="btn btn-xs btn-danger">Del</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div>
                                    <input type="hidden" v-model="locations" name="form[locations]">
                                    <input type="hidden" name="clicked" value="1">
                                    <button class="btn btn-primary btn-submit">SUBMIT</button>
                                    <a href="<?= base_url('dashboard/services.php') ?>" class="btn btn-secondary">CANCEL</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include "../common/footer.php";
?>
<script>
    let vm = new Vue({
        el: '#origin',
        data: {
            items: [],
            city_name: '',
            locations: ''
        },
        methods: {
            addItem: function() {
                let ab = {
                    city: this.city_name
                }
                this.items.push(ab);
                this.city_name = ''
                this.locations = JSON.stringify(this.items);
            },
            removeItem: function(i) {
                this.items.splice(i, 1);
                this.locations = JSON.stringify(this.items);
            }
        },
        created: function() {
            this.locations = '<?= $item->locations; ?>';
            this.items = JSON.parse(this.locations);
        }
    });
</script>