<?php
include "config/autoload.php";
include_once "common/header.php";
?>

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
      <?php include "common/alert.php"; ?>
      <div class="row d-flex justify-content-center">
        <div class="col-lg-12">
          <h2 class="fw-bold mb-5">Bhumi Locker Login</h2>
          
              <style>
              .myDiv{
              	display:none;
              }  
              </style>
            <div class="row">
              <div class="col-md-4 mb-4">
                <div class="form-outline">
                  <label class="form-label" for="mobileOtp">Mobile OTP</label>
                  <input class="form-check-input" type="radio" name="flexRadioDefault" id="mobileOtp" value="mobile">
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <div class="form-outline">
                  <label class="form-label" for="aadharOtp">Aadhar OTP</label>
                  <input class="form-check-input" type="radio" name="flexRadioDefault" id="aadharOtp" value="aadhar">
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <div class="form-outline">
                  <label class="form-label" for="aadharFingerscan">Aadhar fingerscan</label>
                  <input class="form-check-input" type="radio" name="flexRadioDefault" id="aadharFingerscan" value="fingerscan">
                </div>
              </div>
            </div>
            <span class="error" style="display:none"> Please Enter Valid Data</span>
            <span class="success" style="display:none"> Form Submitted Success</span>

            <!-- mobile form -->
            <div class="myDiv" id="showmobile">
              <div class="message"></div>
              <div class="form-group row mb-4">
                <label for="" class="col-sm-3 col-form-label">Mobile Number</label>
                <div class="col-sm-6">
                  <input type="number" class="form-control" id="mobile" name="mobile_number" value="" placeholder="Mobile number" required />
                </div>
                <div class="col-sm-3">
                  <input style="background: #3f51b5;" class="btn btn-primary" name="send_otp" type="button" id="send_otp" value="Send OTP" required />
                </div>
              </div>
            </div>

            <!-- Aadhar form -->
            <div class="myDiv" id="showaadhar">
              <div class="form-group row mb-4">
                <div class="message"></div>
                <label for="inputAdhar" class="col-sm-3 col-form-label">Aadhar Number</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="inputAdhar" placeholder="XXXX XXXX XXXX XXXX" required />
                  </div>
                  <div class="col-sm-3">
                    <button type="button" name="aadhar_otp" id="aadhar_otp" style="background: #3f51b5;" class="btn btn-primary">Verify otp</button>
                  </div>
                </div>
              </div>
            </div>


            <!-- otp form -->
            <div class="form-group row mb-4">
              <label for="inputPassword3" class="col-sm-3 col-form-label">OTP</label>
              <div class="col-sm-6">
                <input type="number" class="form-control" id="otp" name="otp" placeholder="xxxx" required />
              </div>
              <div class="col-sm-3">
              </div>
            </div>


            <!-- login password -->
            <div class="form-group row mb-4">
              <label for="inputPassword3" class="col-sm-3 col-form-label">Your Password</label>
              <div class="col-sm-6">
                <input type="password" class="form-control" id="password" name="password" placeholder="xxxx" required />
              </div>
              <div class="col-sm-3">
              </div>
            </div>


            <div class="col-sm-3">
              <input style="background: #3f51b5;" class="btn btn-primary btn-block mb-4" name="login" type="button" id="login" value="Login">
            </div>
              
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

<?php
include_once "common/footer.php";
?>
<script>
$(document).ready(function(){
    $('input[type="radio"]').click(function(){
    	var demovalue = $(this).val();
        $("div.myDiv").hide();
        $("#show"+demovalue).show();
    });
});
</script>

<script type="text/javascript" >
  $(document).ready(function () {
  $("#send_otp").click(function (event) {
    var formData = {
      mobile: $("#mobile").val(),
    };
    $.ajax({
      type: "POST",
      url: "send_bhumi_otp.php",
      data: formData,
      dataType: "json",
      encode: true,
    }).done(function (data) {
      if (data.success) {
          // alert(data.message);
          $(".message").removeClass("error").text(data.message).css("color", "green");
      } else {
          $(".message").addClass("error").text(data.message).css("color", "red");
      }
    });

    event.preventDefault();
  });

  $("#login").click(function (event) {
    var formData = {
      mobile: $("#mobile").val(),
      password: $("#password").val(),
      otp: $("#otp").val(),
    };
    $.ajax({
      type: "POST",
      url: "bhumi-login-action.php",
      data: formData,
      dataType: "json",
      encode: true,
    }).done(function (data) {
      if(data.success) {
          window.location.href = "bhumi-locker.php";
      } else {
          $(".message").addClass("error").text(data.message).css("color", "red");
      }
      
            
        
    });

    event.preventDefault();
  });
});
</script>