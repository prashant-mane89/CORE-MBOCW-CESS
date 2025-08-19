<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Include your database connection file
require_once '../config/db.php';

// Initialize variables to hold the dynamic data
$totalProjects = 0;
$totalWorkOrders = 0;
$totalCessCollected = 0.0;
$cessPending = 0.0;
// We will assume a hardcoded value for "Funds transferred" for now, as we don't have
// a database table for it. A query would be needed here for a real-world scenario.
$totalCessDue = 0.0;

try {
    // --- 1. Get Total Projects Count ---
    $sqlProjects = "SELECT COUNT(*) AS total_projects FROM projects";
    $resultProjects = $conn->query($sqlProjects);
    if ($resultProjects && $resultProjects->num_rows > 0) {
        $row = $resultProjects->fetch_assoc();
        $totalProjects = $row['total_projects'];
    }

    // --- 2. Get Total Work Orders Count ---
    $sqlWorkOrders = "SELECT COUNT(*) AS total_work_orders FROM project_work_orders";
    $resultWorkOrders = $conn->query($sqlWorkOrders);
    if ($resultWorkOrders && $resultWorkOrders->num_rows > 0) {
        $row = $resultWorkOrders->fetch_assoc();
        $totalWorkOrders = $row['total_work_orders'];
    }

    // --- 3. Get Total CESS Due from all Work Orders ---
    // This query gets the total CESS amount from all work orders, which is the total amount to be collected.
    $sqlCessDue = "SELECT COALESCE(SUM(work_order_effective_cess_amount), 0) AS total_cess_due FROM project_work_orders";
    $resultCessDue = $conn->query($sqlCessDue);
    if ($resultCessDue && $resultCessDue->num_rows > 0) {
        $row = $resultCessDue->fetch_assoc();
        $totalCessDue = floatval($row['total_cess_due']);
    }

    // --- 4. Get Total CESS Collected (Verified Payments) ---
    // This query gets the total CESS amount that has been collected and verified.
    $sqlCessCollected = "SELECT COALESCE(SUM(effective_cess_amount), 0) AS total_cess_collected FROM cess_payment_history WHERE is_payment_verified = 1 AND payment_status = 'Paid'";
    $resultCessCollected = $conn->query($sqlCessCollected);
    if ($resultCessCollected && $resultCessCollected->num_rows > 0) {
        $row = $resultCessCollected->fetch_assoc();
        $totalCessCollected = floatval($row['total_cess_collected']);
    }
    
    // --- 5. Calculate CESS Pending ---
    // The pending amount is the difference between the total due and the amount collected.
    $totalCessPending = $totalCessDue - $totalCessCollected;

    // Format all monetary values for display
    $totalCessDueDisplay = number_format($totalCessDue, 2);
    $totalCessCollectedDisplay = number_format($totalCessCollected, 2);
    $totalCessPendingDisplay = number_format($totalCessPending, 2);

} catch (Exception $e) {
    // Handle database connection or query errors
    // In a real application, you'd log this error
    // For now, we will just set the values to 0
    error_log("Database error on dashboard: " . $e->getMessage());
    $totalProjects = 0;
    $totalWorkOrders = 0;
    $totalCessCollected = 0.0;
    $cessPending = 0.0;
} finally {
    // Close the database connection
    if ($conn) {
        $conn->close();
    }
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

  <title>MBOCWCESS Portal | Dashboard</title>
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
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <!-- Total Projects Box -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $totalProjects; ?></h3>
                <p>Total Projects</p>
              </div>
              <div class="icon">
                <i class="fas fa-building"></i>
              </div>
              <a href="projects.php" class="small-box-footer">More Info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
           <!-- Total Projects Work Orders Box -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $totalWorkOrders; ?></h3>
                <p>Total Projects Work Orders</p>
              </div>
              <div class="icon">
                <i class="fas fa-tasks"></i>
              </div>
              <a href="projects.php" class="small-box-footer">More Info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <!-- Total CESS Box -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3>₹<?php echo $totalCessDueDisplay; ?></h3>
                <p>Total CESS</p>
              </div>
              <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
              </div>
              <a href="reports.php" class="small-box-footer">More Info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <!-- Total CESS Collected Box -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3>₹<?php echo $totalCessCollectedDisplay; ?></h3>
                <p>Total CESS Collected</p>
              </div>
              <div class="icon">
                <i class="fas fa-check-circle"></i>
              </div>
              <a href="reports.php" class="small-box-footer">More Info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <!-- CESS Pending Box -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>₹<?php echo $totalCessPendingDisplay; ?></h3>
                <p>CESS Pending</p>
              </div>
              <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
              </div>
              <a href="reports.php" class="small-box-footer">More Info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <div class="row">
          <div class="col-md-12">
              <!-- solid cess collection graph -->
              <div class="card bg-gradient-info">
                  <div class="card-header border-0">
                      <h3 class="card-title">
                          <i class="fas fa-th mr-1"></i>
                          Cess Collection Graph
                      </h3>
                      <div class="card-tools">
                          <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                              <i class="fas fa-minus"></i>
                          </button>
                          <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                              <i class="fas fa-times"></i>
                          </button>
                      </div>
                  </div>
                  <div class="card-body">
                      <canvas class="chart" id="line-chart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer bg-transparent">
                      <div class="row">
                          <div class="col-4 text-center">
                              <div class="text-white">Total CESS Due</div>
                              <div class="text-white font-weight-bold" id="footer-cess-due"></div>
                          </div>
                          <!-- ./col -->
                          <div class="col-4 text-center">
                              <div class="text-white">Total CESS Collected</div>
                              <div class="text-white font-weight-bold" id="footer-cess-collected"></div>
                          </div>
                          <!-- ./col -->
                          <div class="col-4 text-center">
                              <div class="text-white">Total Pending CESS</div>
                              <div class="text-white font-weight-bold" id="footer-cess-pending"></div>
                          </div>
                          <!-- ./col -->
                      </div>
                      <!-- /.row -->
                  </div>
                  <!-- /.card-footer -->
              </div>
          </div>
        </div>
        <!-- /.row -->
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
<!-- ChartJS -->
<script src="../plugins/chart.js/Chart.min.js"></script>
<script>
  $(function () {
    'use strict';

    // Function to fetch data from the server and render the chart and other stats
    const fetchDataAndRenderDashboard = async () => {
      try {
        // Fetch the data from the new PHP API endpoint
        const response = await fetch('get_chart_data.php');
        
        // Check if the response was successful
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        // Parse the JSON data from the response
        const data = await response.json();

        // Get the chart labels and data from the JSON response
        var chartLabels = data.chartLabels;
        var chartData = data.chartData;

        // Update the dashboard statistics.
        // You will need to add IDs to your HTML elements (e.g., id="total-projects")
        // to make them selectable.
        $('#total-projects').text(data.totalProjects);
        $('#total-work-orders').text(data.totalWorkOrders);
        $('#total-cess-due').text(`₹${data.totalCessDueDisplay}`);
        $('#total-cess-collected').text(`₹${data.totalCessCollectedDisplay}`);
        $('#total-cess-pending').text(`₹${data.totalCessPendingDisplay}`);

        // Also update the footer values
        $('#footer-cess-due').text(`₹${data.totalCessDueDisplay}`);
        $('#footer-cess-collected').text(`₹${data.totalCessCollectedDisplay}`);
        $('#footer-cess-pending').text(`₹${data.totalCessPendingDisplay}`);

        // Get context with jQuery - using jQuery's .get() method.
        var salesGraphChartCanvas = $('#line-chart').get(0).getContext('2d');

        var salesGraphChartData = {
          labels: chartLabels,
          datasets: [
            {
              label: 'CESS Collected (₹)',
              fill: false,
              borderWidth: 2,
              lineTension: 0,
              spanGaps: true,
              borderColor: '#fff',
              pointRadius: 3,
              pointHoverRadius: 7,
              pointColor: '#fff',
              pointBackgroundColor: '#fff',
              data: chartData
            }
          ]
        }

        var salesGraphChartOptions = {
          maintainAspectRatio: false,
          responsive: true,
          legend: {
              display: false
          },
          scales: {
              xAxes: [{
                  ticks: {
                      fontColor: '#fff',
                      maxTicksLimit: 12
                  },
                  gridLines: {
                      display: false,
                      color: 'rgba(255, 255, 255, 0.2)'
                  }
              }],
              yAxes: [{
                  ticks: {
                      fontColor: '#fff',
                      callback: function(value, index, values) {
                          return '₹' + value.toLocaleString();
                      }
                  },
                  gridLines: {
                      display: true,
                      color: 'rgba(255, 255, 255, 0.2)'
                  }
              }]
          },
          tooltips: {
              mode: 'index',
              intersect: false,
              callbacks: {
                  label: function(tooltipItem, data) {
                      var label = data.datasets[tooltipItem.datasetIndex].label || '';
                      if (label) {
                          label += ': ';
                      }
                      label += '₹' + tooltipItem.yLabel.toLocaleString();
                      return label;
                  }
              }
          }
        }

        // This will create the chart passing the Chart.js library to the canvas
        var salesGraphChart = new Chart(salesGraphChartCanvas, {
          type: 'line',
          data: salesGraphChartData,
          options: salesGraphChartOptions
        })
          
      } catch (error) {
        console.error("Error fetching or rendering chart data:", error);
        // You can display a user-friendly error message on the page here
        // For example, replace placeholders with an error state
        $('#total-projects').text("Error");
        $('#total-work-orders').text("Error");
        // etc...
      }
    };

    // Call the function to fetch and render the dashboard data when the page loads
    fetchDataAndRenderDashboard();
  })
</script>
</body>
</html>
