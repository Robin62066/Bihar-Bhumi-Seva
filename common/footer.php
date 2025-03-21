<footer class="bg-light mt-2">
    <div class="container">
        <div class="bg-white p-1 mb-1">
            <ul class="fmenu-new">
                <li><a href="<?= site_url() ?>">Home</a></li>
                <li><a href="https://www.biharbhumiseva.in/blogs/about-us/">About us</a></li>
                <li><a href="<?= site_url('contact-us.php') ?>">Contact us</a></li>
                <li><a href="<?= site_url('terms-conditions.php') ?>">Terms of use</a></li>
                <li><a href="<?= site_url('privacy-policy.php') ?>">Privacy Policy</a></li>
                <li><a href="<?= site_url('refund-policy.php') ?>">Refund Policy</a></li>
                <li><a href="<?= site_url('careers.php') ?>">Career</a></li>
                <li><a href="<?= site_url('pricing.php') ?>">Membership Plans</a></li>
                <li><a href="<?= site_url('blogs/') ?>">Blogs</a></li>
            </ul>
        </div>
        <div class="p-2 bg-white">
            <div class="footer-new">
                <div>
                    <a href="https://play.google.com/store/apps/details?id=app.biharbhumiseva&pli=1" target="_blank">
                        <img src="<?= base_url('assets/front/img/play-store.png') ?>" height="30" />
                        <img src="<?= base_url('assets/front/img/app-store.png') ?>" height="30" />
                    </a>
                </div>
                <div>
                    <div class="social-icons fs-5">
                        <a href="https://www.facebook.com/biharbhumiseva" target="_blank"><i class="bi-facebook"></i> </a>
                        <a href="#" target="_blank"><i class="bi-twitter text-info"></i> </a>
                        <a href="https://www.instagram.com/biharbhumiseva/" target="_blank"><i class="bi-instagram text-danger"></i> </a>
                        <a href="https://www.youtube.com/@BiharBhumiSeva/featured" target="_blank"><i class="bi-youtube text-danger"></i> </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="pt-2">
            बिहार भूमि सेवा में बिना रजिस्टर के कोई भी प्लॉट खरीदने के लिए आप स्वयं जिम्मेदार होंगे।
            जमीन खरीदना है एक बड़ा निवेश होता है और इसे खरीदते वक्त अनेक प्रकर की धोखधड़ी की संभावना बानी रहती है। ऐसा में सही जमीन चुनने और सही मालिक की पहचान करने के लिए विभिने ऑनलाइन शंसाधनो का उपयोगी एक महत्पूणे कदम है। जो व्यक्ति जमीन बेच रहा ह उसका दस्ताबेज पेपर वेरिफ़िएड करती है बिहार भूमि सेवा।
        </div>
    </div>
</footer>
<script src="<?= base_url('assets/front/js/custom.js') ?>"></script>
<script src="<?= base_url('assets/front/js/bootstrap.min.js') ?>"></script>
<script>
    $(".btn-copy").click(function() {
        var metxt = $(this).data('copy');
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(metxt).select();
        document.execCommand("copy");
        $temp.remove();
        // $(this).html('<i class="fa fa-copy"></i> COPIED');
        alert("Link copied to share")
    });
    $(document).ready(function() {
        $('form').submit(function() {
            $(this).find('.btn-submit').html("Please wait...").prop('disabled', 'disabled');
        })
    });
</script>

</body>

</html>