<html>
<?php
include "./config/autoload.php";
$id = $_GET['id'] ?? null;
$item = $row = $db->select('ai_labours', ['user_id' => $id])->row();
?>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<style type="text/css">
    :root {}

    /* General Layout */
    body {
        font-family: sans-serif;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: #efe;
    }

    .box {
        min-width: 552px;
        height: calc(1 * var(--size));
        min-height: 368px;
        box-sizing: border-box;
    }

    .front {
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: #071c33;
        background-size: 10%;

        background-repeat: no-repeat;
        color: #fff;
        font-weight: bolder;
        font-size: 200%;
    }

    .front p {
        margin-top: 65px;
    }

    .back {
        width: 100%;
        font-size: 70%;


    }

    .button {
        text-transform: uppercase;
        text-decoration: none;
        color: #fff;
        background-color: rgb(50, 150, 100);
        padding: 1rem 2rem;
        border-radius: 5px;
        margin-top: 2%;
    }

    .image {
        position: absolute;
        z-index: 1;


    }

    .photo {
        position: relative;
        top: 24px;
        left: 20px;
        border-radius: 20px;
    }

    .up {
        width: 100%;
        position: absolute;
        z-index: 5;
    }

    .down {
        position: absolute;
        z-index: 5;
    }

    .uname {
        position: relative;
        left: 400px;
        bottom: 70px;
    }

    .fname {
        position: relative;
        left: 400px;
        bottom: 70px;
    }

    .dob {
        position: relative;
        left: 400px;
        bottom: 76px;
    }

    .gender {
        position: relative;
        left: 400px;
        bottom: 84px;
    }

    .address {
        position: relative;
        left: 400px;
        bottom: 91px;

    }

    .mobile {
        position: relative;
        bottom: 4px;
        font-weight: bold;
        left: 60px;
    }

    .uid {
        position: relative;
        bottom: 50px;
        left: 300px;
        font-weight: bold;
        font-size: 25px;
    }

    /* Print Styles */
    @media print {

        /*  Hide everything that doesn't have the '.print' class */
        body>*:not(.print) {
            display: none;
        }

        .front {
            /*      Force the browser to print with the given colors instead of overwriting to black&white */
            color-adjust: exact;
            -webkit-print-color-adjust: exact;
            background-color: #071c33;
            color: #fff;
        }

        /*  Give the cards fixed dimensions  */

        /*  Remove the header and footer text and urls the browser places  */
        @page {
            margin: 0;

        }

    }
</style>

<body>


    <div class="print mt-5">

        <div class="box back">
            <img src="./image/card1.png" class="image" alt="..." height="368" width="552">
            <div class="up">
                <img src="<?= base_url(upload_dir($item->photo)) ?>" class="photo" width="157" height="141" style="border-radius: 10px;" />
                <p class="uid" style=" letter-spacing: 8px; "><?= $item->user_id; ?></p>
                <p class="mobile"><?= $item->mobile_number; ?></p>
                <p class="uname"><?= $item->first_name . " " . $item->middle_name . " " . $item->last_name; ?></p>
                <p class="fname"> <?= $item->father_name; ?></p>
                <p class="dob"><?= $item->dob; ?></p>
                <p class="gender"> <?= $item->gender; ?></p>
                <p class="address"> <?= $item->address1; ?></p>


            </div>

        </div>
    </div>
    <a href="#!" class="button">
        Download
    </a>

</body>
<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script type="text/javascript">
    const button = document.querySelector('a');
    button.addEventListener('click', () => {
        window.print()
    });
</script>

</html>