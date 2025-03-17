<?php
include "../config/autoload.php";
$id = $_GET['id'] ?? null;
$item = $row = $db->select('ai_labours', ['id' => $id])->row();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .uname,
        .fname,
        .dob,
        .gender {
            position: relative;
            font-size: 13px;
            left: 380px;
        }

        .uname {
            top: -195px;

        }

        .fname {
            top: -192px;
        }

        .dob {
            top: -200px;
        }

        .gender {
            top: -213px;
        }

        .uid {
            position: relative;
            font-size: 13px;
            top: -216px;
            left: 230px;
            gap: 10px;
        }

        .photo {
            position: relative;
            top: -330px;
            left: 114px;
        }

        @media all and (max-width: 900px) {
            .mob {
                position: relative;
                margin: auto;
                width: 100%;
                left: 210px;
            }
            .outer {
                display: none;
                justify-content: none;
                align-items: none;
            }
        }
    </style>
</head>

<body>

    <div class="outer" style="display: flex; justify-content: center; align-items: center;">
        <div class="mob">
            <div>
                <img class="" src="../image/card.png" alt="image" height="300px" width="600px" />
            </div>
            <div class="uname">
                <p><?= $item->first_name . " " . $item->middle_name . " " . $item->last_name; ?></p>
            </div>
            <div class="fname">
                <p><?= $item->father_name; ?></p>
            </div>
            <div class="dob">
                <p><?= $item->dob; ?></p>
            </div>
            <div class="gender">
                <p><?= $item->gender; ?></p>
            </div>
            <div class="uid">
                <b style=" letter-spacing: 8px; "><?= $item->user_id; ?></b>
            </div>
            <div class="photo">
                <img src="<?= base_url(upload_dir($item->photo)) ?>" width="67" height="63" style="border-radius: 10px;" />
            </div>
        </div>


    </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>
        window.print();
    </script>
</body>

</html>