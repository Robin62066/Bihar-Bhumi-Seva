<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect('login.php', 'Session logout. Login again', 'danger');

$items = $db->select('ai_users', ['kyc_status' => KYC_PROCESSING])->result_array();

$menu = 'members';
include "../common/header.php";
?>

<h5>All Information</h5>
<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>क्रम सांख्य</th>
                <th>नाम</th>
                <th>आधार नंबर</th>
                <th>पैन नंबर</th>
                <th>मोबाइल नंबर</th>
                <th>ईमेल आईडी</th>
                <th>खाते का प्रकार</th>
                <th>देखना</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $serialNo = 1; // Counter for serial number
            foreach ($items as $item) {
                $user_type = $item['account_type'];
                $st = '';
                if ($user_type ==  USER_CUSTOMER) $st = 'Customer';
                else if ($user_type == USER_LAND_OWNER) $st = 'Land Owner';
                else if ($user_type == USER_BROKER) $st = 'Broker';
                else if ($user_type == USER_MUNSI) $st = 'Munsi';
                else if ($user_type == USER_AMIN) $st = 'Amin';

                // Display each row in the table
                echo "<tr>";
                echo "<td>" . $serialNo++ . "</td>";
                echo "<td>" . $item['first_name'] . ' ' . $item['last_name'] . "</td>";
                echo "<td>" . $item['aadhar_number'] . "</td>";
                echo "<td>" . $item['pan_number'] . "</td>";
                echo "<td>" . $item['mobile_number'] . "</td>";
                echo "<td>" . $item['email_id'] . "</td>";
                echo "<td>" . $st . "</td>";
                echo "<td><a href='" . admin_url('users/kyc-details.php?id=' . $item['id']) . "' class='btn btn-primary btn-xs'   >View</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
include "../common/footer.php";
?>