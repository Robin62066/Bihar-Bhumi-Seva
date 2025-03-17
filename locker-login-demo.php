<?php
include "config/autoload.php";
include_once "common/header.php";

?>

<style>
  .myDiv{
    display:none;
  }  
</style>

<div class="login-wrapper py-5">
<!-- Section: Design Block -->
<section class="text-center">

  <div class="container">
    <div class="row justify-content-center">
  <div class="card mx-4 mx-md-5 shadow-5-strong" style="
        background: hsla(0, 0%, 100%, 0.8);
        backdrop-filter: blur(30px);
        width: 60%;
        ">
    <div class="card-body py-5 px-md-5">

      <div class="row d-flex justify-content-center">
        <div class="col-lg-12">
          <h2 class="fw-bold mb-5">Bhumi Locker Login</h2>
          <form class="form-inline">
            <div class="row">
              <div class="col-md-4 mb-4">
                <div class="form-outline">
                  <label class="form-label" for="form3Example1">Mobile otp</label>
                  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="mobile">
                </div>
              </div>
              <div class="col-md-4 mb-4">
                <div class="form-outline">
                  <label class="form-label" for="form3Example2">Aadhar otp</label>
                  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="aadhar">
                </div>
              </div>
              <div class="col-md-4 mb-4">
                <div class="form-outline">
                  <label class="form-label" for="form3Example2">Aadhar fingerscan</label>
                  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="fingerscan">
                </div>
              </div>
            </div>

            <div class="myDiv" id="showmobile"><div class="form-group row mb-4">
              <label for="inputEmail3" class="col-sm-3 col-form-label">Mobile No</label>
              <div class="col-sm-6">
                <input type="number" class="form-control" id="inputEmail3" placeholder="Mobile number" required />
              </div>
              <div class="col-sm-3">
                <button type="submit" class="btn btn-primary">Send otp</button>
              </div> </div>             
            </div>

            <div class="myDiv" id="showaadhar"><div class="form-group row mb-4">
              <label for="inputEmail3" class="col-sm-3 col-form-label">Aadhar No</label>
              <div class="col-sm-6">
                <input type="number" class="form-control" id="inputEmail3" placeholder="xxxx xxxx xxxx" required />
              </div>
              <div class="col-sm-3">
                <button type="submit" class="btn btn-primary">Verify otp</button>
              </div> </div>             
            </div>
            
            <div class="form-group row mb-4">
              <label for="otp" class="col-sm-3 col-form-label">Otp No</label>
              <div class="col-sm-6">
                <input type="password" class="form-control" id="inputPassword3" placeholder="Enter your otp" required />
              </div>
              <div class="col-sm-3">
                <!-- <button type="submit" class="btn btn-primary">verify</button> -->
              </div>
            </div>

            <div class="form-group row mb-4">
              <label for="inputEmail3" class="col-sm-3 col-form-label">Login Password</label>
              <div class="col-sm-6">
                <input type="password" class="form-control" id="inputPassword4" placeholder="xxxx" required />
              </div>
              <div class="col-sm-3">
                <!-- <button type="submit" class="btn btn-primary">&nbsp;</button> -->
              </div>          
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-block mb-4">
              Login
            </button>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</section>
<!-- Section: Design Block -->

</div>


<script>
    let vm = new Vue({
        el: '#origin',
        data: {
            user_type: ''
        }
    });
</script>

<script>
$(document).ready(function(){
    $('input[type="radio"]').click(function(){
      var demovalue = $(this).val(); 
      //alert(demovalue);
        $("div.myDiv").hide();
        $("#show"+demovalue).show();
    });
});
</script>

<?php
include_once "common/footer.php";
?>