<?php
include 'config/autoload.php';
include 'common/header.php';
?>

<div class="container bg-white" style="width: 500px;">
    <div class="border border-1 mt-3 mb-3">
        <div class="text-center mt-3">
            <h4 class="mb-3 ">Property Details </h4>
        </div>

        <div class="row">

            <div class="col-sm-6">
                <img class="card-img-center" src="image/bricks.jpeg" alt="" width="200" height="200">
            </div>
            <div class="col-sm-6">
                <div class="mb-3">
                    Property Title :- <span> House</span>
                </div>
                <div class="mb-3">
                    Property Type :- <span> House</span>
                </div>
                <div class="mb-3">
                    Property Title :- <span> House</span>
                </div>
                <div class="mb-3">
                    Property Address :- <span>House</span>
                </div>
                <div class="mb-3">
                    Property Price :- <span> House</span>
                </div>

            </div>
        </div>
    </div>

    <div class="text-center mb-3">
        <h4 class="mb-3 ">Property Details </h4>
    </div>
    <div class="card card-body mb-3">
        <form action="#" method="POST" enctype="multipart/form-data">
            <div class="bg-white p-3 shadow-sm1 rounded">

                <div class="row">

                    <div class="col-12">
                        <label>Full name:</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label>Mobile No:</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label>Email Id:</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label>Address:</label>
                        <textarea class="form-control" placeholder="Property Description" name="description" rows="3"></textarea>
                    </div>
                </div>

                <button type="submit" name="submit" class="btn btn-primary px-4 py-2 mt-3">Submit</button>

            </div>

        </form>
    </div>

</div>