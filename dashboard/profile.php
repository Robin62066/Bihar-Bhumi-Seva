<?php
require('../config/autoload.php');
if (!is_login()) redirect(base_url('login.php'), 'You must login to continue');
$user_id = user_id();

$isEditing = false;
if (isset($_GET['edit'])) {
    $edit_id = (int) $_GET['edit'];
    $isEditing = true;
    $editItem = $db->select('ai_posts', ['id' => $edit_id], 1)->row();
}

if (isset($_GET['delete'])) {
    $del_id = (int) $_GET['delete'];
    $db->delete('ai_posts', ['id' => $del_id], 1);
    redirect(site_url('dashboard/profile.php'), 'Post deleted successfully', 'success');
}

if (isset($_POST['submited'])) {
    if ($isEditing) {
        $form = $_POST['form'];
        $form['user_id'] = $user_id;
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
            $form['image'] = do_upload('image');
        }
        $db->update('ai_posts', $form, ['id' => $edit_id]);
        session()->set_flashdata('success', 'Post successfully updated');
        $isEditing = false;
    } else {
        $form = $_POST['form'];
        $form['user_id'] = $user_id;
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
            $form['image'] = do_upload('image');
        }
        $form['created'] = date('Y-m-d H:i:s');
        $db->insert('ai_posts', $form);
        session()->set_flashdata('success', 'Poat Successfully Created');
    }
    redirect(site_url('dashboard/profile.php'));
}

if (isset($_POST['aboutSubmit'])) {
    $form = $_POST['form'];
    $form['id'] = $user_id;
    $db->update('ai_profiles', $form, ['user_id' => $user_id]);
    session()->set_flashdata('success', 'about information successfully saved');
}

if (isset($_POST['btn_cover'])) {
    $form = [];
    if (isset($_FILES['cover']['name']) && $_FILES['cover']['name'] != '') {
        $form['cover'] = do_upload('cover');
    }

    $db->update('ai_profiles', $form, ['user_id' => $user_id]);
    session()->set_flashdata('success', 'Cover image Successfully saved');
}

$item = $db->select('ai_users', ['id' => $user_id], 1)->row();
$profile = $db->select('ai_profiles', ['user_id' => $user_id], 1)->row();
$posts = $db->select('ai_posts', ['user_id' => $user_id],)->result();

include "../common/header.php";
?>


<style>
    .upload {
        position: relative;
        bottom: 185px;
        left: 610px;
    }

    .p-heading {
        padding-top: 20px;
    }

    #lname {
        padding-top: 20px;
    }

    @media only screen and (max-width: 767px) {
        .upload {
            position: relative;
            bottom: 184px;
            left: 220px;

        }

        #lname {
            width: 50%;
            position: relative;
            bottom: 42px;
            left: 180px;
        }

        #social-icons-forProfile {
            position: relative;
            left: 186px;
            bottom: 50px;

        }

        .logo {
            width: 100px;
            height: 100px;
            position: relative;
            right: 100px;

        }

        /* 
        .follow {
            padding-left: 200px;
            position: relative;
            bottom: 150px;

        } */

        .edit {
            position: relative;
            left: 300px;
            bottom: 172px;
        }

        .details {
            position: relative;
            bottom: 63px;
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

        .eidt-details {
            padding: 5px 10px;
        }


    }
</style>

<div id="origin1" class="dashboard">
    <div class="container py-5">
        <div class="user-panel">
            <div class="row">
                <?php
                $subpage = 'business';
                include_once "dashboard-menu.php"; ?>
                <div class="col-sm-9 ">
                    <?= front_view('common/alert'); ?>
                    <div>
                        <div class="profile-cover">
                            <form id="form2" action="" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="btn_cover" value="1">
                                <div class="bg-light">
                                    <img src="<?= base_url(upload_dir($profile->cover));   ?>" alt="" class="backimg" height="200" width="100%" style="object-fit: cover;">
                                    <label class="upload" for="fileInput" class="btn btn-upload">
                                        <i class="bi bi-upload"></i> Upload Image
                                    </label>
                                    <input class="upload" type="file" id="fileInput" accept="image/*" name="cover" style="display: none;" />
                                </div>
                            </form>
                        </div>
                        <div class="bg-white shadow-sm p-1 rounded-sm " id="app">
                            <div class="row small">
                                <div class="col-sm-3" style="position: relative; margin-top: -60px;">
                                    <div class="p-logo d-flex justify-content-center">
                                        <?php
                                        if ($profile->logo != '') {
                                        ?>
                                            <img src="<?= base_url(upload_dir($profile->logo)); ?>" alt="" class="logo" style="border-radius: 50%;" height="120" width="120" />
                                        <?php
                                        } else {
                                        ?>
                                            <img src="<?= theme_url('img/logo.png'); ?>" alt="" class="profile-img-circle" height="120" width="120" />
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-7" style="position: relative; right:20px;">
                                    <h5 id="lname"><?= $profile->legal_name; ?></h5>
                                    <div class="mb-4">
                                        <div class="social-icons fs-5">
                                            <a id="social-icons-forProfile" href="<?= $profile->fb_link; ?>" target="_blank"><i class="bi-facebook"></i> </a>
                                            <a id="social-icons-forProfile" href="<?= $profile->tw_link; ?>" target="_blank"><i class="bi-twitter text-info"></i> </a>
                                            <a id="social-icons-forProfile" href="<?= $profile->insta_link; ?>" target="_blank"><i class="bi-instagram text-danger"></i> </a>
                                            <a id="social-icons-forProfile" href="<?= $profile->yt_link; ?>" target="_blank"><i class="bi-youtube text-danger"></i> </a>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
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
                                <div class="col-sm-2 ">
                                    <div class="p-heading">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="<?= site_url('dashboard/business-about.php') ?>" class="btn btn-xs btn-light edit"><span class="bi-pencil"></span> Edit</a>
                                        </div>
                                    </div>
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
                                                        <span class="text-muted"><?= $user_id ?></span>
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
                                    <h5>Manage Services</h5>
                                    <button type="button" class="btn btn-xs btn-primary add-post" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        Add New Post
                                    </button>
                                </div>
                                <?php
                                foreach ($posts as $post) {
                                ?>
                                    <div class="row gap-2">
                                        <div class="col-sm-2">
                                            <?php
                                            if ($post->image != '') {
                                            ?>
                                                <img src="<?= base_url(upload_dir($post->image)) ?>" class="img-fluid rounded-2" />
                                            <?php
                                            } else {
                                            ?>
                                                <img src="<?= image_not_found(); ?>" class="img-fluid rounded-2" />
                                            <?php
                                            }
                                            ?>

                                        </div>
                                        <div class="col-sm-7">
                                            <p class="mb-1"><b><?= $post->post_title ?></b></p>
                                            <div><?= $post->description ?></div>
                                        </div>
                                        <div class="col-sm-2">
                                            <a href="<?= site_url('dashboard/profile.php?edit=' . $post->id); ?>" class="btn btn-xs btn-info">Edit</a>
                                            <a href="<?= site_url('dashboard/profile.php?delete=' . $post->id); ?>" class="btn btn-xs btn-danger btn-confirm" data-msg="Are you sure to Delete?">Delete</a>
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
                                                <label for="aboutText" class="form-label">About You</label>
                                                <textarea rows="5" name="form[about_text]" class="form-control"><?= $profile->about_text; ?></textarea>
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="d-flex justify-content-center">
                                                <input type="hidden" name="aboutSubmit" value="1">
                                                <button class="btn btn-warning btn-submit">SUBMIT</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="card p-4 shadow-sm">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" required name="form[post_title]" class="form-control" value="<?= $isEditing ? $editItem->post_title : ''; ?>" id="title" placeholder="Enter your title">
                                </div>

                                <!-- Description Input -->
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" required name="form[description]" rows="4"><?= $isEditing ? $editItem->description : ''; ?></textarea>
                                </div>

                                <!-- Image Upload -->
                                <div class="mb-3">
                                    <input type="file" name="image" class="form-control">
                                    <?php
                                    if ($isEditing) {
                                    ?>
                                        <img src="<?= base_url(upload_dir($editItem->image)) ?>" alt="" width="100">
                                    <?php
                                    }
                                    ?>
                                </div>

                                <!-- Submit Button -->
                                <div>
                                    <input type="hidden" name="submited" value="1">
                                    <button class="btn btn-warning btn-submit">SUBMIT</button>
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
    new Vue({
        el: '#origin1',
        data: {
            about: false,
            postService: false,
            contact: false,
            isEditing: <?= $isEditing ? 'true' : 'false'; ?>
        },
        methods: {
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
        },
        created: function() {
            this.postService = true;
            if (this.isEditing) {
                const myModel = new bootstrap.Modal('#exampleModal');
                myModel.show();
            }
        }
    })
</script>
<script>
    document.getElementById("fileInput").onchange = function() {
        document.getElementById("form2").submit();
    };
</script>