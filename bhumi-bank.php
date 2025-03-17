<?php
include "config/autoload.php";

include "common/header.php";
?>
<div class="container">
    <div class="row g-0">
        <?= front_view('common/home-menu'); ?>
        <div class="col-sm-9">
            <div class="p-3 bg-white mb-2">
                <?= front_view("common/alert"); ?>
                <div class="search-area">
                    <div class="row">
                        <div class="col-sm-5">
                            <select class="form-select">
                                <option value="">District</option>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <select class="form-select">
                                <option value="">Zone</option>
                            </select>
                        </div>
                        <div class="col-sm-2 d-grid">
                            <button class="btn btn-primary btn-sm">Filter</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white p-3">
                <h1 class="h5">Bhumi Bank</h1>
                <hr />
                We are working....
            </div>
        </div>
    </div>
</div>
<?php
include "common/footer.php";
