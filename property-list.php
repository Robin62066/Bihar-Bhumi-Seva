<?php
include "config/autoload.php";
$page = $_GET['page'] ?? 1;
$limit = 5;
$offset = ($page - 1) * $limit;
$dists = $db->select('ai_districts', [], false, 'dist_name ASC')->result();
$sql = "SELECT * FROM ai_sites WHERE (status = 1 OR status = 3) AND isPromoted = 0 ORDER BY created DESC LIMIT $limit OFFSET $offset";
$sql1 = "SELECT * FROM ai_sites WHERE (status = 1 OR status = 3) AND isPromoted = 1 ORDER BY created DESC LIMIT $limit OFFSET $offset";

$Props = $db->query($sql)->result();
$property = $db->query($sql1)->result();

$items = [];
while (!empty($property) && !empty($Props)) {
    if (!empty($property)) {

        $items[] = array_shift($property);

        $items = array_merge($items, array_splice($Props, 0, 4));
    } else {

        $items = array_merge($items, $Props);
    }
}

// echo "<pre>";
// print_r($items);
// echo "</pre>";
// die;

$total = $db->query("SELECT COUNT(*) as total FROM ai_sites WHERE status=1")->row()->total;
$links = ceil($total / $limit);

$dist_id = $_GET['dist'] ?? null;
$distName = '';
if ($dist_id > 0) {
    $items = $db->select('ai_sites', ['status' => 1, 'dist_id' => $dist_id], "$limit OFFSET $offset", "id DESC")->result();
    foreach ($dists as $dt) {
        if ($dt->id == $dist_id) {
            $distName = $dt->dist_name;
        }
    }
}

if (isset($_POST['btnleads'])) {
    $sb = $_POST['form'];
    $sb['user_id'] = is_login() ? user_id() : 0;
    $sb['created'] = date("Y-m-d H:i:s");
    $sb['status']  = 0;
    $db->insert("ai_leads", $sb);
    redirect(site_url("property-list.php"), "Enquiry Details sent successfully", "success");
}
include_once "common/header.php";
?>
<style>
    article.post {
        margin-bottom: 20px;
    }
</style>
<script src="https://unpkg.com/infinite-scroll@4.0.1/dist/infinite-scroll.pkgd.min.js"></script>
<div id="origin" class="container">
    <hr />
    <div class="search-area bg-white mb-2 shadow shadow-sm p-2 rounded">
        <form action="" method="get">
            <div class="row">
                <div class="col-sm-3">
                    <select @change="set_zones" name="dist" v-model="dist_id" class="form-select">
                        <option value="">Select District</option>
                        <?php
                        foreach ($dists as $item) {
                        ?>
                            <option value="<?= $item->id; ?>"><?= $item->dist_name; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-3">
                    <select name="zone" v-model="zone_id" class="form-select">
                        <option value="">Select Anchal</option>
                        <option v-for="item in zones" :value="item.id">{{ item.zone_name }}</option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <select name="budget" class="form-select">
                        <option value="">Budget</option>
                        <option value="1">10k-1Lac</option>
                        <option value="2">1Lac-50Lac</option>
                        <option value="3">50Lac-5Cr</option>
                        <option value="4">Above 5Cr</option>
                    </select>
                </div>
                <div class="col-sm-3 d-grid">
                    <button name="search" value="Search" class="btn btn-primary btn-sm">Filter</button>
                </div>
            </div>
        </form>
    </div>
    <div v-if="errmsg.length>0" class="alert mb-2" :class="errcls">{{errmsg}}</div>
    <?= front_view("common/alert"); ?>

    <div class="row">
        <?= front_view('common/home-menu'); ?>
        <div class="col-sm-9">
            <?php
            if ($distName != '') {
            ?>
                <div class="page-header pt-3">
                    <h1 class="h3">Properties in <span class="text-primary"><?= $distName; ?></span></h1>
                </div>
            <?php
            }
            if (count($items) == 0) {
            ?>
                <div class="card p-5">
                    <div class="text-center">
                        <p><b>NO PROPERTY FOUND </b></p>
                        <a href="sell-property.php" class="btn btn-outline-primary">Post your Property</a>
                    </div>
                </div>
            <?php
            }
            ?>
            <div class="property-wrapper">
                <?php
                foreach ($items as $item) {

                    $user = $db->select('ai_users', ['id' => $item->user_id], 1)->row();
                    $isWishlisted = false;
                    if (is_login()) {
                        $isWishlisted = $db->select("ai_wishlist", ['pid' => $item->id, 'user_id' => user_id()], 1)->row();
                    }



                ?>
                    <article class="post">
                        <?php
                        $cls = "";
                        if ($item->status == 3) {
                            $cls = "border border-danger rounded p-3  bg-danger-subtle";
                        } else if ($item->isPromoted == 1 || $item->status == 3) {
                            $cls = "border border-2 rounded p-3  bg-white";
                        } ?>
                        <div class="row <?= $cls; ?>">
                            <div class="col-12 col-sm-3">
                                <div class="property-list-thumbnail">
                                    <?php if ($item->status == 3) { ?>
                                        <a href="#">
                                        <?php } else { ?>
                                            <a href="<?= base_url('property-view.php?id=' . $item->id); ?>">
                                            <?php } ?>
                                            <img src="<?= base_url(upload_dir($item->photo_front)) ?>"
                                                alt="" class="img-fluid w-100">
                                            </a>
                                </div>
                            </div>
                            <div class="col-12 col-sm-9">
                                <div class="p-2 d-flex justify-content-between flex-column h-100">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h1 class="h5 mb-0 site-title ">
                                                <a href="<?= base_url('property-view.php?id=' . $item->id); ?>">
                                                    <?= $item->site_title; ?>
                                                </a>
                                            </h1>
                                            <div class="text-muted mb-2"> <i class="bi-home"></i> <?= $item->address; ?></div>
                                        </div>
                                        <div class="text-end">
                                            <div class="mb-3">
                                                <?php
                                                if ($isWishlisted) {
                                                ?>
                                                    <button @click="removeWishlist('<?= $item->id ?>')" class="btn btn-sm"><i class="bi-bookmark-fill text-success"></i></button>
                                                <?php
                                                } else {
                                                ?>
                                                    <button @click="addWishlist('<?= $item->id ?>')" class="btn btn-sm"><i class="bi-bookmark"></i></button>
                                                <?php
                                                }
                                                ?>

                                            </div>
                                            <div class="mb-1">
                                                <!-- <button @click="sendSMS('<?= $item->id ?>')" class="btn btn-sm btn-outline-dark">Send SMS</button> -->
                                                <button @click="pid=<?= $item->id ?>" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-sm btn-outline-dark">Send Msg</button>
                                            </div>
                                            <?php
                                            if ($user->whatsapp != '') {
                                            ?>
                                                <a href="https://api.whatsapp.com/send?phone=91<?= $user->whatsapp; ?>" class="btn btn-sm btn-success">WhatsApp</a>
                                            <?php
                                            }
                                            ?>
                                            <?php
                                            if ($item->isPromoted == 1) { ?>
                                                <p style="color: gray; " class="mt-1 mb-0 text-center">Sponsored</p>
                                            <?php }
                                            ?>
                                            <?php
                                            if ($item->status == 3) { ?>
                                                <img src="image/giphy.gif" alt="" height="80" width="80">
                                            <?php }
                                            ?>

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
                    </article>
                <?php

                }
                ?>
            </div>
            <!-- <div class="py-2 text-center">
                <?php
                if ($page <= 1) {
                ?>
                    <button disabled class="btn btn-sm btn-primary">Previous</button>
                    <a href="<?= site_url('property-list.php?page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
                <?php
                } else if ($page >= $links) {
                ?>
                    <a href="<?= site_url('property-list.php?page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
                    <button disabled class="btn btn-sm btn-primary">Next</button>
                <?php
                } else if ($links >= 1 && $page < $links) {
                ?>
                    <a href="<?= site_url('property-list.php?page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
                    <a href="<?= site_url('property-list.php?page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
                <?php
                }
                ?>
            </div> -->
        </div>
    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="" method="post">
                    <input type="hidden" name="form[prop_id]" v-model="pid">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Send Enquiry</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="clearfix">
                            <p class="small text-danger mb-3">Missing or in-accurate details will be reject automatically.</p>
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
                                    <input class="form-control" name="form[mobile]" maxlength="10" required placeholder="Mobile number *" />
                                </div>
                                <div class="col-sm-6">
                                    <input class="form-control" name="form[email_id]" placeholder="Email Id (Optional)" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <textarea rows="3" name="form[details]" class="form-control" placeholder="Details *"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="hidden" name="btnleads" value="Send" />
                        <button class="btn btn-primary btn-submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include_once "common/footer.php";
?>
<script>
    $(document).ready(function() {
        console.log("Loading...")
        $('.property-wrapper').infiniteScroll({
            // options
            path: 'ajax-props.php?page={{#}}',
            append: '.post',
            history: false,
        });
    });
    let vm = new Vue({
        el: '#origin',
        data: {
            zones: [],
            dist_id: '<?= $dist_id; ?>',
            zone_id: '',
            btncls: '',
            errmsg: '',
            errcls: '',
            pid: 0
        },
        methods: {
            set_zones: function() {
                this.zones = [];
                api_call('zones', {
                    dist_id: this.dist_id
                }).then(resp => {
                    if (resp.success) this.zones = resp.data;
                })
            },
            sendSMS: function(pid) {
                api_call('send-sms-enquiry', {
                    pid: pid
                }).then(resp => {
                    this.errmsg = resp.message;
                    this.errcls = resp.success ? 'alert-success' : 'alert-danger'
                })
            },
            addWishlist: function(pid) {
                api_call('add-wishlist', {
                    pid: pid
                }).then(resp => {
                    this.errmsg = resp.message;
                    this.errcls = resp.success ? 'alert-success' : 'alert-danger'
                })
            },
            removeWishlist: function(pid) {
                api_call('remove-wishlist', {
                    pid: pid
                }).then(resp => {
                    this.errmsg = resp.message;
                    this.errcls = resp.success ? 'alert-success' : 'alert-danger'
                })
            }
        },
        created: function() {
            if (this.dist_id != '') {
                this.set_zones();
            }
        }
    });
</script>