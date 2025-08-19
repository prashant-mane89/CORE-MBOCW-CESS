<?php
// This file is a simple API endpoint to provide chart data.
// It should be placed on your web server and accessed via a URL.

session_start();
// Check if the user is authenticated. This is a crucial security step.
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(["error" => "Unauthorized access."]);
    exit;
}

require_once '../config/db.php';

// Set the content type to application/json so the browser knows how to handle the response
header('Content-Type: application/json');

$response = [
    "chartLabels" => [],
    "chartData" => [],
    "totalProjects" => 0,       // Added this
    "totalWorkOrders" => 0,     // Added this
    "totalCessDue" => 0,
    "totalCessCollected" => 0,
    "totalCessPending" => 0
];

try {
    // Fetch data for the CESS collection graph
    $sqlChartData = "
        SELECT 
            DATE_FORMAT(created_at, '%Y-%m') AS month,
            COALESCE(SUM(effective_cess_amount), 0) AS monthly_cess
        FROM 
            cess_payment_history
        WHERE
            is_payment_verified = 1 AND payment_status = 'Paid'
        GROUP BY 
            month
        ORDER BY 
            month ASC
    ";
    $resultChartData = $conn->query($sqlChartData);

    if ($resultChartData) {
        while ($row = $resultChartData->fetch_assoc()) {
            $response["chartLabels"][] = date('M Y', strtotime($row['month']));
            $response["chartData"][] = floatval($row['monthly_cess']);
        }
    }

    // Get Total CESS Due from all Work Orders
    $sqlCessDue = "SELECT COALESCE(SUM(work_order_effective_cess_amount), 0) AS total_cess_due FROM project_work_orders";
    $resultCessDue = $conn->query($sqlCessDue);
    if ($resultCessDue && $resultCessDue->num_rows > 0) {
        $row = $resultCessDue->fetch_assoc();
        $totalCessDue = floatval($row['total_cess_due']);
        $response["totalCessDue"] = $totalCessDue;
    }

    // Get Total CESS Collected (Verified Payments)
    $sqlCessCollected = "SELECT COALESCE(SUM(effective_cess_amount), 0) AS total_cess_collected FROM cess_payment_history WHERE is_payment_verified = 1 AND payment_status = 'Paid'";
    $resultCessCollected = $conn->query($sqlCessCollected);
    if ($resultCessCollected && $resultCessCollected->num_rows > 0) {
        $row = $resultCessCollected->fetch_assoc();
        $totalCessCollected = floatval($row['total_cess_collected']);
        $response["totalCessCollected"] = $totalCessCollected;
    }
    
    // Calculate CESS Pending
    $totalCessPending = $totalCessDue - $totalCessCollected;
    $response["totalCessPending"] = $totalCessPending;

    // ADDED: Query to get total projects
    $sqlTotalProjects = "SELECT COUNT(*) AS total FROM projects";
    $resultTotalProjects = $conn->query($sqlTotalProjects);
    if ($resultTotalProjects && $resultTotalProjects->num_rows > 0) {
        $row = $resultTotalProjects->fetch_assoc();
        $response["totalProjects"] = intval($row['total']);
    }

    // ADDED: Query to get total work orders
    $sqlTotalWorkOrders = "SELECT COUNT(*) AS total FROM project_work_orders";
    $resultTotalWorkOrders = $conn->query($sqlTotalWorkOrders);
    if ($resultTotalWorkOrders && $resultTotalWorkOrders->num_rows > 0) {
        $row = $resultTotalWorkOrders->fetch_assoc();
        $response["totalWorkOrders"] = intval($row['total']);
    }

    // Use number_format to format the values for display.
    $response["totalCessDueDisplay"] = number_format($response["totalCessDue"], 2);
    $response["totalCessCollectedDisplay"] = number_format($response["totalCessCollected"], 2);
    $response["totalCessPendingDisplay"] = number_format($response["totalCessPending"], 2);

    // Encode the entire response array as JSON and print it.
    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500); // Server error
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
} finally {
    if ($conn) {
        $conn->close();
    }
}

?>