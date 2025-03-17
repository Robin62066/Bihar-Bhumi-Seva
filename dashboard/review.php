<?php
include "../config/autoload.php";
if (!is_login()) redirect(base_url('login.php'), 'Please Login to continue', 'danger');

$id = $_GET['id'] ?? '';
$items = $db->select('ai_labours', ['user_id' => $id], 1)->row();


include "../common/header.php";
?>
<style>
    body {
        font-size: 15px;
        font-weight: 400;
        color: grey;
    }


    .faq {
        background-color: darkcyan;
        padding: 10px;
    }

    .faq,
    h3 {
        color: white;
        font-size: 25px;
        font-weight: 700;
        font-family: 'Montserrat', sans-serif;
    }

    .faq span {
        color: grey;

    }

    .basic {
        width: 400px;
    }

    #tab {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    td,
    th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }
</style>
<div class="container">
    <div class="col-md-12">
        <div class="card p-2">
            <div class="faq text-center">
                <h3>BIHAR BHUMI SEVA</h3>
                <h5><b>Labour Review</b></h5>
            </div>
            <div class="row">
                <div class="col-8">
                    <table id="tab">
                        <tr>
                            <th>Labour</th>
                            <th>Details</th>
                        </tr>

                        <tr>
                            <th>First Name</th>
                            <td><?= $items->first_name; ?></td>
                        </tr>
                        <tr>
                            <th>Last Name</th>
                            <td><?= $items->last_name; ?></td>
                        </tr>
                        <th>Father Name</th>
                        <td><?= $items->father_name; ?></td>
                        <tr>
                            <th>Mother Name</th>
                            <td><?= $items->mother_name; ?></td>
                        </tr>
                        <th>Married Status</th>
                        <td><?= $items->marital_status; ?></td>

                        <tr>
                            <th>Date of Birth</th>
                            <td><?= $items->dob; ?></td>
                        </tr>
                        <tr>
                            <th>Email ID</th>
                            <td><?= $items->email_id; ?></td>
                        </tr>
                        <tr>
                            <th>Phone No.</th>
                            <td><?= $items->mobile_number; ?></td>
                        </tr>
                        <tr>
                            <th>Phone No.2</th>
                            <td><?= $items->contact; ?></td>
                        </tr>
                        <tr>
                            <th>District</th>
                            <td><?php
                                $dists = $db->select("ai_districts", ["id" => $items->dist_id], 1)->row();
                                echo $dists->dist_name;
                                ?></td>

                        </tr>
                        <tr>
                            <th>Anchal</th>
                            <td><?php
                                $Zones = $db->select("ai_zones", ["id" => $items->zone_id], 1)->row();
                                echo $Zones->zone_name; ?></td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td><?= $items->address1; ?></td>
                        </tr>
                        <tr>
                            <th>Pin-code</th>
                            <td><?= $items->pincode; ?></td>
                        </tr>
                        <tr>
                            <th>Aadhar Number</th>
                            <td><?= $items->aadhar_no; ?></td>
                        </tr>

                    </table>
                </div>

                <div class="col-4">
                    <table>
                        <tr>
                            <th>Photo</th>
                        </tr>
                        <tr>
                            <td> Profile Photo
                                <?php
                                if ($items->photo != '') {
                                ?>
                                    <img src="<?= base_url(upload_dir($items->photo)) ?>" alt="" class="img-fluid" /> <br />
                                <?php
                                }
                                ?>
                            </td>

                        </tr>
                        <tr>
                            <td>Aadhar Front
                                <?php
                                if ($items->aadhar_front != '') {
                                ?>
                                    <img src="<?= base_url(upload_dir($items->aadhar_front)) ?>" alt="" class="img-fluid" /> <br />
                                <?php
                                }
                                ?>
                            </td>

                        </tr>
                        <tr>
                            <td>Aadhar Back
                                <?php
                                if ($items->aadhar_back != '') {
                                ?>
                                    <img src="<?= base_url(upload_dir($items->aadhar_back)) ?>" alt="" class="img-fluid" /> <br />
                                <?php
                                }
                                ?>
                            </td>

                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-12 mt-4" align="center">
                <a href="<?= site_url('labours-card.php?id=' . $id); ?>" class="btn btn-primary">Submit</a>
            </div>
        </div>


    </div>
</div>
</div>