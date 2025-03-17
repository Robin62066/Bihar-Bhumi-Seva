<?php
include "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$perm = getPermission(Permission::PAYMENTS);
if (!$perm->canView()) {
  session()->set_flashdata('danger', 'Your do not have access permissions');
  redirect(admin_url('dashboard.php'));
}

$ab = userdata('admin');
if ($ab == null) {
  set_flashdata('error_msg', 'You must login to continue.');
  redirect('index.php');
}
$menu = 'services';
if (isset($_GET['act']) && $_GET['act'] == 'del') {
  $id = $_GET['id'];
  $db->delete('ai_orders', ['id' => $id]);
  session()->set_flashdata('success', 'Record deleted successfully');
}
$items = $db->select('ai_orders', [], false, "id DESC")->result();
include "../common/header.php";
?>
<div id="origin">
  <div class="page-header">
    <h5>Payments Applications</h5>
  </div>
  <div class="bg-white p-3">
    <table class="table data-table">
      <thead>
        <tr>
          <th>Sl</th>
          <th>Recipt Id</th>
          <th>Created</th>
          <th>RZP Id</th>
          <th>Amount</th>
          <th>Cust name</th>
          <th>Cust mobile</th>
          <th>Status</th>
          <th>Usertype</th>
          <th>Notes</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sl = 1;
        foreach ($items as $item) {
        ?>
          <tr>
            <td><?= $sl++; ?></td>
            <td><?= $item->receipt_id; ?></td>
            <td><?= $item->created; ?></td>
            <td><?= $item->rzp_order_id; ?></td>
            <td><?= $item->amount; ?></td>
            <td><?= $item->cust_name; ?></td>
            <td><?= $item->cust_mobile; ?></td>
            <td>
              <?php
              if ($item->status == 0) echo '<span class="badge bg-warning">Pending</span>';
              if ($item->status == 1) echo '<span class="badge bg-success">Success</span>';
              if ($item->status == 2) echo '<span class="badge bg-danger">Failed</span>';
              ?>
            </td>
            <td>
              <?php
              if ($item->user_id > 0) {
                $um = $db->select('ai_users', ['id' => $item->user_id], 1)->row();
                if (is_object($um)) echo user_type_string($um->user_type);
              }
              ?>
            </td>
            <td><?= $item->notes; ?></td>
            <td>
              <div class="d-flex gap-2">
                <?php
                if ($perm->canDelete()) {
                ?>
                  <a href="<?= admin_url('services/payments.php?id=' . $item->id . '&act=del') ?>" class="btn btn-xs btn-danger btn-confirm" data-msg="Are you sure to delete?"> Delete</a>
                <?php
                }
                ?>
              </div>
            </td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php
include "../common/footer.php";
