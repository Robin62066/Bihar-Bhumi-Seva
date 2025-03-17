<?php
include "config/autoload.php";

if (isset($_POST['btn-upload'])) {
    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $sb['image'] = do_upload('image');
        if ($sb['image'] != '') {
            $db->insert('ai_images', $sb);
        }
        redirect('bhumi-locker.php');
    } else {
        echo "Please upload an image.";
    }
}

$docs = $db->select('ai_images', [], false)->result();
// print_r($docs); die;

include "common/header.php";
?>


<div class="container-fluid upload-document" style="padding: 30px 10px;">
	<div class="px-lg-5">
		<div class="message"></div>
		<form action="" enctype="multipart/form-data" method="post">
		    <div class="row mb-12">
		        <div class="col-sm-3">
		            <div data-mdb-input-init class="form-outline">
		                <h2>Documents</h2>
		            </div>
		        </div>
		        <div class="col-sm-6">
		            <div data-mdb-input-init class="form-outline">
		                <input class="form-control" name="image" id="formFileLg" type="file" />
		            </div>
		        </div>
		        <div class="col-sm-3">
		            <div data-mdb-input-init class="form-outline">
		                <button type="submit" value="upload" name="btn-upload" class="btn btn-dark px-5 py-2 text-uppercase">Upload Documents Here</button>
		            </div>
		        </div>
		    </div>
		</form>
	</div>
</div>

<div class="container-fluid">
  <div class="px-lg-5">
    <div class="row">
    <?php
		foreach ($docs as $doc) {
	?>
      <!-- Gallery item -->
      <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <div class="bg-white rounded shadow-sm"><img src="<?= base_url(upload_dir($doc->image)) ?>" alt="" class="img-fluid card-img-top">
          <div class="p-4">
            <h6> <a href="#" class="text-dark">Red paint cup</a></h6>
            <div class="d-flex align-items-center justify-content-between rounded-pill bg-light px-3 py-2 mt-4">
              <p class="small mb-0"><i class="fa fa-picture-o mr-2"></i><span class="font-weight-bold">JPG</span></p>
              <div class="badge badge-danger px-3 rounded-pill font-weight-normal">New</div>
            </div>
          </div>
        </div>
      </div>
      <!-- End -->
	<?php
		}
	?>

    </div>
    
  </div>
</div>


<?php
include_once "common/footer.php";
?>