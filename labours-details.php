<?php
include "../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');
$id = $_GET['id'] ?? null;
$item = $row = $db->select('ai_labours', ['id' => $id])->row();
include "common/header.php";
?>
<div id="origin">
    <div class="page-header">
        <h5>Labour Details:</h5>
    </div>
    <div class="bg-white border rounded-1 p-2 mb-2">
        <button class="btn btn-primary" @click="basicDetails">Basic Details</button>
        <button class="btn btn-primary" @click="address">Address</button>
        <button class="btn btn-primary" @click="emp">Employee Details</button>
        <button class="btn btn-primary" @click="bank">Bank Details</button>
        <button class="btn btn-primary" @click="status">Status</button>
        <a href="<?= admin_url('labours-card.php?id=' . $item->id); ?>" class=" btn btn-primary">Download Card</a>

    </div>
    <div v-if="btn ==='basicDetails'" class="card mb-2 p-2" style="border: 1px solid #ccc; padding: 10px;margin-bottom: 20px;">
        <h5>Basic Details</h5>
        <table class="table">
            <tbody>
                <tr>
                    <td>
                        <img src="<?= base_url(upload_dir($item->photo)) ?>" width="100" />
                    </td>
                    <th>Applicat Id:</th>
                    <td><?= $item->id; ?></td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td><?= $item->first_name . " " . $item->middle_name . " " . $item->middle_name; ?></td>
                    <th>D.O.B</th>
                    <td><?= $item->dob; ?></td>
                    <th>Gender</th>
                    <td><?= $item->gender; ?></td>
                </tr>
                <tr>
                    <th>Father Name</th>
                    <td><?= $item->father_name; ?></td>
                    <th>Mother Name</th>
                    <td><?= $item->mother_name; ?></td>
                    <th>Marital Stats</th>
                    <td><?= $item->marital_status; ?></td>
                </tr>
                <tr>
                    <th>Contact Numbber</th>
                    <td><?= $item->mobile_number; ?></td>
                    <th>Adahar Number</th>
                    <td><?= $item->aadhar_no; ?></td>
                    <th>Pan Number</th>
                </tr>
                <tr>
                    <td><?= $item->pan_no; ?></td>
                    <th>Voter Id</th>
                    <td><?= $item->voter_no; ?></td>
                    <th>Email</th>
                    <td><?= $item->email_id; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div v-if="btn ==='address'" class="card mb-2 p-2" style="border: 1px solid #ccc; padding: 10px;margin-bottom: 20px;">
        <h5>Address</h5>
        <table class="table">
            <tbody>
                <tr>
                    <th>Current Address</th>
                </tr>
                <tr>
                    <th>Address</th>
                    <td><?= $item->address1 . " " . $item->address2; ?></td>
                    <th>city</th>
                    <td><?= $item->city; ?></td>
                    <th>Pincode</th>
                    <td><?= $item->pincode; ?></td>
                    <th>contact</th>
                    <td><?= $item->contact; ?></td>
                </tr>
                <tr>
                    <th>Permanent Address</th>
                </tr>
                <tr>
                    <th>Address</th>
                    <td><?= $item->paddress1 . " " . $item->paddress2; ?></td>
                    <th>City</th>
                    <td><?= $item->pcity; ?></td>
                    <th>Pincode</th>
                    <td><?= $item->ppincode; ?></td>
                    <th>contact</th>
                    <td><?= $item->pcontact; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div v-if="btn ==='emp'" class="card mb-2 p-2" style="border: 1px solid #ccc; padding: 10px;margin-bottom: 20px;">
        <h5>Employee Details</h5>
        <table class="table">
            <tbody>
                <tr>
                    <th>Employee Name</th>
                    <td><?= $item->emp_name; ?></td>
                    <th>Employee Address</th>
                    <td><?= $item->emp_address; ?></td>
                    <th>Employee Phone</th>
                    <td><?= $item->emp_phone; ?></td>
                </tr>
                <tr>
                    <th>Industory/ Other Industory</th>
                    <?php
                    if ($item->industry == 'Others') {
                    ?>
                        <td><?= $item->industry_other; ?></td>
                    <?php
                    } else {
                    ?>
                        <td><?= $item->industry; ?></td>
                    <?php
                    }
                    ?>

                    <th>Occupaton/ other Occupation</th>
                    <td>
                        <?php
                        if ($item->occupation == 'Others') {
                        ?>
                    <td><?= $item->occupation_other; ?></td>
                <?php
                        } else {
                ?>
                    <td><?= $item->occupation; ?></td>
                <?php
                        }
                ?>

                </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div v-if="btn === 'bank'" class="card mb-2 p-2" style="border: 1px solid #ccc; padding: 10px;margin-bottom: 20px;">
        <h5>Bank Details</h5>
        <table class="table">
            <tbody>
                <tr>
                    <th>Bank Name</th>
                    <td><?= $item->bank_name ?></td>
                    <th>Account Number</th>
                    <td><?= $item->bank_ac_number; ?></td>
                    <th>IFSCE Code</th>
                    <td><?= $item->bank_ifsc; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div v-if="btn === 'status'" class="card mb-2 p-2" style="border: 1px solid #ccc; padding: 10px;margin-bottom: 20px;">
        <h5>Status</h5>
        <table class="table">
            <tbody>
                <tr>
                    <th>Status</th>
                    <td><?= $item->status; ?></td>
                    <th>Created</th>
                    <td><?= $item->created; ?></td>
                    <th>Updated</th>
                    <td><?= $item->updated; ?></td>
                </tr>
                <tr>
                    <th>Message</th>
                    <td><?= $item->message; ?></td>
                    <th>Steps</th>
                    <td><?= $item->steps; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php
include "./common/footer.php";
?>
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
            address: function() {
                this.btn = 'address';
            },
            emp: function() {
                this.btn = 'emp';
            },
            bank: function() {
                this.btn = 'bank';
            },
            status: function() {
                this.btn = 'status';
            }
        }
    })
</script>