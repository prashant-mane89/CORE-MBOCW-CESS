<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
require_once '../config/db.php';

if (!isset($_GET['id'])) {
    die("Missing ID.");
}
$id = intval($_GET['id']);

$query = "
SELECT 
    b.id AS bill_id,
    b.title,
    b.total_amount,
    COALESCE(SUM(h.amount_paid), 0) AS total_paid,
    (b.total_amount - COALESCE(SUM(h.amount_paid), 0)) AS balance_amount,
    b.payment_status,
    b.bill_date,
    b.bill_photo,
    h.payment_mode
FROM bills b
LEFT JOIN bill_payment_history h ON h.bill_id = b.id
WHERE b.id = ?
AND b.deleted_at IS NULL
GROUP BY b.id
ORDER BY b.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$bill = $result->fetch_assoc();

if (!$bill) {
    die("Bill not found.");
}


?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Medical POS System Desk | View Bill Details</title>
  <link rel="icon" href="../assets/img/favicon_io/favicon.ico" type="image/x-icon">

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <?php include('includes/navbar.php'); ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php include('includes/sidebar.php'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">View Bill Details</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">View Bill Details</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">View Bill Details</h3>
                    <div class="card-tools">
                        <a href="bills.php" class="btn btn-primary" ><i class="fas fa-eye"></i> Bill List</a> 
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-12">
                            
                            <form action="update-bill.php" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="bill_id" value="<?php echo $bill['bill_id']; ?>">
                                <div class="form-group">
                                    <label>Title</label>
                                    <?php echo isset($bill['title'])?$bill['title']:''; ?>
                                </div>
                                <div class="form-group">
                                    <label>Bill Total Amount</label>
                                    <?php echo isset($bill['total_amount'])?$bill['total_amount']:''; ?>
                                </div>
                                <div class="form-group">
                                    <label>Bill Date</label>
                                    <?php echo isset($bill['bill_date'])?$bill['bill_date']:''; ?>
                                </div>
                                <div class="form-group">
                                    <label>Payment Status</label>
                                    <?php if($bill['payment_status']=='Paid'){echo'selected';} ?>
                                    <?php if($bill['payment_status']=='Unpaid'){echo'selected';} ?>
                                    <?php if($bill['payment_status']=='Partial'){echo'selected';} ?>
                                </div>

                                <div class="form-group" id="unpaid_reason_div" style="display:none; margin-top: 10px;">
                                    <label for="unpaid_reason">Reason for Unpaid:</label>
                                    <?php echo isset($bill['unpaid_reason'])?$bill['unpaid_reason']:''; ?>
                                </div>
                                <div class="form-group" id="partial_amount_div" style="display:none; margin-top: 10px;">
                                    <label for="partial_amount">Partial Payment Amount:</label>
                                    <?php echo isset($bill['amount_paid'])?$bill['amount_paid']:''; ?>
                                </div>

                                <div class="form-group">
                                    <label>Payment Mode</label>
                                    <?php if($bill['payment_mode']==1){echo'Cash';} ?>
                                    <?php if($bill['payment_mode']==2){echo'Net Banking';} ?>
                                    <?php if($bill['payment_mode']==3){echo'UPI';} ?>
                                    <?php if($bill['payment_mode']==4){echo'Credit Card';} ?>
                                    <?php if($bill['payment_mode']==5){echo'Debit Card';} ?>
                                    <?php if($bill['payment_mode']==6){echo'Cheque';} ?>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Bill Photo:</label>
                                            <?php if (!empty($bill['bill_photo'])): ?>
                                                <img src="../uploads/bills/<?php echo $bill['bill_photo']; ?>" class="img img-responsive" alt="Bill Photo" style="max-height: 150px;">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <?php include('includes/footer.php'); ?>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

</body>
</html>
