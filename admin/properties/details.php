<?php
include_once "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$id = isset($_GET['id']) ? $_GET['id'] : null;
if (isset($_POST['btnok'])) {

    $form = $_POST['form'];
    if (isset($_POST['del_front']) && $_POST['del_front'] == 1) {
        $form['photo_front'] = '';
    }
    if (isset($_POST['del_back']) && $_POST['del_back'] == 1) {
        $form['photo_back'] = '';
    }
    if (isset($_POST['del_left']) && $_POST['del_left'] == 1) {
        $form['photo_left'] = '';
    }
    if (isset($_POST['del_right']) && $_POST['del_right'] == 1) {
        $form['photo_right'] = '';
    }
    if (isset($_POST['del_rasid']) && $_POST['del_rasid'] == 1) {
        $form['rasid_photo'] = '';
    }

    if (isset($_FILES['photo_front']['name']) && $_FILES['photo_front']['name'] != '') {
        $form['photo_front'] = do_upload('photo_front');
    }
    if (isset($_FILES['photo_back']['name']) && $_FILES['photo_back']['name'] != '') {
        $form['photo_back'] = do_upload('photo_back');
    }
    if (isset($_FILES['photo_left']['name']) && $_FILES['photo_left']['name'] != '') {
        $form['photo_left'] = do_upload('photo_left');
    }
    if (isset($_FILES['photo_right']['name']) && $_FILES['photo_right']['name'] != '') {
        $form['photo_right'] = do_upload('photo_right');
    }
    if (isset($_FILES['video']['name']) && $_FILES['video']['name'] != '') {
        $form['video'] = do_upload('video');
    }
    if (isset($_FILES['rasid_photo']['name']) && $_FILES['rasid_photo']['name'] != '') {
        $form['rasid_photo'] = do_upload('rasid_photo');
    }


    $db->update('ai_sites', $form, ['id' => $id]);
    redirect(admin_url('properties/details.php?id=' . $id), 'Details updated successfully', 'success');
}
$item = $db->select('ai_sites', ['id' => $id], 1)->row();
$menu = 'properties';
$blist = $db->select('ai_users', ['user_type' => USER_BROKER])->result();
$mlist = $db->select('ai_users', ['user_type' => USER_MUNSI])->result();
$arrOwners = $db->select('ai_users', ['user_type' => USER_LAND_OWNER])->result();
$user = $db->select('ai_users', ['id' => $item->user_id])->row();

include "../common/header.php";

$perm = getPermission();
$perm->setModule(Permission::PROPERTIES);
?>
<div id="origin">
    <div class="page-header">
        <h5>Full Details</h5>
        <a href="<?= admin_url('properties') ?>" class="btn btn-sm btn-primary">Cancel</a>
    </div>
    <form action="" method="post" enctype="multipart/form-data">
        <?php
        if ($item->status == 2) {
        ?>
            <div class="alert alert-danger">This Property is marked as Rejected.</div>
        <?php
        }
        ?>
        <div class="row">
            <div class="col-sm-7">
                <div class="card">
                    <div class="card-header">Property Full Details</div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td>Proerty Id</td>
                                    <td>
                                        <a href="<?= base_url('property-view.php?id=' . $item->id) ?>" target="_blank">
                                            #<?= $id; ?>
                                        </a>

                                    </td>
                                </tr>
                                <tr>
                                    <td>Property For</td>
                                    <td> <select class="form-select" name="form[property_for]">
                                            <option <?= $item->property_for == 'Rent' ? 'selected' : ''; ?> value="Rent">Rent</option>
                                            <option <?= $item->property_for == 'Sell' ? 'selected' : ''; ?> value="Sell">Sell</option>
                                        </select> </td>
                                </tr>
                                <tr>
                                    <td>Property Type</td>
                                    <td> <select class="form-select" name="form[property_type]">
                                            <option value="">Select</option>
                                            <option value="Land" <?= $item->property_type == 'Land' ? 'selected' : ''; ?>>Land</option>
                                            <option value="Office" <?= $item->property_type == 'Office' ? 'selected' : ''; ?>>Office</option>
                                            <option value="Flat" <?= $item->property_type == 'Flat' ? 'selected' : ''; ?>>Flat</option>
                                            <option value="House" <?= $item->property_type == 'House' ? 'selected' : ''; ?>>House</option>
                                            <option value="Villa" <?= $item->property_type == 'Villa' ? 'selected' : ''; ?>>Villa</option>
                                            <option value="Farm House" <?= $item->property_type == 'Farm House' ? 'selected' : ''; ?>>Farm House</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Title</td>
                                    <td> <input type="text" name="form[site_title]" value="<?= $item->site_title; ?>" class="form-control"> </td>
                                </tr>
                                <tr>
                                    <td>Details</td>
                                    <td> <textarea name="form[details]" rows="2" class="form-control"><?= $item->details; ?></textarea> </td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td>
                                        <input type="text" name="form[address]" value="<?= $item->address; ?>" class="form-control">
                                    </td>
                                </tr>
                                <?php
                                if ($item->property_type == 'Land') {
                                ?>
                                    <tr>
                                        <td>Mauja No</td>
                                        <td><input type="text" name="form[mauja]" value="<?= $item->mauja; ?>" class="form-control width-100">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Jamabandi No</td>
                                        <td><input type="text" name="form[jamabandi_no]" value="<?= $item->jamabandi_no; ?>" class="form-control width-100">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Bhag Vartman</td>
                                        <td><input type="text" name="form[bhag_vartman]" value="<?= $item->bhag_vartman; ?>" class="form-control width-100">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Page no</td>
                                        <td><input type="text" name="form[page_no]" value="<?= $item->page_no; ?>" class="form-control width-100">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Thana</td>
                                        <td><input type="text" name="form[thana_no]" value="<?= $item->thana_no; ?>" class="form-control width-100">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Raiyat Name</td>
                                        <td><input type="text" name="form[jamabani_raiyat_name]" value="<?= $item->jamabani_raiyat_name; ?>" class="form-control">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Khata No</td>
                                        <td><input type="text" name="form[khata_no]" value="<?= $item->khata_no; ?>" class="form-control width-100">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Khesra No</td>
                                        <td><input type="text" name="form[khesra_no]" value="<?= $item->khesra_no; ?>" class="form-control width-100">
                                        </td>
                                    </tr>
                                <?php } else {
                                ?>
                                    <tr>
                                        <td>Ammenities</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <input type="text" v-model="itemTitle" class="form-control" placeholder="e.g. Number of Rooms">
                                                <button type="button" @click="addItem" class="btn btn-sm btn-primary">ADD</button>
                                            </div>
                                            <div v-if="items.length > 0">
                                                <hr />
                                                <p><b>Added List</b></p>
                                                <ol>
                                                    <li v-for="(item1, indx) in items" class="mb-1">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span>{{item1}}</span>
                                                            <button type="button" @click="removeItem(indx)" class="badge bg-danger border-0">Remove</button>
                                                        </div>
                                                    </li>
                                                </ol>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <td>Total Area</td>
                                    <td class="d-flex align-items-center gap-3"><input type="text" name="form[total_area]" value="<?= $item->total_area; ?>" class="form-control width-200"> <?= $item->area_unit; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total Amount</td>
                                    <td><input type="text" name="form[total_amount]" value="<?= $item->total_amount; ?>" class="form-control width-100">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>
                                        <div>
                                            <select name="form[status]" class="form-select" style="width: 200px;">
                                                <option value="">Select</option>
                                                <option <?= $item->status == 1 ? 'selected' : ''; ?> value="1">Approve</option>
                                                <option <?= $item->status == 0 ? 'selected' : ''; ?> value="0">Pending</option>
                                                <option <?= $item->status == 2 ? 'selected' : ''; ?> value="2">Reject</option>
                                                <option <?= $item->status == 3 ? 'selected' : ''; ?> value="3">Sold</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Show on Home</td>
                                    <td>
                                        <div>
                                            <select name="form[share_on_home]" class="form-select" style="width: 200px;">
                                                <option value="0">Select</option>
                                                <option <?= $item->share_on_home == 1 ? 'selected' : ''; ?> value="1">Yes</option>
                                                <option <?= $item->share_on_home == 0 ? 'selected' : ''; ?> value="0">No</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Promoted to Sponsor</b></td>
                                    <td>
                                        <div>
                                            <select name="form[isPromoted]" class="form-select" style="width: 200px;">
                                                <option value="0">Select</option>
                                                <option <?= $item->isPromoted == 1 ? 'selected' : ''; ?> value="1">Yes</option>
                                                <option <?= $item->isPromoted == 0 ? 'selected' : ''; ?> value="0">No</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="col-sm-4">
                            <video width="320" height="240" controls>
                                <source src="<?= base_url(upload_dir($item->video)) ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <label for="">Upload Video</label>
                            <input type="file" name="video" accept="video/*" style="width: 250px;">
                            <span class="text-danger"> *Video Size less then 50MB</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="card mb-2">
                    <div class="card-header">Listed By</div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td>#</td>
                                    <td><a href="<?= admin_url('users/view.php?id=' . $user->id); ?>"><?= $user->id; ?></a></td>
                                </tr>
                                <tr>
                                    <td>Name</td>
                                    <td><?= $user->first_name . ' ' . $user->last_name; ?></td>
                                </tr>
                                <tr>
                                    <td>Mobile</td>
                                    <td><?= $user->mobile_number; ?></td>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td><?= user_type_string($user->user_type); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-header">Broker & Munsi Info</div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td>Land Owner</td>
                                    <td>
                                        <select name="form[owner_id]" class="form-select">
                                            <option value="0">Select Broker</option>
                                            <?php
                                            foreach ($arrOwners as $ab) {
                                            ?>
                                                <option <?= $item->owner_id == $ab->id ? 'selected' : ''; ?> value="<?= $ab->id; ?>"><?= $ab->first_name . ' ' . $ab->last_name; ?></option>
                                            <?php
                                            }
                                            ?>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Broker</td>
                                    <td>
                                        <select name="form[broker_id]" class="form-select">
                                            <option value="0">Select Broker</option>
                                            <option <?= $item->broker_id == $user->id ? 'selected' : ''; ?> value="<?= $user->id; ?>"><?= $user->first_name . ' ' . $user->last_name; ?> (SELF)</option>
                                            <?php
                                            foreach ($blist as $ab) {
                                            ?>
                                                <option <?= $item->broker_id == $ab->id ? 'selected' : ''; ?> value="<?= $ab->id; ?>"><?= $ab->first_name . ' ' . $ab->last_name; ?></option>
                                            <?php
                                            }
                                            ?>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Munsi</td>
                                    <td>
                                        <select name="form[munsi_id]" class="form-select">
                                            <option value="0">Select Munsi</option>
                                            <option <?= $item->munsi_id == $user->id ? 'selected' : ''; ?> value="<?= $user->id; ?>"><?= $user->first_name . ' ' . $user->last_name; ?> (SELF)</option>
                                            <?php
                                            foreach ($mlist as $ab) {
                                            ?>
                                                <option <?= $item->munsi_id == $ab->id ? 'selected' : ''; ?> value="<?= $ab->id; ?>"><?= $ab->first_name . ' ' . $ab->last_name; ?></option>
                                            <?php
                                            }
                                            ?>

                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Photos</div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td>Front Photo</td>
                                    <td>
                                        <?php
                                        if ($item->photo_front != '') {
                                        ?>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <img src="<?= base_url(upload_dir($item->photo_front)) ?>" width="100" height="80" />
                                                <label>
                                                    <input type="checkbox" name="del_front" value="1"> Delete
                                                </label>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        <input type="file" name="photo_front" class="form-control" />
                                    </td>

                                </tr>
                                <tr>
                                    <td>Back Photo</td>
                                    <td>
                                        <?php
                                        if ($item->photo_back != '') {
                                        ?>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <img src="<?= base_url(upload_dir($item->photo_back)) ?>" width="100" height="80" />
                                                <label>
                                                    <input type="checkbox" name="del_back" value="1"> Delete
                                                </label>
                                            </div>
                                        <?php } ?>
                                        <input type="file" name="photo_back" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Left Photo</td>
                                    <td>
                                        <?php
                                        if ($item->photo_left != '') {
                                        ?>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <img src="<?= base_url(upload_dir($item->photo_left)) ?>" width="100" height="80" />
                                                <label>
                                                    <input type="checkbox" name="del_left" value="1"> Delete
                                                </label>
                                            </div>
                                        <?php } ?>
                                        <input type="file" name="photo_left" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Right Photo</td>
                                    <td>
                                        <?php
                                        if ($item->photo_right != '') {
                                        ?>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <img src="<?= base_url(upload_dir($item->photo_right)) ?>" width="100" height="80" />
                                                <label>
                                                    <input type="checkbox" name="del_right" value="1"> Delete
                                                </label>
                                            </div>
                                        <?php } ?>
                                        <input type="file" name="photo_right" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Rasid Photo</td>
                                    <td>
                                        <?php
                                        if ($item->rasid_photo != '') {
                                        ?>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="<?= base_url(upload_dir($item->rasid_photo)) ?>" download class="btn btn-xs btn-primary">Download</a>
                                                <label>
                                                    <input type="checkbox" name="del_rasid" value="1"> Delete
                                                </label>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        <input type="file" name="rasid_photo" class="form-control">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-3 card-footer">
            <?php
            if ($perm->canEdit()) {
            ?>
                <input type="hidden" name="btnok" value="Approve">
                <button class="btn btn-primary btn-submit">Update Details</button>
                <a href="<?= admin_url('properties') ?>" class="btn btn-danger">Cancel</a>
            <?php
            }
            ?>
        </div>
        <input type="hidden" name="form[amenities]" :value="amlist">
    </form>
</div>
<?php
include "../common/footer.php";
?>

<script>
    new Vue({
        el: '#origin',
        data: {
            itemTitle: '',
            items: [],
            amlist: '<?= $item->amenities ?>'
        },
        methods: {
            removeItem: function(sl) {
                this.items.splice(sl, 1);
                this.amlist = this.items.toString();
            },
            addItem: function() {
                if (this.itemTitle.trim() != '') {
                    this.items.push(this.itemTitle);
                    this.itemTitle = '';
                    this.amlist = this.items.toString();
                }
            },
        },
        created: function() {
            <?php
            if ($item->amenities != '') {
            ?>
                this.items = '<?= $item->amenities ?>'.split(',');
            <?php
            }
            ?>
        }
    })
</script>