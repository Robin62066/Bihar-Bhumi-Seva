<?php
include "config/autoload.php";
$id = isset($_GET['id']) ? $_GET['id'] : null;
$user = db_user($id);
if ($user == null) {
    redirect(site_url());
}
$services = $db->select('ai_services', ['user_id' => $id, 'status' => 1])->result();
$userDetails = $db->select('ai_profiles', ['user_id' => $id])->row();

$item = $db->select('ai_users', ['id' => $id], 1)->row();
$profile = $db->select('ai_profiles', ['user_id' => $id], 1)->row();
$posts = $db->select('ai_posts', ['user_id' => $id],)->result();

$isLabour = false;
$labour = $db->select('ai_labours', ['user_id' => $id], 1)->row();
if (is_object($labour)) {
    $isLabour = true;
}

include "common/header.php";
?>
<style>
    #lname {
        padding-top: 20px;
    }

    @media only screen and (max-width: 767px) {

        #lname {
            width: 54%;
            position: relative;
            bottom: 36px;
            left: 150px;
        }

        #social-icons-forProfile {
            position: relative;
            left: 149px;
            bottom: 45px;

        }

        .logo {
            width: 100px;
            height: 100px;
            position: relative;

        }

        .details {
            position: relative;
            bottom: 49px;
            padding: 4px 9px;
            font-size: 12px;
            left: 71px;
        }


        .small {
            height: 126px;
        }

        .add-post {
            padding: 5px 20px;
            font-size: 11px;
        }

        .class-1 {
            border: 1px solid gray;
            border-radius: 5px;
            width: 80%;
            margin-left: 40px;
        }


        .post-img {
            height: 200px;
            border-radius: 5px;
            width: 100%;
            margin-top: 10px;
        }

        .backimg {
            height: 74px;
        }

        .p-logo {
            position: relative;
            right: 105px;
            top: 29px;
        }
    }
</style>
<div id="origin" class="container my-3">
    <div class="row">

        <div class="col-sm-12">
            <div class="user-info bg-white p-3 mb-3">
                <div class="page-header">
                    <div class="d-flex gap-2">
                        <a
                            @click="setDetail('personal')"
                            :class="detail === 'personal' ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-info'">
                            Personal Details
                        </a>

                        <a
                            @click="setDetail('business')"
                            :class="detail === 'business' ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-info'">
                            Business Details
                        </a>
                        <a
                            href="#properties"
                            class="btn btn-sm btn-info">
                            Property List
                        </a>
                    </div>
                </div>
                <div v-if="detail==='personal'">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="user-info bg-white p-3">
                                <div class="text-center">
                                    <img src="<?= base_url(upload_dir($user->image)) ?>" class="img-fluid w-100" />
                                </div>
                                <div class="text-center bg-light p-2">
                                    <h5 class="text-success m-0 text-uppercase"><?= $user->name; ?></h5>
                                </div>
                            </div>
                            <div>
                                <?php
                                if ($user->whatsapp != '') {
                                ?>
                                    <a href="https://api.whatsapp.com/send?phone=91<?= $user->whatsapp; ?>" class="btn btn-xs btn-success"> <i class="bi-whatsapp"></i> Chat</a>
                                <?php
                                }
                                ?>
                                <a href="tel:<?= $user->mobile_number; ?>" class="btn btn-xs btn-primary"> <i class="bi-telephone"></i> Call</a>
                                <button data-copy="<?= base_url('user-profile.php?id=' . $user->id); ?>" class="btn btn-xs btn-warning btn-copy"> <i class="bi-share"></i> Share</button>
                                <!-- <a href="<?= base_url('user-profile.php?id=' . $user->id . '&download=1'); ?>" class="btn btn-xs btn-dark"> <i class="bi-download"></i> Download</a> -->
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td style="width: 160px;">Profile Id</td>
                                        <td>#<?= "B" . str_pad($user->id, 12, '0', STR_PAD_LEFT); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Name</td>
                                        <td><?= $user->name; ?> <?= $user->kyc_status == 1 ? '<span class="bi-patch-check text-success"></span>' : ''; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td><?= $user->address; ?></td>
                                    </tr>
                                    <tr>
                                        <td>City</td>
                                        <td><?= $user->city; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Phone No</td>
                                        <td><?= $user->mobile_number; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Email Id</td>
                                        <td><?= $user->email_id; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Aadhar Number</td>
                                        <td><?= $user->aadhar_number; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Member Since</td>
                                        <td><?= date('jS M Y', strtotime($user->created)); ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                    </div>
                </div>

                <div v-if="detail==='business'">
                    <?php if ($userDetails) { ?>
                        <div class="row">
                            <div class="col-sm-12 ">
                                <?= front_view('common/alert'); ?>
                                <div>
                                    <div class="">
                                        <?php
                                        if ($profile && $profile->cover) {
                                        ?>
                                            <div class="bg-light">
                                                <img src="<?= base_url(upload_dir($profile->cover)); ?>" alt="" class="backimg" height="200" width="100%" style="object-fit: cover;">
                                            </div>
                                        <?php
                                        } else {
                                        ?>
                                            <div class="bg-light" style="height: 200px;">

                                            </div>
                                        <?php
                                        }
                                        ?>

                                    </div>
                                    <div class="bg-white shadow-sm p-1 rounded-sm " id="app">
                                        <div class="row small">
                                            <div class="col-sm-2 col-md-2" style="position: relative; margin-top: -60px;">
                                                <div class="p-logo d-flex justify-content-center">
                                                    <?php
                                                    if ($profile->logo != '') {
                                                    ?>
                                                        <img src="<?= base_url(upload_dir($profile->logo)); ?>" alt="" class="logo" style="border-radius: 50%; background: #fff;" height="120" width="120" />
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <img src="<?= theme_url('default.png'); ?>" alt="" style="border-radius: 50%; background: #fff;" height="120" width="120" />
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-8 col-md-10" style="position: relative;">
                                                <h5 id="lname"><?= $profile->legal_name; ?></h5>
                                                <div class="mb-4">
                                                    <div class="social-icons fs-5">
                                                        <a id="social-icons-forProfile" href="<?= $profile->fb_link; ?>" target="_blank"><i class="bi-facebook"></i> </a>
                                                        <a id="social-icons-forProfile" href="<?= $profile->tw_link; ?>" target="_blank"><i class="bi-twitter text-info"></i> </a>
                                                        <a id="social-icons-forProfile" href="<?= $profile->insta_link; ?>" target="_blank"><i class="bi-instagram text-danger"></i> </a>
                                                        <a id="social-icons-forProfile" href="<?= $profile->yt_link; ?>" target="_blank"><i class="bi-youtube text-danger"></i> </a>
                                                    </div>
                                                    <div class="d-none d-sm-block">
                                                        <?php
                                                        if ($user->whatsapp != '') {
                                                        ?>
                                                            <a href="https://api.whatsapp.com/send?phone=91<?= $user->whatsapp; ?>" class="btn btn-xs btn-success"> <i class="bi-whatsapp"></i> Chat</a>
                                                        <?php
                                                        }
                                                        ?>
                                                        <a href="tel:<?= $user->mobile_number; ?>" class="btn btn-xs btn-primary"> <i class="bi-telephone"></i> Call</a>
                                                        <button data-copy="<?= base_url('user-profile.php?id=' . $user->id); ?>" class="btn btn-xs btn-warning btn-copy"> <i class="bi-share"></i> Share</button>
                                                        <!-- <a href="<?= base_url('user-profile.php?id=' . $user->id . '&download=1'); ?>" class="btn btn-xs btn-dark"> <i class="bi-download"></i> Download</a> -->
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-2 d-none d-sm-block">
                                                    <button @click="postServicefun" class="btn btn-success details">
                                                        <span>Posts</span>
                                                    </button>
                                                    <button @click="contactUs" class="btn btn-success details">
                                                        <span>Contact Us</span>
                                                    </button>
                                                    <button @click="aboutfun" class="btn btn-success details">
                                                        <span>About Us</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-block d-sm-none text-center" style="margin-top: -35px;">
                                            <div class="mb-2">
                                                <?php
                                                if ($user->whatsapp != '') {
                                                ?>
                                                    <a href="https://api.whatsapp.com/send?phone=91<?= $user->whatsapp; ?>" class="btn btn-xs btn-success"> <i class="bi-whatsapp"></i> Chat</a>
                                                <?php
                                                }
                                                ?>
                                                <a href="tel:<?= $user->mobile_number; ?>" class="btn btn-xs btn-primary"> <i class="bi-telephone"></i> Call</a>
                                                <button data-copy="<?= base_url('user-profile.php?id=' . $user->id); ?>" class="btn btn-xs btn-warning btn-copy"> <i class="bi-share"></i> Share</button>
                                                <!-- <a href="<?= base_url('user-profile.php?id=' . $user->id . '&download=1'); ?>" class="btn btn-xs btn-dark"> <i class="bi-download"></i> Download</a> -->
                                            </div>
                                            <div class="">
                                                <button @click="postServicefun" class="btn  btn-success btn-sm">
                                                    <span>Post Services</span>
                                                </button>
                                                <button @click="contactUs" class="btn btn-success btn-sm mx-2">
                                                    <span>Contact Us</span>
                                                </button>
                                                <button @click="aboutfun" class="btn btn-success btn-sm">
                                                    <span>About Us</span>
                                                </button>
                                            </div>
                                        </div>
                                        <hr />
                                        <div v-if="contact">
                                            <div class="container my-5">
                                                <div class="card shadow-sm">
                                                    <div class="card-body">
                                                        <div class="d-flex flex-column gap-3">
                                                            <div class="list-group">
                                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <strong>Profile Id:</strong>
                                                                    <span class="text-muted"><?= $id ?></span>
                                                                </div>
                                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <strong>Email ID:</strong>
                                                                    <span class="text-muted"><?= $profile->email_id ?></span>
                                                                </div>
                                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <strong>Mobile No.:</strong>
                                                                    <span class="text-muted"><?= $profile->mobile ?></span>
                                                                </div>
                                                                <?php if ($profile->mobile2 != null) { ?>
                                                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <strong>Whatsapp No. :</strong>
                                                                        <span class="text-muted"><?= $profile->mobile2 ?></span>
                                                                    </div>
                                                                <?php } else {
                                                                ?>
                                                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <strong>GSTIN/CIN/PAN Number :</strong>
                                                                        <span class="text-muted"><?= $profile->gstin ?></span>
                                                                    </div>
                                                                <?php }
                                                                ?>
                                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <strong>Business Address:</strong>
                                                                    <span class="text-muted"><?= $profile->address ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-if="postService" class="container my-5">
                                            <div class="page-header">
                                                <h5>All Posts</h5>
                                            </div>
                                            <?php
                                            foreach ($posts as $post) {
                                            ?>
                                                <div class="row g-2">
                                                    <div class="col-sm-3 col-md-4">
                                                        <?php
                                                        if ($post->image != '') {
                                                        ?>
                                                            <img src="<?= base_url(upload_dir($post->image)) ?>" width="200" class="img-fluid rounded-2 w-100" />
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <img src="<?= image_not_found(); ?>" width="200" class="img-fluid rounded-2 w-100" />
                                                        <?php
                                                        }
                                                        ?>

                                                    </div>
                                                    <div class="col-sm-9 col-md-8">
                                                        <p class=" mb-1"><b><?= $post->post_title ?></b></p>
                                                        <div><?= $post->description ?></div>
                                                    </div>
                                                </div>
                                                <hr />
                                            <?php
                                            }
                                            ?>
                                        </div>
                                        <div v-if="about" class="container my-5">
                                            <div class="card shadow-sm">
                                                <div class="card-body">
                                                    <form action="" method="post">
                                                        <!-- About Text Textarea -->
                                                        <div class="mb-3">
                                                            <h6><b>About <?= $profile->legal_name; ?></b></h6>
                                                            <div><?= $profile->about_text; ?></div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-danger">Business Details Not Found !</div>
                    <?php } ?>
                </div>
                <div v-if="detail==='labours'">
                    <?php
                    if (is_object($labour)) {
                    ?>
                        <div class="row">
                            <div class="col-sm-12 ">
                                <?= front_view('common/alert'); ?>
                                <div>
                                    <div class="">
                                        <div class="bg-light">
                                            <img src="<?= base_url(upload_dir($profile->cover)); ?>" alt="" class="backimg" height="200" width="100%" style="object-fit: cover;" onerror="this.src='<?= base_url('assets/front/banner.jpg'); ?>'" />
                                        </div>
                                    </div>
                                    <div class="bg-white shadow-sm p-1 rounded-sm " id="app">
                                        <div class="row small">
                                            <div class="col-sm-2 col-md-2" style="position: relative; margin-top: -60px;">
                                                <div class="p-logo d-flex justify-content-center">
                                                    <?php
                                                    if ($labour->logo != '') {
                                                    ?>
                                                        <img src="<?= base_url(upload_dir($labour->logo)); ?>" alt="" class="logo bg-light" style="border-radius: 100%;" height="140" width="140" />
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <img src="<?= theme_url('img/logo.png'); ?>" alt="" class="profile-img-circle bg-light" height="140" width="140" />
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-8 col-md-10" style="position: relative;">
                                                <h5 id="lname"><?= strtoupper($labour->first_name . ' ' . $labour->last_name); ?></h5>
                                                <div class="mb-4">
                                                    <div class="social-icons fs-5">
                                                        <a id="social-icons-forProfile" href="<?= $profile->fb_link; ?>" target="_blank"><i class="bi-facebook"></i> </a>
                                                        <a id="social-icons-forProfile" href="<?= $profile->tw_link; ?>" target="_blank"><i class="bi-twitter text-info"></i> </a>
                                                        <a id="social-icons-forProfile" href="<?= $profile->insta_link; ?>" target="_blank"><i class="bi-instagram text-danger"></i> </a>
                                                        <a id="social-icons-forProfile" href="<?= $profile->yt_link; ?>" target="_blank"><i class="bi-youtube text-danger"></i> </a>
                                                    </div>
                                                    <div class="d-none d-sm-block">
                                                        <?php
                                                        if ($user->whatsapp != '') {
                                                        ?>
                                                            <a href="https://api.whatsapp.com/send?phone=91<?= $user->whatsapp; ?>" class="btn btn-xs btn-success"> <i class="bi-whatsapp"></i> Chat</a>
                                                        <?php
                                                        }
                                                        ?>
                                                        <a href="tel:<?= $user->mobile_number; ?>" class="btn btn-xs btn-primary"> <i class="bi-telephone"></i> Call</a>
                                                        <button data-copy="<?= base_url('user-profile.php?id=' . $user->id); ?>" class="btn btn-xs btn-warning btn-copy"> <i class="bi-share"></i> Share</button>
                                                        <!-- <a href="<?= base_url('user-profile.php?id=' . $user->id . '&download=1'); ?>" class="btn btn-xs btn-dark"> <i class="bi-download"></i> Download</a> -->
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-2 d-none d-sm-block">
                                                    <button @click="postServicefun" class="btn btn-success details">
                                                        <span>Post Services</span>
                                                    </button>
                                                    <button @click="contactUs" class="btn btn-success details">
                                                        <span>Contact Us</span>
                                                    </button>
                                                    <button @click="aboutfun" class="btn btn-success details">
                                                        <span>About Us</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-block d-sm-none text-center" style="margin-top: -35px;">
                                            <div class="mb-2">
                                                <?php
                                                if ($user->whatsapp != '') {
                                                ?>
                                                    <a href="https://api.whatsapp.com/send?phone=91<?= $user->whatsapp; ?>" class="btn btn-xs btn-success"> <i class="bi-whatsapp"></i> Chat</a>
                                                <?php
                                                }
                                                ?>
                                                <a href="tel:<?= $user->mobile_number; ?>" class="btn btn-xs btn-primary"> <i class="bi-telephone"></i> Call</a>
                                                <button data-copy="<?= base_url('user-profile.php?id=' . $user->id); ?>" class="btn btn-xs btn-warning btn-copy"> <i class="bi-share"></i> Share</button>
                                                <!-- <a href="<?= base_url('user-profile.php?id=' . $user->id . '&download=1'); ?>" class="btn btn-xs btn-dark"> <i class="bi-download"></i> Download</a> -->
                                            </div>
                                            <div class="">
                                                <button @click="postServicefun" class="btn  btn-success btn-sm">
                                                    <span>Post Services</span>
                                                </button>
                                                <button @click="contactUs" class="btn btn-success btn-sm mx-2">
                                                    <span>Contact Us</span>
                                                </button>
                                                <button @click="aboutfun" class="btn btn-success btn-sm">
                                                    <span>About Us</span>
                                                </button>
                                            </div>
                                        </div>
                                        <hr />
                                        <div v-if="contact">
                                            <div class="container my-5">
                                                <div class="card shadow-sm">
                                                    <div class="card-body">
                                                        <div class="d-flex flex-column gap-3">
                                                            <div class="list-group">
                                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <strong>Profile Id:</strong>
                                                                    <span class="text-muted"><?= $id ?></span>
                                                                </div>
                                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <strong>Email ID:</strong>
                                                                    <span class="text-muted"><?= $profile->email_id ?></span>
                                                                </div>
                                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <strong>Mobile No.:</strong>
                                                                    <span class="text-muted"><?= $profile->mobile ?></span>
                                                                </div>
                                                                <?php if ($profile->mobile2 != null) { ?>
                                                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <strong>Whatsapp No. :</strong>
                                                                        <span class="text-muted"><?= $profile->mobile2 ?></span>
                                                                    </div>
                                                                <?php } else {
                                                                ?>
                                                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <strong>GSTIN/CIN/PAN Number :</strong>
                                                                        <span class="text-muted"><?= $profile->gstin ?></span>
                                                                    </div>
                                                                <?php }
                                                                ?>
                                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <strong>Business Address:</strong>
                                                                    <span class="text-muted"><?= $profile->address ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-if="postService" class="container my-5">
                                            <div class="page-header">
                                                <h5>All Posts</h5>
                                            </div>
                                            <?php
                                            foreach ($posts as $post) {
                                            ?>
                                                <div class="row g-2">
                                                    <div class="col-sm-3 col-md-4">
                                                        <?php
                                                        if ($post->image != '') {
                                                        ?>
                                                            <img src="<?= base_url(upload_dir($post->image)) ?>" width="200" class="img-fluid rounded-2 w-100" />
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <img src="<?= image_not_found(); ?>" width="200" class="img-fluid rounded-2 w-100" />
                                                        <?php
                                                        }
                                                        ?>

                                                    </div>
                                                    <div class="col-sm-9 col-md-8">
                                                        <p class=" mb-1"><b><?= $post->post_title ?></b></p>
                                                        <div><?= $post->description ?></div>
                                                    </div>
                                                </div>
                                                <hr />
                                            <?php
                                            }
                                            ?>
                                        </div>
                                        <div v-if="about" class="container my-5">
                                            <div class="card shadow-sm">
                                                <div class="card-body">
                                                    <form action="" method="post">
                                                        <!-- About Text Textarea -->
                                                        <div class="mb-3">
                                                            <h6><b>About <?= $profile->legal_name; ?></b></h6>
                                                            <div><?= $profile->about_text; ?></div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div id="properties" class="bg-white p-3 mb-3">
                <div class="page-header mb-0">
                    <h5>Properties</h5>
                </div>
                <div class="py-2">
                    <?php
                    $sites = $db->select('ai_sites', ['user_id' => $id, 'status' => 1])->result();
                    if (count($sites) > 0) {
                        foreach ($sites as $item) {
                    ?>
                            <div class="row">
                                <div class="col-sm-3 col-md-4">
                                    <div class="property-list-thumbnail">
                                        <a href="<?= base_url('property-view.php?id=' . $item->id); ?>">
                                            <img src="<?= base_url(upload_dir($item->photo_front)) ?>" alt="" class="img-fluid w-100 rounded-2">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-sm-9 col-md-8">
                                    <div class="p-2 d-flex justify-content-between flex-column h-100">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h1 class="h5 mb-0 site-title">
                                                    <a href="<?= base_url('property-view.php?id=' . $item->id); ?>">
                                                        <?= $item->site_title; ?>
                                                    </a>
                                                </h1>
                                                <div class="text-muted mb-2"> <i class="bi-home"></i> <?= $item->address; ?></div>
                                                <div><?= $item->details; ?></div>
                                            </div>
                                        </div>
                                        <div class="property-info">
                                            <div> <span class="text-danger"> <i class="bi-geo-alt"></i> </span> <?= $item->address; ?></div>
                                            <div class="my-1"> <span class="text-danger"> <i class="bi-currency-rupee"></i> </span> <?= $item->total_amount; ?> - <?= $item->total_area . ' ' . $item->area_unit; ?></div>
                                            <div> <span class="text-danger"> <i class="bi-telephone"></i> </span>
                                                <a href="tel:<?= $user->mobile_number; ?>" class="text-danger"><small><?= $user->mobile_number; ?></small></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    } else {
                        ?>
                        <div class="alert alert-danger">No Service Details Found</div>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="bg-white p-3 mb-3">
                <div class="page-header mb-0">
                    <h5>Services</h5>
                </div>
                <?php
                if (count($services) > 0) {
                    foreach ($services as $item) {
                        $arr_loc = [];
                        if ($item->locations != '') $arr_loc = json_decode($item->locations);
                        $cities = '';
                        foreach ($arr_loc as $a) {
                            $cities .= '<span class="btn btn-xs btn-outline-primary">' . $a->city . '</span> ';
                        }
                ?>
                        <div>
                            <div class="border-bottom py-3">
                                <h6 class="text-primary"><?= $item->service_name; ?></h6>
                                <?= $item->details; ?>
                            </div>
                            <div class="pt-3">
                                Locations: <?= $cities; ?>
                            </div>
                        </div>
                    <?php
                    }
                } else {
                    ?>
                    <div class="alert alert-danger">No Service Details Found</div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
include "common/footer.php";
?>
<script>
    new Vue({
        el: '#origin',
        data: {
            detail: "personal",
            about: false,
            postService: false,
            contact: false,
        },
        methods: {
            setDetail(selectedDetail) {
                this.detail = selectedDetail;
                if (selectedDetail === 'business') {
                    this.postServicefun();
                }
            },
            aboutfun: function() {
                this.about = true;
                this.postService = false;
                this.contact = false;
            },
            postServicefun: function() {
                this.postService = true;
                this.contact = false;
                this.about = false;
            },
            contactUs: function() {
                this.contact = true;
                this.postService = false;
                this.about = false;
            },
        }
    })
</script>

<script>
    document.getElementById("fileInput").onchange = function() {
        document.getElementById("form2").submit();
    };
</script>