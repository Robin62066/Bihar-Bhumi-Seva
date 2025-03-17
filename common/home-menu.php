<div id="navmenu" class="col-sm-2">
    <div class="home-sidebar">
        <div class="d-block d-sm-none">
            <div class="p-2 mobile-mmenu text-white d-flex gap-2 align-items-center">
                <button id="btnmenu" class="btn btn-sm btn-light"><i class="bi-list"></i> </button>
                <div>MENU</div>
            </div>
        </div>
        <div class="home-menu">
            <ul>
                <li><a href="property-list.php">सम्पति सूचि देखें</a></li>
                <li><a href="users.php?type=brokers">ब्रोकर सूचि देखें </a></li>
                <li><a href="users.php?type=munsi">मुंसी सूचि देखें </a></li>
                <li><a href="users.php?type=amin">अमीन सूचि देखें </a></li>
                <li><a href="users.php?type=co">CO सूचि देखें </a></li>
                <li><a href="labours.php">मजदूर </a></li>
                <li><a href="users.php?type=building-contructor">बिल्डर्स</a></li>
                <li><a href="users.php?type=bricks-mfgs">ब्रिक्स मैन्युफैक्चरर</a></li>
                <li><a href="users.php?type=sand-suppliers">बालू सप्लायर</a></li>
                <li><a href="complaint.php">शिकायत दर्ज़ करें </a></li>
                <li><a href="sell-property.php">सम्पति को पोस्ट करें </a></li>
                <li><a href="online-mutation.php">म्युटेशन के लिए आवेदन करें </a></li>
                <li><a href="check-mutation.php">म्युटेशन की स्थिति देखें </a></li>
                <li><a href="signup.php">बिहार भूमि लॉकर </a></li>
            </ul>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#btnmenu').click(function() {
            $('.home-menu').slideToggle('slow');
        })
    })
</script>