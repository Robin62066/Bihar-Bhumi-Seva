<?php
include 'config/autoload.php';
include 'common/header.php';
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
<script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= theme_url('styleone.css') ?>">
<style>
    body {
        font-family: Arial, sans-serif;
    }

    .carousel-item {
        position: relative;
        overflow: hidden;
    }

    .carousel-item img {
        width: 100%;
        object-fit: cover;
    }

    .carousel-caption {
        position: absolute;
        bottom: 150px;
        left: 50%;
        transform: translateX(-0.5%);
        text-align: center;
        background: rgba(0, 0, 0, 0.1);
        border-radius: 0px;
        padding: 10px;
    }

    .cta-button {
        background-color: #28a745;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-size: 18px;
        text-decoration: none;
        transition: background-color 0.3s;
    }

    .cta-button:hover {
        background-color: #218838;
    }

    .carousel-caption {
        display: none;
    }
</style>
<style type="text/css">
    .properties {

        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .header1 {
        text-align: center;
        margin-bottom: 30px;
    }

    .header1 h1 {
        color: #2c3e50;
        font-size: 28px;
    }

    .card {
        width: 100%;
        height: 240px;
        background-color: rgba(31, 165, 143, 0.94);
        color: white;
        padding: 20px;
        margin: 10px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: scale(1.05);
        background-color: #3498db;
    }

    .card i {
        font-size: 40px;
        margin-bottom: 15px;
    }

    .card h3 {
        font-size: 18px;
        margin-bottom: 10px;
    }

    .card p {
        font-size: 14px;
        margin-bottom: 20px;
    }

    .card a {
        font-size: 16px;
        color: #ecf0f1;
        text-decoration: none;
        font-weight: bold;
    }

    .card a:hover {
        color: #f39c12;
    }

    .download {
        color: black;
        font-size: 22px;
        font-weight: 500;
    }

    .seva {
        color: black;
        font-size: 15px;
        padding-top: 30px;

    }

    .now {
        text-decoration: none;
        padding: 20px 50px;
        border-radius: 20px;
        transform-origin: 0% 0%;
        font-size: 20px;
        font-weight: 550;
        font-family: "Inter", sans-serif;
        color: black;
        background:
            linear-gradient(90deg, darkcyan 50%, lightgreen 0) var(--_p, 100%)/200% no-repeat;
        -webkit-background-clip: background;
        background-clip: background;
        transition: .5s;
        position: relative;
        top: 30px;
    }

    .now:hover {
        --_p: 0%;
        border-radius: 3px;
        color: white;
        border-radius: 20px;
    }

    .mob {
        border-radius: 40px;
        height: 300px;
        width: 100%;
    }

    .mob:hover {
        transition: 2s;
        transform: scale(1.2);
    }

    .blog-post {
        background-color: #ffffff;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 20px;
        margin-bottom: 20px;
        transition: box-shadow 0.3s;
    }

    .blog-post:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .blog-title {
        font-size: 24px;
        margin-bottom: 10px;
    }

    .blog-snippet {
        font-size: 16px;
        margin-bottom: 15px;
        color: #555;
    }

    .read-more {
        font-weight: bold;
        color: #28a745;
        text-decoration: none;
    }

    .read-more:hover {
        text-decoration: underline;
    }

    .animated-button {
        padding: 15px 30px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: transform 0.3s ease, background-color 0.3s ease;
    }

    .animated-button:hover {
        transform: scale(1.1);
        background-color: #0056b3;
    }

    @media screen and (max-width: 768px) {
        .card {
            width: 95%;
        }

        .card h3 {
            font-size: 26px;
        }

        .card p {
            font-size: 20px;
        }

        .card a {
            font-size: 18;
        }
    }
</style>
<div class="container pt-2">
    <div class="row g-0">
        <?= front_view('common/home-menu'); ?>
        <div class="col-sm-10">
            <div class="p-2 bg-white">
                <div class="text-danger border border-danger small p-2 rounded d-flex justify-content-between align-items-center gap-2">
                    <div style="flex: 1;">
                        <marquee behavior="" direction="">
                            <span>बिहार भूमि सेवा में आपका स्वागत है। बिहार भूमि सेवा लाया है निःशुल्क Unlimited Services. ✔ प्लॉट
                                लिस्टिंग, ✔ Broker लिस्टिंग, ✔ Amin लिस्टिंग, ✔ Munsi लिस्टिंग. ✔ Online Mutation, ✔ Bhumi Locker, ✔ Builder लिस्टिंग, ✔ Bricks Manufacture, ✔ Sand Suppiers, ✔ निःशुल्क अभियान चलाती है।</span>
                        </marquee>
                    </div>
                    <!-- <div class="audio-play">
                        <button class="btnplay"><i class="bi-volume-mute"></i></button>
                        <audio preload id="audio-id">
                            <source src="assets/audio.mp4" type="audio/mpeg">
                        </audio>
                    </div> -->
                </div>
                <div class="py-3">
                    <div id="carouselExample" class="carousel carousel-dark slide" data-bs-ride="carousel">
                        <div class="carousel-inner">

                            <!-- Slide 1 -->
                            <div class="carousel-item active">
                                <a href="https://www.biharbhumiseva.in/property-list.php">
                                    <img src="image/banners/1.jpg" alt="Slide 1" class="d-block w-100"> <!-- Replace with actual image path -->
                                    <div class="carousel-caption">
                                        <h5>सम्पति सूचि देखें/ Property</h5>
                                        <a href="https://www.biharbhumiseva.in/property-list.php" class="cta-button">जानें अधिक / Learn More</a>
                                    </div>
                                </a>
                            </div>

                            <!-- Slide 2 -->
                            <div class="carousel-item">
                                <a href="https://www.biharbhumiseva.in/users.php?type=labours">
                                    <img src="image/banners/2.jpg" alt="Slide 2" class="d-block w-100">
                                    <div class="carousel-caption">
                                        <h5>मजदूर / Labours</h5>
                                        <a href="https://www.biharbhumiseva.in/users.php?type=labours" class="cta-button">जानें अधिक / Learn More</a>
                                    </div>
                                </a>
                            </div>

                            <!-- Slide 3 -->
                            <div class="carousel-item">
                                <a href="https://www.biharbhumiseva.in/online-mutation.php">
                                    <img src="image/banners/3i.jpg" alt="Slide 3" class="d-block w-100">
                                    <div class="carousel-caption">
                                        <h5>प्म्युटेशन के लिए आवेदन करें / Online Mutation </h5>
                                        <a href="https://www.biharbhumiseva.in/online-mutation.php" class="cta-button">जानें अधिक / Learn More</a>
                                    </div>
                                </a>
                            </div>

                            <!-- Slide 4 -->
                            <div class="carousel-item">
                                <a href="https://www.biharbhumiseva.in/amin-list.php">
                                    <img src="image/banners/4.jpg" alt="Slide 4" class="d-block w-100">
                                    <div class="carousel-caption">
                                        <h5>अमीन सूचि देखें / Amin</h5>
                                        <a href="https://www.biharbhumiseva.in/amin-list.php" class="cta-button">जानें अधिक / Learn More</a>
                                    </div>
                                </a>
                            </div>

                            <!-- Slide 5 -->
                            <div class="carousel-item">
                                <a href="https://www.biharbhumiseva.in/munsee-list.php">
                                    <img src="image/banners/5.jpg" alt="Slide 5" class="d-block w-100">
                                    <div class="carousel-caption">
                                        <h5>मुंसी सूचि देखें / Munsi</h5>
                                        <a href="https://www.biharbhumiseva.in/munsee-list.php" class="cta-button">जानें अधिक / Learn More</a>
                                    </div>
                                </a>
                            </div>

                            <!-- Slide 6 -->
                            <div class="carousel-item">
                                <a href="https://www.biharbhumiseva.in/users.php?type=bricks-mfgs">
                                    <img src="image/banners/6.jpg" alt="Slide 6" class="d-block w-100">
                                    <div class="carousel-caption">
                                        <h5>ब्रिक्स मैन्युफैक्चरर / Mgh Bricks</h5>
                                        <a href="https://www.biharbhumiseva.in/users.php?type=bricks-mfgs" class="cta-button">जानें अधिक / Learn More</a>
                                    </div>
                                </a>
                            </div>

                            <!-- Slide 7 -->
                            <div class="carousel-item">
                                <a href="https://www.biharbhumiseva.in/signup.php">
                                    <img src="image/banners/7.jpg" alt="Slide 7" class="d-block w-100">
                                    <div class="carousel-caption">
                                        <h5>वभू-लॉकर / Bhumi Locker</h5>
                                        <a href="https://www.biharbhumiseva.in/signup.php" class="cta-button">जानें अधिक / Learn More</a>
                                    </div>
                                </a>
                            </div>

                            <!-- Slide 8 -->
                            <div class="carousel-item">
                                <a href="https://www.biharbhumiseva.in/signup.php">
                                    <img src="image/banners/9.jpg" alt="Slide 8" class="d-block w-100">
                                    <div class="carousel-caption">
                                        <h5>ग्रसम्पति को पोस्ट करें / Post Property</h5>
                                        <a href="https://www.biharbhumiseva.in/signup.php" class="cta-button">जानें अधिक / Learn More</a>
                                    </div>
                                </a>
                            </div>

                            <!-- Slide 9 -->
                            <div class="carousel-item">
                                <a href="https://www.biharbhumiseva.in/users.php?type=sand-suppliers">
                                    <img src="image/banners/8.jpg" alt="Slide 9" class="d-block w-100">
                                    <div class="carousel-caption">
                                        <h5>बालू सप्लायर / Sand Dealer</h5>
                                        <a href="https://www.biharbhumiseva.in/users.php?type=sand-suppliers" class="cta-button">जानें अधिक / Learn More</a>
                                    </div>
                                </a>
                            </div>

                            <!-- Slide 10 -->
                            <div class="carousel-item">
                                <a href="https://www.biharbhumiseva.in/brokers.php">
                                    <img src="image/banners/10.jpg" alt="Slide 10" class="d-block w-100">
                                    <div class="carousel-caption">
                                        <h5>ब्रोकर सूचि देखें / Find Broker</h5>
                                        <a href="https://www.biharbhumiseva.in/brokers.php" class="cta-button">जानें अधिक / Learn More</a>
                                    </div>
                                </a>
                            </div>

                            <!-- Slide 11 -->
                            <div class="carousel-item">
                                <a href="https://www.biharbhumiseva.in/users.php?type=building-contructor">
                                    <img src="image/banners/11.jpg" alt="Slide 11" class="d-block w-100">
                                    <div class="carousel-caption">
                                        <h5>ग्राहक सहायता / Construction</h5>
                                        <a href="https://www.biharbhumiseva.in/users.php?type=building-contructor" class="cta-button">जानें अधिक / Learn More</a>
                                    </div>
                                </a>
                            </div>

                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <!-- <div class="text-danger border border-danger small p-2 rounded">
                    
                </div> -->
            </div>

        </div>
    </div>
</div>

<div class="container mt-4">
    <div class="home-icons">
        <div class="circle">
            <a href="sell-property.php"><img src="image/icon-1.jpeg"></a>
            <div>Post Property</div>
        </div>
        <div class="circle">
            <a href="property-list.php"><img src="image/icon-2.jpeg"></a>
            <div>Property</div>
        </div>
        <div class="circle">
            <a href="users.php?type=brokers"><img src="image/icon-3.jpeg"></a>
            <div> Broker</div>
        </div>
        <div class="circle">
            <a href="users.php?type=munsi"><img src="image/icon-4.jpeg"></a>
            <div> Munsi</div>
        </div>
        <div class="circle">
            <a href="users.php?type=amin"><img src="image/icon-5.jpeg"></a>
            <div> Amin</div>
        </div>
        <div class="circle">
            <a href="users.php?type=co"><img src="image/icon-6.jpeg"></a>
            <div> CO</div>
        </div>
    </div>
    <div class="home-icons">
        <div class="circle">
            <a href="dashboard/bhumi-locker.php"><img src="image/icon-7.jpeg"></a>
            <div> BhumiLocker </div>
        </div>
        <div class="circle">
            <a href="online-mutation.php"><img src="image/icon-8.jpeg"></a>
            <div> Mutation</div>
        </div>
        <!-- <div class="circle">
            <a href="complaint.php"><img src="image/icon-9.jpeg"></a>
            <div> Complaint </div>
        </div> -->
        <div class="circle">
            <a href="labours.php"><img src="image/icon-13.jpg"></a>
            <div> Labours </div>
        </div>
        <div class="circle">
            <a href="users.php?type=building-contructor"><img src="image/icon-10.jpg"></a>
            <div> Builders </div>
        </div>
        <div class="circle">
            <a href="users.php?type=bricks-mfgs"><img src="image/icon-11.jpg"></a>
            <div> Bricks Mfgs </div>
        </div>
        <div class="circle">
            <a href="users.php?type=sand-suppliers"><img src="image/icon-12.jpg"></a>
            <div> Sand Supplier </div>
        </div>
        <!-- <div class="circle">
            <a href="#"><img src="image/icon-12.png"></a>
            <div> Others </div>
        </div> -->
    </div>

    <!-- <div class="row mt-5 mb-2 text-center">
        <div class="col-md-3">
            <div class="post">
                <img src="image/plot.png">
                <h3><b>Post Your Property Ads for free in Bihar</b></h3>
                <p><b>Sell/Land Out your Property & Get Unlimited Responses</b></p>
                <a href="sell-property.php" class="btn btn-sm btn-success">List Your Property</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dream">
                <img src="image/plot.png">
                <h3><b>Find Your Dreem Property</b></h3>
                <p>Get the list of porperty<br>Matching to your requirements</p>
                <a href="property-list.php" class="btn btn-sm btn-success">Post Requriment</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="user">
                <img src="image/loker.png">
                <h3><b>50000+ Verified User - Bihar Bhumi Loker</b></h3>
                <a href="signup.php" class="btn btn-sm btn-success">Explor Now</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="online">
                <img src="image/paper.png">
                <h2><b>Online-Mutation</b></h2>
                <p>On Whatsapp</p>
                <a href="online-mutation.php" class="btn btn-sm btn-success">Explor Now</a>
            </div>
        </div>
    </div> -->
    <div class="mt-5">
        <div class="seva text-center">
            <h4>
                <b>Latest Properties for Sale <span>In Bihar</span>
                </b>
            </h4>
        </div>
    </div>
    <?php
    $items = $db->select('ai_sites', ['status' => 1, 'property_for' => 'Sell', 'share_on_home' => '1'], 5)->result();
    if (count($items) > 0) { ?>
        <div class="row mt-3">
            <div class="col-md-12">
                <h5>Property for Sale</h5>
                <div class="slider">
                    <?php
                    foreach ($items as $item) {
                    ?>
                        <div class="photo" style="height: 400px; ">
                            <div class="prop-image">
                                <?php
                                if ($item->photo_front != '') {
                                ?>
                                    <img src="<?= base_url(upload_dir($item->photo_front)) ?>" style="height: 200px; width: 100%" class="img-fluid" />
                                <?php
                                }
                                ?>
                            </div>
                            <div class="tag"><?= $item->total_amount; ?></div>
                            <div class="detail">
                                <div class="text-muted"><?= $item->property_type; ?></div>
                                <h5 class="prop-title"><?= $item->site_title; ?></h5>
                                <div class="mb-2"><?= $item->address; ?></div>
                                <a href="<?= 'property-view.php?id=' . $item->id; ?>" class="btn btn-xs btn-outline-primary">More Detail</a>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="photo" style="height: 400px; display:flex; justify-content:center; align-items:center; background-color: #f0f0f0">
                        <div class="prop-image" style="width: 100%;">
                            <div class="box" style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
                                <a href="<?= site_url("signup.php") ?>" class="btn btn-lg btn-primary animated-button">Post Property</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php
    $items = $db->select('ai_sites', ['status' => 1, 'property_for' => 'Sell', 'share_on_home' => '1'], 5, "id DESC")->result();
    if (count($items) > 0) { ?>
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="slider">
                    <?php
                    foreach ($items as $item) {
                    ?>
                        <div class="photo" style="height: 400px; ">
                            <div class="prop-image">
                                <?php
                                if ($item->photo_front != '') {
                                ?>
                                    <img src="<?= base_url(upload_dir($item->photo_front)) ?>" style="height: 200px; width: 100%" class="img-fluid" />
                                <?php
                                }
                                ?>
                            </div>
                            <div class="tag"><?= $item->total_amount; ?></div>
                            <div class="detail">
                                <div class="text-muted"><?= $item->property_type; ?></div>
                                <h5 class="prop-title"><?= $item->site_title; ?></h5>
                                <div class="mb-2"><?= $item->address; ?></div>
                                <a href="<?= 'property-view.php?id=' . $item->id; ?>" class="btn btn-xs btn-outline-primary">More Detail</a>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="photo" style="height: 400px; display:flex; justify-content:center; align-items:center; background-color: #f0f0f0">
                        <div class="prop-image" style="width: 100%;">
                            <div class="box" style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
                                <a href="<?= site_url("signup.php") ?>" class="btn btn-lg btn-primary animated-button">Post Property</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="mt-5">
        <div class="seva text-center">
            <h4>
                <b>Latest Properties for Rent <span>In Bihar</span></b>
            </h4>
        </div>
    </div>
    <?php
    $items = $db->select('ai_sites', ['status' => 1, 'property_for' => 'Rent', 'share_on_home' => '1'], 5)->result();
    if (count($items) > 0) { ?>

        <div class="row mt-5">
            <div class="col-md-12">
                <h5>Property for Rent</h5>
                <div class="slider">
                    <?php
                    foreach ($items as $item) {
                    ?>
                        <div class="photo" style="height: 400px; ">
                            <div class="prop-image">
                                <?php
                                if ($item->photo_front != '') {
                                ?>
                                    <img src="<?= base_url(upload_dir($item->photo_front)) ?>" style="height: 200px; width: 100%" class="img-fluid" />
                                <?php
                                }
                                ?>
                            </div>
                            <div class="tag"><?= $item->total_amount; ?></div>
                            <div class="detail">
                                <div class="text-muted"><?= $item->property_type; ?></div>
                                <h5 class="prop-title"><?= $item->site_title; ?></h5>
                                <div class="mb-2"><?= $item->address; ?></div>
                                <a href="property-view.php?id=<?= $item->id ?>" class="btn btn-xs btn-outline-primary">More Detail</a>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="photo" style="height: 400px; display:flex; justify-content:center; align-items:center; background-color: #f0f0f0">
                        <div class="prop-image" style="width: 100%;">
                            <div class="box" style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
                                <a href="<?= site_url("signup.php") ?>" class="btn btn-lg btn-primary animated-button">Post Property</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <div>
        <div class="seva text-center">
            <h4>
                <b>Bihar Bhumi Seva - <span>Membership Features</span> </b>
            </h4>
            <p>Select a feature below to know more.</p>

        </div>


        <div>
            <div class="row" align="center">
                <div class="col-sm-3">
                    <div class="card" onclick="window.location.href='https://www.biharbhumiseva.in/blogs/land-registration-%e0%a4%ad%e0%a5%82%e0%a4%ae%e0%a4%bf-%e0%a4%aa%e0%a4%82%e0%a4%9c%e0%a5%80%e0%a4%95%e0%a4%b0%e0%a4%a3/'">
                        <i class="fas fa-map-marker-alt"></i>
                        <h3 class="fw-bold">Land Registration <br> भूमि पंजीकरण</h3>
                        <p>Get access to land registration services with our membership plans.</p>
                        <a href="https://www.biharbhumiseva.in/blogs/land-registration-%e0%a4%ad%e0%a5%82%e0%a4%ae%e0%a4%bf-%e0%a4%aa%e0%a4%82%e0%a4%9c%e0%a5%80%e0%a4%95%e0%a4%b0%e0%a4%a3/">Learn More</a>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card" onclick="window.location.href='https://www.biharbhumiseva.in/blogs/customer-support-%e0%a4%97%e0%a5%8d%e0%a4%b0%e0%a4%be%e0%a4%b9%e0%a4%95-%e0%a4%b8%e0%a4%b9%e0%a4%be%e0%a4%af%e0%a4%a4%e0%a4%be/'">
                        <i class="fas fa-users"></i>
                        <h3 class="fw-bold">Customer Support <br> ग्राहक सहायता</h3>
                        <p>24/7 customer support to resolve all your queries.</p>
                        <a class="pt-3" href="https://www.biharbhumiseva.in/blogs/customer-support-%e0%a4%97%e0%a5%8d%e0%a4%b0%e0%a4%be%e0%a4%b9%e0%a4%95-%e0%a4%b8%e0%a4%b9%e0%a4%be%e0%a4%af%e0%a4%a4%e0%a4%be/">Learn More</a>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card " onclick="window.location.href='https://www.biharbhumiseva.in/blogs/legal-assistance-%e0%a4%95%e0%a4%be%e0%a4%a8%e0%a5%82%e0%a4%a8%e0%a5%80-%e0%a4%b8%e0%a4%b9%e0%a4%be%e0%a4%af%e0%a4%a4%e0%a4%be/'">
                        <i class="fas fa-gavel"></i>
                        <h3 class="fw-bold">Legal Assistance <br> कानूनी सहायता</h3>
                        <p>Access legal advice and assistance for land-related issues.</p>
                        <a class="pt-3" href="https://www.biharbhumiseva.in/blogs/legal-assistance-%e0%a4%95%e0%a4%be%e0%a4%a8%e0%a5%82%e0%a4%a8%e0%a5%80-%e0%a4%b8%e0%a4%b9%e0%a4%be%e0%a4%af%e0%a4%a4%e0%a4%be/">Learn More</a>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card" onclick="window.location.href='https://www.biharbhumiseva.in/blogs/legal-assistance-%e0%a4%95%e0%a4%be%e0%a4%a8%e0%a5%82%e0%a4%a8%e0%a5%80-%e0%a4%b8%e0%a4%b9%e0%a4%be%e0%a4%af%e0%a4%a4%e0%a4%be-2/'">
                        <i class="fas fa-cogs"></i>
                        <h3 class="fw-bold">Online Services <br> ऑनलाइन सेवाएँ</h3>
                        <p>Enjoy easy access to all services online through our portal.</p>
                        <a class="pt-3" href="https://www.biharbhumiseva.in/blogs/legal-assistance-%e0%a4%95%e0%a4%be%e0%a4%a8%e0%a5%82%e0%a4%a8%e0%a5%80-%e0%a4%b8%e0%a4%b9%e0%a4%be%e0%a4%af%e0%a4%a4%e0%a4%be-2/">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="row" align="center">
                <div class="col-sm-3">
                    <div class="card " onclick="window.location.href='https://www.biharbhumiseva.in/blogs/document-verification-%e0%a4%a6%e0%a4%b8%e0%a5%8d%e0%a4%a4%e0%a4%be%e0%a4%b5%e0%a5%87%e0%a4%9c%e0%a4%bc-%e0%a4%b8%e0%a4%a4%e0%a5%8d%e0%a4%af%e0%a4%be%e0%a4%aa%e0%a4%a8/'">
                        <i class="fas fa-id-card"></i>
                        <h3>Document Verification <br> दस्तावेज़ सत्यापन</h3>
                        <p>सत्यापन के लिए हमारे सेवा का लाभ उठाएं और दस्तावेज़ों को सुरक्षित रखें।</p>
                        <a href="https://www.biharbhumiseva.in/blogs/document-verification-%e0%a4%a6%e0%a4%b8%e0%a5%8d%e0%a4%a4%e0%a4%be%e0%a4%b5%e0%a5%87%e0%a4%9c%e0%a4%bc-%e0%a4%b8%e0%a4%a4%e0%a5%8d%e0%a4%af%e0%a4%be%e0%a4%aa%e0%a4%a8/">अधिक जानें</a>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card " onclick="window.location.href='https://www.biharbhumiseva.in/blogs/easy-payment-methods-%e0%a4%86%e0%a4%b8%e0%a4%be%e0%a4%a8-%e0%a4%ad%e0%a5%81%e0%a4%97%e0%a4%a4%e0%a4%be%e0%a4%a8-%e0%a4%b5%e0%a4%bf%e0%a4%a7%e0%a4%bf%e0%a4%af%e0%a4%be%e0%a4%81/'">
                        <i class="fas fa-credit-card"></i>
                        <h3>Easy Payment Methods <br> आसान भुगतान विधियाँ</h3>
                        <p>हमारे विभिन्न भुगतान विकल्पों के साथ लेन-देन को सरल बनाएं।</p>
                        <a href="https://www.biharbhumiseva.in/blogs/easy-payment-methods-%e0%a4%86%e0%a4%b8%e0%a4%be%e0%a4%a8-%e0%a4%ad%e0%a5%81%e0%a4%97%e0%a4%a4%e0%a4%be%e0%a4%a8-%e0%a4%b5%e0%a4%bf%e0%a4%a7%e0%a4%bf%e0%a4%af%e0%a4%be%e0%a4%81/">अधिक जानें</a>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card " onclick="window.location.href='https://www.biharbhumiseva.in/blogs/premium-service-%e0%a4%aa%e0%a5%8d%e0%a4%b0%e0%a5%80%e0%a4%ae%e0%a4%bf%e0%a4%af%e0%a4%ae-%e0%a4%b8%e0%a5%87%e0%a4%b5%e0%a4%be/'">
                        <i class="fas fa-star"></i>
                        <h3>Premium Service</h3>
                        <p>Get exclusive access to premium features with our service.</p>
                        <a class="pt-3" href="https://www.biharbhumiseva.in/blogs/premium-service-%e0%a4%aa%e0%a5%8d%e0%a4%b0%e0%a5%80%e0%a4%ae%e0%a4%bf%e0%a4%af%e0%a4%ae-%e0%a4%b8%e0%a5%87%e0%a4%b5%e0%a4%be/">Learn More</a>
                    </div>

                </div>
                <div class="col-sm-3">
                    <div class="card " onclick="window.location.href='https://www.biharbhumiseva.in/blogs/document-submission-%e0%a4%a6%e0%a4%b8%e0%a5%8d%e0%a4%a4%e0%a4%be%e0%a4%b5%e0%a5%87%e0%a4%9c%e0%a4%bc-%e0%a4%b8%e0%a4%ac%e0%a4%ae%e0%a4%bf%e0%a4%b6%e0%a4%a8/'">
                        <i class="fas fa-file-upload"></i>
                        <h3>दस्तावेज़ सबमिशन</h3>
                        <p>अपने दस्तावेज़ को आसानी से सबमिट करें और उसकी स्थिति ट्रैक करें।</p>
                        <a class="pt-3" href="https://www.biharbhumiseva.in/blogs/document-submission-%e0%a4%a6%e0%a4%b8%e0%a5%8d%e0%a4%a4%e0%a4%be%e0%a4%b5%e0%a5%87%e0%a4%9c%e0%a4%bc-%e0%a4%b8%e0%a4%ac%e0%a4%ae%e0%a4%bf%e0%a4%b6%e0%a4%a8/">अधिक जानें</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="row mt-5">
        <div class="col-md-3">
            <div class="profile section-pfile">
                <h4><b>5000+ Property Broker Dealers Profile</b></h4>
                <p>Connect with Genuine Property Broker in your City</p>
                <a href="#" class="btn btn-success">Explore Now</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="amin section-pfile">
                <h4><b>100+ Verified Amin Property for Management</b></h4>
                <p>Search for the best Amin in your City</p>
                <a href="#" class="btn btn-success">Explore Now</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="munsi section-pfile">
                <h4><b>200+ Verified Deed writer,Munsi</b></h4>
                <p>Search for the best Munsi in your City</p>
                <a href="#" class="btn btn-success">Explore Now</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="point section-pfile">
                <h4><b>700+ Verified Bihar Bhumi Seva Service Point In Bihar</b></h4>
                <p></p>
                <a href="#" class="btn btn-success">Explore Now</a>
            </div>
        </div>
    </div> -->


    <div class="row py-5">
        <div class="col-sm-12 m-auto">
            <div class="seva text-center">
                <h4>
                    <b>Explore <span>Bihar Bhumi Seva</span> Blogs</b>
                </h4>
            </div>
            <div class="row">
                <!-- Blog Post 1 -->
                <div class="col-md-4">
                    <div class="blog-post">
                        <h2 class="blog-title">बिहार भूमि लॉकर क्या है कैसे काम करती है?</h2>
                        <p class="blog-snippet">दस्तावेज़ों की वैधता की पुष्टि के लिए बार-बार कार्यालय जाने की जरूरत नहीं पड़ती।.</p>

                        <a href="https://www.biharbhumiseva.in/blogs/how-to-create-bhumi-locker-account/" class="read-more">Read More</a>
                    </div>
                </div>

                <!-- Blog Post 2 -->
                <div class="col-md-4">
                    <div class="blog-post">
                        <h2 class="blog-title">दाखिल खारिज करे निशुल्क ऑनलाइन </h2>
                        <p class="blog-snippet">दस्तावेज़ों की दाखिल खारिज के लिए बार-बार कार्यालय जाने की जरूरत नहीं .</p>
                        <a href="https://www.biharbhumiseva.in/blogs/online-mutation-free/" class="read-more">Read More</a>
                    </div>
                </div>

                <!-- Blog Post 3 -->
                <div class="col-md-4">
                    <div class="blog-post">
                        <h2 class="blog-title">जमीन बेचने के लिए विज्ञापन</h2>
                        <p class="blog-snippet">विज्ञापन देना एक प्रभावी तरीका है जिससे आप अपनी जमीन को तेजी से और सुरक्षित तरीके से बेच सकते हैं।.</p>
                        <a href="https://www.biharbhumiseva.in/blogs/how-sale-plot-in-bihar/" class="read-more">Read More</a>
                    </div>
                </div>
            </div>
            <!-- <div class="blog-list-items">
                <ul>
                    <li><a href="https://www.biharbhumiseva.in/blogs/how-to-create-bhumi-locker-account/">बिहार भूमि लॉकर क्या है कैसे काम करती है?</a></li>
                    <li><a href="https://www.biharbhumiseva.in/blogs/online-mutation-free/">दाखिल खारिज करे निशुल्क ऑनलाइन बिहार भूमि सेवा देती है सर्विस </a></li>
                    <li><a href="https://www.biharbhumiseva.in/blogs/what-we-do-for-sale-property-in-bihar/">बिहार में जमीन बेचने के लिए विज्ञापन बिहार भूमि सेवा पर संपत्ति की सूचि क्यु बनाए।</a></li>
                    <li><a href="https://www.biharbhumiseva.in/blogs/how-sale-plot-in-bihar/">सम्पति को कैसे पोस्ट करे। </a></li>
                    <li><a href="https://www.biharbhumiseva.in/blogs/how-create-amin-membership-profile/">अमिन प्रोफाइल कैसे बनायें ?</a></li>
                    <li><a href="https://www.biharbhumiseva.in/blogs/how-to-make-munsi-membership-profile/">मुंसी प्रोफाइल कैसे बनाएं ?</a></li>
                </ul>
            </div> -->
            <div class="text-center">
                <a href="https://www.biharbhumiseva.in/blogs/" class="btn btn-primary">See All Blogs</a>
            </div>
        </div>
    </div>
</div>
<div class="container mb-5">
    <div class="row" align="center">
        <div class="col-md-6">
            <div class="vihar">
                <img src="image/logo.png" height="100" width="170">
                <div class="typing-container">
                    <h3 class="download">Download the Bihar Bhumi Seva APK now to access Essential land service at your fingertips!</h3>
                </div>

                <p class="seva">बिहार भूमि सेवा APK डाउनलोड करें और अपनी उंगलियों पर आवश्यक भूमि सेवाओं तक पहुँच प्राप्त करें</p>
                <img src="image/playstor.png" height="80" width="200">
                <img src="image/app-store.png" height="60" width="190"><br>
                <a href="https://play.google.com/store/apps/details?id=app.biharbhumiseva" class="now">Download Now</a>
            </div>
        </div>
        <div class="col-md-6 p-3 mt-5">
            <div class="mobile">
                <a href=""><img src="image/mob.png" class="mob"></a>
            </div>
        </div>
    </div>
</div>
<!-- <div style="background-color: #0A1A44;">
    <div class="container">
        <div class="mb-2">
            <a href="https://play.google.com/store/apps/details?id=app.biharbhumiseva" target="_blank"><img src="image/footer-data.png" alt="" class="img-fluid" /></a>
        </div>
    </div>
</div> -->
<div class="bg-light pt-4 mt-5">
    <div class="container">
        <div class="seva text-center ">
            <h5>
                <b>Find your Property in <span>Your Perferred City</span></b>
            </h5>
        </div>
        <?php
        $arrCities = $db->select('ai_districts', [], false, "dist_name ASC")->result();
        ?>
        <div class="py-4">
            <div class="row">
                <div class="col-sm-3">
                    <ul class="city-list">
                        <?php
                        foreach (array_slice($arrCities, 0, 10) as $indx => $city) {
                            $link = site_url('property-list.php?dist=' . $city->id);
                        ?>
                            <li><a href="<?= $link; ?>"><?= ucwords(strtolower($city->dist_name)); ?></a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <ul class="city-list">
                        <?php
                        foreach (array_slice($arrCities, 10, 10) as $indx => $city) {
                            $link = site_url('property-list.php?dist=' . $city->id);
                        ?>
                            <li><a href="<?= $link; ?>"><?= ucwords(strtolower($city->dist_name)); ?></a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <ul class="city-list">
                        <?php
                        foreach (array_slice($arrCities, 20, 10) as $indx => $city) {
                            $link = site_url('property-list.php?dist=' . $city->id);
                        ?>
                            <li><a href="<?= $link; ?>"><?= ucwords(strtolower($city->dist_name)); ?></a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <ul class="city-list">
                        <?php
                        foreach (array_slice($arrCities, 30, 10) as $indx => $city) {
                            $link = site_url('property-list.php?dist=' . $city->id);
                        ?>
                            <li><a href="<?= $link; ?>"><?= ucwords(strtolower($city->dist_name)); ?></a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>


<script type="text/javascript">
    $(document).ready(function() {
        var a = $('.slider').bxSlider({
            minSlides: 1,
            maxSlides: 5,
            mode: 'horizontal',
            adaptiveHeight: true,
            slideWidth: 260,
            moveSlides: 1,
            touchEnabled: false
        });
    });
</script>
<script>
    var audio = document.getElementById('audio-id');
    var is_playing = false;
    let btnplay = document.querySelector('.btnplay');
    btnplay.addEventListener("click", function() {
        if (is_playing) {
            audio.pause();
            is_playing = false;
            btnplay.innerHTML = '<i class="bi-volume-mute"></i>'

        } else {
            audio.play();
            is_playing = true;
            btnplay.innerHTML = '<i class="bi-volume-up"></i>'
        }
    })
    window.onload = function() {
        document.getElementById('audio-id').play();
        //is_playing = true;
    }
</script>
<?php
include 'common/footer.php';
