<?php
include "./config/autoload.php";
$id = $_GET['id'] ?? null;
$item = $row = $db->select('ai_labours', ['user_id' => $id])->row();
?>
<html>

<head>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        .full-bg {
            position: absolute;
        }

        .card {
            display: flex;
            justify-content: center;
            margin: 0;
            padding: 0;
            align-items: center;
            border: none;
            width: 100%;
            height: 100%;
        }

        .uname,
        .fname,
        .dob,
        .gender,
        .address,
        .photo {
            position: absolute;
            left: 790px;
            font-weight: 500;
            font-size: 14px;
        }

        .uname {
            bottom: 316px;
            left: 790px;

        }

        .fname {
            bottom: 282px;

        }

        .dob {
            bottom: 257px;

        }

        .gender {
            bottom: 232px;

        }

        .address {
            bottom: 210px;
            max-width: 200px;

        }

        .uid {
            position: absolute;
            bottom: 370px;
            left: 700px;
            gap: 4px;
            color: white;
            font-size: 18px;
            font-weight: 800;
        }

        .photo {
            position: absolute;
            bottom: 343px;
            left: 428px;
        }

        .mobile {
            position: absolute;
            bottom: 280px;
            left: 455px;
            font-size: 18px;
            font-weight: 800;

        }
    </style>
</head>

<body>

    <div class="container">
        <div class="card   ">
            <img class="full-bg" src="./image/card1.png" alt="image" height="368" width="552" />
        </div>
        <p class="uname"><?= $item->first_name . " " . $item->middle_name . " " . $item->last_name; ?></p>
        <p class="fname"><?= $item->father_name; ?></p>
        <p class="dob"><?= $item->dob; ?></p>
        <p class="gender"><?= $item->gender; ?></p>
        <p class="mobile"><?= $item->mobile_number; ?></p>
        <p class="address"><?= $item->address1; ?></p>
        <p class="uid" style=" letter-spacing: 8px; "><?= $item->user_id; ?></p>
        <img src="<?= base_url(upload_dir($item->photo)) ?>" class="photo" width="157" height="141" style="border-radius: 10px;" />
    </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


</body>

</html>