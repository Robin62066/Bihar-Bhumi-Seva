<?php
include "config/autoload.php";

include "common/header.php";
?>
<div class="container">
    <hr />
    <div class="search-area bg-white mb-2 shadow shadow-sm p-2 rounded">
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
        <div><span class="text-danger"> <i class="bi-geo-alt"></i> </span> Echak More, Hazaribagh - JH </div>
        <div>
            <i class="bi-bookmark"></i>
        </div>
    </div>
    <div class="border rounded mb-3">
        <div class="slider-main bg-white text-center py-3">
            <img src="<?= theme_url('img/land-photo.png') ?>" class="img-fluid" />
        </div>
    </div>
    <div class="border bg-white rounded mb-3">
        <div class="row">
            <div class="col-sm-4">
                <div class="p-3">
                    <h6 class="text-danger"> <i class="bi-currency-rupee"></i> 22,00,000/- </h6>
                    <p class="text-muted">3798/- Sq/ft</p>
                    <h6 class="d-flex gap-2">
                        <div class="bg-danger text-white py-2 px-2 rounded"> <i class="bi-telephone"></i> </div>
                        <div>
                            <div>91985748596</div>
                            <div>91985748596</div>
                        </div>
                    </h6>
                </div>
            </div>
            <div class="col-sm-4 border-start border-end">
                <div class="user-box-info p-3">
                    <div class="photo-view">
                        <img src="<?= theme_url('img/seller.png') ?>" class="img-fluid" />
                    </div>
                    <div class="detail-view">
                        <div><b>Owner Details</b></div>
                        <div>Seller Name Singh</div>
                        <div>Aadhar No: XXXX-XXXX-5680</div>
                        <div>Phone No: XXX-XXX-7890</div>
                        <a href="#" class="btn btn-warning btn-xs">View Profile</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="user-box-info p-3">
                    <div class="photo-view">
                        <img src="<?= theme_url('img/broker.png') ?>" class="img-fluid" />
                    </div>
                    <div class="detail-view">
                        <div><b>Owner Details</b></div>
                        <div>Seller Name Singh</div>
                        <div>Aadhar No: XXXX-XXXX-5680</div>
                        <div>Phone No: XXX-XXX-7890</div>
                        <a href="#" class="btn btn-warning btn-xs">View Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="border bg-white rounded mb-3">
        <div class="row g-0">
            <div class="col-sm-7">
                <div class="p-3">
                    <h6>Land/Plot Details</h6>
                    <table class="table table-sm table-border">
                        <tbody>
                            <tr>
                                <td>a</td>
                                <td>b</td>
                                <td>c</td>
                                <td>d</td>
                            </tr>
                            <tr>
                                <td>a</td>
                                <td>b</td>
                                <td>c</td>
                                <td>d</td>
                            </tr>
                            <tr>
                                <td>a</td>
                                <td>b</td>
                                <td>c</td>
                                <td>d</td>
                            </tr>
                            <tr>
                                <td>a</td>
                                <td>b</td>
                                <td>c</td>
                                <td>d</td>
                            </tr>
                            <tr>
                                <td>a</td>
                                <td>b</td>
                                <td>c</td>
                                <td>d</td>
                            </tr>
                            <tr>
                                <td>a</td>
                                <td>b</td>
                                <td>c</td>
                                <td>d</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-5 border-start">
                <img src="<?= theme_url('img/map-location.png') ?>" class="img-fluid" />
            </div>
        </div>
    </div>
</div>
<?php
include "common/footer.php";
