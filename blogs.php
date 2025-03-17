<?php
include "config/autoload.php";
include "common/header.php";
?>
<div class="container py-4">
    <div class="page-header">
        <h3>Blogs</h3>
    </div>
    <div class="bg-white p-4 blogs-view">
        <div class="item-row">
            <div class="item-q">
                <span>How to submit property ?</span>
                <span data-target="#tab1" class="more-view">
                    <i class="bi-chevron-down"></i>
                </span>
            </div>
            <div id="tab1" class="item-ans">
                -Answer will come here
            </div>
        </div>
        <div class="item-row">
            <div class="item-q">
                <span>How to create broker profile?</span>
                <span data-target="#tab2" class="more-view">
                    <i class="bi-chevron-down"></i>
                </span>
            </div>
            <div id="tab2" class="item-ans">
                -Answer will come here
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.more-view').click(function() {
            let tr = $(this).data('target');
            $(this).children('i').toggleClass('bi-chevron-up');
            $(tr).slideToggle();
        })
    })
</script>
<?php
include "common/footer.php";
