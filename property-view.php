<?php
include "config/autoload.php";
$id = isset($_GET['id']) ? $_GET['id'] : null;
if ($id == null) redirect('index.php', 'Opps!! Some error');
$item = $db->select('ai_sites', ['id' => $id], 1)->row();

if ($item === null) redirect('index.php', 'Opps!! Some error');
if ($item->status != 1) redirect('index.php', 'Opps!! Some error');

$user = $db->select('ai_users', ['id' => $item->user_id], 1)->row();
$broker = $db->select('ai_users', ['id' => $item->broker_id], 1)->row();
$owner = $db->select('ai_users', ['id' => $item->owner_id], 1)->row();

$hideSearch = true;

if (isset($_POST['btnleads'])) {
    $sb = $_POST['form'];
    $sb['user_id'] = is_login() ? user_id() : 0;
    $sb['created'] = date("Y-m-d H:i:s");
    $sb['prop_id'] = $id;
    $sb['status']  = 0;
    $db->insert("ai_leads", $sb);
    redirect(site_url("property-view.php?id=" . $id), "Enquiry Details sent successfully", "success");
}
include "common/header.php";
?>
<div class="container pt-3">
    <?php
    include "common/alert.php";
    ?>
    <!-- <hr /> -->
    <h5><?= $item->site_title; ?></h5>
    <div class="search-area bg-white mb-2 shadow shadow-sm p-2 rounded d-none">
        <div class="row">
            <div class="col-sm-3">
                <select class="form-select">
                    <option value="">District</option>
                </select>
            </div>
            <div class="col-sm-3">
                <select class="form-select">
                    <option value="">Zone</option>
                </select>
            </div>
            <div class="col-sm-3">
                <select class="form-select">
                    <option value="">Budget</option>
                </select>
            </div>
            <div class="col-sm-3 d-grid">
                <button class="btn btn-primary btn-sm">Filter</button>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between py-2">
        <div><span class="text-danger"> <i class="bi-geo-alt"></i> </span> <?= $item->address; ?> </div>
        <div>
            <i class="bi-bookmark"></i>
        </div>
    </div>
    <div class="border rounded mb-2">
        <div class="slider-main bg-white text-center">
            <div id="carouselExampleCaptions" class="carousel slide">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="<?= base_url(upload_dir($item->photo_front)) ?>" class="d-block w-100" alt="...">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Front Photo</h5>
                        </div>
                    </div>
                    <?php
                    if ($item->photo_back != '') {
                    ?>
                        <div class="carousel-item">
                            <img src="<?= base_url(upload_dir($item->photo_back)) ?>" class="d-block w-100" alt="...">
                            <div class="carousel-caption d-none d-md-block">
                                <h5>Back Photo</h5>
                            </div>
                        </div>
                    <?php
                    }
                    if ($item->photo_left != '') {
                    ?>
                        <div class="carousel-item">
                            <img src="<?= base_url(upload_dir($item->photo_left)) ?>" class="d-block w-100" alt="...">
                            <div class="carousel-caption d-none d-md-block">
                                <h5>Left Photo</h5>
                            </div>
                        </div>
                    <?php
                    }
                    if ($item->photo_right != '') {
                    ?>
                        <div class="carousel-item">
                            <img src="<?= base_url(upload_dir($item->photo_right)) ?>" class="d-block w-100" alt="...">
                            <div class="carousel-caption d-none d-md-block">
                                <h5>Right Photo</h5>
                            </div>
                        </div>
                    <?php
                    }
                    if ($item->video != '') {
                    ?>
                        <div class="carousel-item">
                            <video controls style="max-height: 428px; max-width: 100%;">
                                <source src="<?= base_url(upload_dir($item->video)) ?>" type="video/mp4" />
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
    <div class="border bg-white rounded mb-2">
        <div class="row">
            <div class="col-sm-4">
                <div class="p-3">
                    <h6 class="text-danger"> <i class="bi-currency-rupee"></i> <?= $item->total_amount ?>/- </h6>
                    <p class="text-muted"><?= $item->total_area . ' ' . $item->area_unit; ?></p>
                    <h6 class="d-flex gap-2">
                        <div class="bg-danger text-white py-2 px-2 rounded"> <i class="bi-telephone"></i> </div>
                        <div>
                            <div><?= $user->mobile_number; ?></div>
                            <div><?= $user->whatsapp; ?></div>
                        </div>
                    </h6>
                </div>
            </div>
            <?php
            if ($owner > 0) {
            ?>
                <div class="col-sm-4 border-start">
                    <div class="user-box-info p-3">

                        <div class="photo-view">
                            <img src="<?= base_url(upload_dir($owner->image)) ?>" class="img-fluid" />
                        </div>
                        <div class="detail-view">
                            <div><b>Owner Details</b></div>
                            <div><?= $owner->first_name . ' ' . $owner->last_name; ?></div>
                            <?php
                            if ($owner->aadhar_number != '') {
                            ?>
                                <div>Aadhar No: <?= str_pad(substr($owner->aadhar_number, strlen($owner->aadhar_number) - 4, 4), 12, 'X', STR_PAD_LEFT); ?></div>
                            <?php } else {
                            ?>
                                <div>Aadhar No: -</div>
                            <?php
                            }
                            if ($owner->mobile_number != '') {
                            ?>
                                <div>Phone No: <?= str_pad(substr($owner->mobile_number, strlen($owner->mobile_number) - 4, 4), 10, 'X', STR_PAD_LEFT); ?></div>
                            <?php
                            } else {
                            ?>
                                <div>Phone No: -></div>
                            <?php
                            }
                            ?>
                            <a href="<?= base_url('user-profile.php?id=' . $item->owner_id); ?>" class="btn btn-warning btn-xs">View Profile</a>


                        </div>
                    </div>
                </div>
            <?php }
            if ($broker !=  null) {
            ?>
                <div class="col-sm-4 border-start">
                    <div class="user-box-info p-3">
                        <div class="photo-view">
                            <img src="<?= base_url(upload_dir($broker->image)) ?>" class="img-fluid" />
                        </div>
                        <div class="detail-view">
                            <div><b>Broker Details</b></div>
                            <div><?= $broker->first_name . ' ' . $broker->last_name; ?></div>
                            <div>Aadhar No: <?= $broker->aadhar_number; ?></div>
                            <div>Phone No: <?= str_pad($broker->mobile_number, 10, 'X', STR_PAD_LEFT); ?></div>
                            <a href="<?= base_url('user-profile.php?id=' . $item->broker_id); ?>" class="btn btn-warning btn-xs">View Profile</a>
                        </div>
                    </div>
                </div>
            <?php
            } ?>
        </div>
    </div>
    <div class="mb-3">
        <?= $item->details; ?>
    </div>
    <div class="border bg-white rounded mb-2" id="details">
        <div class="row">
            <div class="col-sm-6">
                <div class="p-3">
                    <h6>Land/Plot Details</h6>
                    <?php
                    if ($item->property_for == "Sell") {

                    ?>
                        <div class="table-responsive">
                            <table class="table table-border">
                                <tbody>
                                    <tr>
                                        <td>Mauja</td>
                                        <td><?= $item->mauja; ?></td>
                                        <td>Jamabandi No</td>
                                        <td><?= $item->jamabandi_no; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Page No</td>
                                        <td><?= $item->page_no; ?></td>
                                        <td>Thana</td>
                                        <td><?= $item->thana_no; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Khata No</td>
                                        <td><?= $item->khata_no; ?></td>
                                        <td>Khesra No</td>
                                        <td><?= $item->khesra_no; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td><?= $item->address; ?></td>
                                        <td>Download Rasid</td>
                                        <td><?php
                                            if ($item->rasid_photo != '') {
                                            ?>
                                                <a download href="<?= base_url(upload_dir($item->rasid_photo)); ?>">Click here</a>
                                            <?php
                                            } else {
                                                echo 'Not Available';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php }
                    if ($item->property_for == "Rent") {
                    ?>
                        <div>
                            <b>Property Type - <?= $item->property_type; ?></b>

                        </div>
                    <?php } ?>
                </div>

            </div>
            <div class="col-sm-6">
                <form action="" method="post">
                    <div class="clearfix p-3">
                        <h6>Send your enquiry</h6>
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <input class="form-control" name="form[first_name]" required placeholder="First name *" />
                            </div>
                            <div class="col-sm-6">
                                <input class="form-control" name="form[last_name]" required placeholder="Last name *" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="form[mobile]" maxlength="10" required placeholder="Mobile number *" id="mobileNumber" onkeyup="validateMobile()" />
                            </div>
                            <div class="col-sm-6">
                                <input type="email" required class="form-control" name="form[email_id]" placeholder="Email Id (Optional)" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <textarea rows="3" name="form[details]" class="form-control" placeholder="Details *"></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="btnleads" value="Send" />
                        <button class="btn btn-primary btn-submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="clearfix bg-white p-2 rounded-1 mb-3">
        <div class="w-100" style="height: 400px; width: 100%;" id="mapview"></div>

    </div>
    <div class="mb-3">
        <a target="_blank" class="btn btn-sm btn-primary" href="https://www.google.com/maps/dir/?api=1&destination=<?= urlencode($item->address); ?>">Get Direction</a>
    </div>

    <!-- <a href="#" data-bs-toggle="modal" data-bs-target="#property">Property</a> -->
    <div class="modal fade" id="property" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-center">
                    <h1 class="modal-title fs-5 fw-bold" id="staticBackdropLabel">क्या आप बिहार में जमीन खरीदने की सोच रहे है। </h1>
                </div>
                <div class="modal-body">
                    <p class="text-center mb-4">अब अपने पसंदीदा जिले में जमीन खरीदने का सपना पूरा करें। अपनी जानकारी दर्ज करें और हम आपकी मदद करेंगे।</p>
                    <form action="property-query.php" method="post">
                        <div class="form-group mb-3">
                            <label for="district">District / जिला</label>
                            <select class="form-control" id="district" name="form[dist]">
                                <option>Select District / जिला चुनें</option>
                                <?php
                                $dists = $db->select('ai_districts', [], false, 'dist_name ASC')->result();
                                foreach ($dists as $item1) {
                                ?>
                                    <option value="<?= $item1->dist_name; ?>"><?= $item1->dist_name; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">Name / नाम</label>
                            <input type="text" class="form-control" id="name" name="form[name]" placeholder="Enter your name / अपना नाम लिखें">
                        </div>
                        <div class="form-group mb-3">
                            <label for="contact">Contact Number / संपर्क नंबर</label>
                            <input type="number" maxlength="10" class="form-control" id="contact" name="form[mobile]" placeholder="Enter your number / अपना नंबर लिखें">
                        </div>
                        <div class="form-group mb-3">
                            <label for="query">Query / प्रश्न</label>
                            <textarea class="form-control" id="query" rows="3" name="form[query]" placeholder="Enter your query / अपना प्रश्न लिखें"></textarea>
                        </div>
                        <input type="hidden" name="submit" value="1">
                        <button type="submit" class="btn btn-primary btn-block">Submit / सबमिट करें</button>
                    </form>
                </div>

            </div>
        </div>
    </div>



</div>
<?php
include "common/footer.php";
?>
<script>
    function validateMobile() {
        const inputField = document.getElementById('mobileNumber');
        inputField.addEventListener('input', function() {
            // Replace any non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(function() {
            var myModal = new bootstrap.Modal(document.getElementById("property"));
            myModal.show();
        }, 30000);
    });
</script>
<?php
if ($item->lat != '' && $item->lng != '') {
?>
    <script async src=" https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_API_KEY ?>&loading=async&callback=initMap">
    </script>
    <script>
        // function mapDraw(latitude, longitude) {
        //     var myLatlng = new google.maps.LatLng(latitude, longitude);
        //     var myOptions = {
        //         zoom: 14,
        //         center: myLatlng,
        //         mapTypeId: google.maps.MapTypeId.ROADMAP
        //     }
        //     var map = new google.maps.Map(document.getElementById("mapview"), myOptions);
        // }

        function initMap() {
            loadDragableMap(<?= $item->lat; ?>, <?= $item->lng; ?>);
        }

        async function loadDragableMap(latitude, longitude) {
            // Request needed libraries.
            const {
                Map,
                InfoWindow
            } = await google.maps.importLibrary("maps");
            const {
                AdvancedMarkerElement
            } = await google.maps.importLibrary("marker");
            const map = new Map(document.getElementById("mapview"), {
                center: {
                    lat: latitude,
                    lng: longitude
                },
                zoom: 14,
                mapId: "4504f8b37365c3d0",
            });
            const infoWindow = new InfoWindow();
            const draggableMarker = new AdvancedMarkerElement({
                map,
                position: {
                    lat: latitude,
                    lng: longitude
                },
                gmpDraggable: false,
                title: "",
            });
        }
    </script>
<?php
}
?>