<?php
// C:\wamp64\www\CORE-MBOCW-CESS\dashboard\save-bulk-projects-invoces-cess.php

session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Include your database connection file
require_once '../config/db.php';

// Include the Composer autoloader for PhpSpreadsheet
require_once('../vendor/autoload.php'); // Adjust path as needed

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception as ReaderException;
use PhpOffice\PhpSpreadsheet\Calculation\Exception as CalculationException;
use Razorpay\Api\Api;

// --- Razorpay Configuration (IMPORTANT: Replace with your actual keys) ---
$keyId = "rzp_test_K27QFBqZ8Wq02s"; // Replace with your key id
$keySecret = "AU11vS10Yrn9mCYI2NuOLGgg"; // Replace with your key secret

// Initialize Razorpay API
$api = new Api($keyId, $keySecret);

// Initialize statement variables to null to ensure they are always in scope for closing
$employerCheckStmt = $employerInsertStmt = null;
$localAuthorityCheckStmt = null;
$projectCategoryCheckStmt = $projectTypeCheckStmt = null;
$projectCheckStmt = $projectInsertStmt = null;
$workOrderCheckStmt = $workOrderInsertStmt = null;
$bulkProjectsInvoicesHistoryInsertStmt = null;
$cessPaymentHistoryInsertStmt = null;
$totalInvoicedWorkOrderStmt = $updateWorkOrderStatusStmt = null;
$totalInvoicedProjectStmt = $updateProjectStatusStmt = null;
$razorpayTransactionInsertStmt = null;

// Define the upload directory. Make sure this directory exists and is writable by the web server!
$uploadDir = '../uploads/bulk_upload_templates/';

// Check if the form was submitted and a file was uploaded
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['bulk_projects_invoices_cess'])) {
    
    $file = $_FILES['bulk_projects_invoices_cess'];
    $fileTmpName = $file['tmp_name'];
    $fileError = $file['error'];

    if ($fileError !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = "File upload failed with error code: " . $fileError;
        header("Location: bulk-projects-invoice-cess-upload-form.php");
        exit();
    }

    // Create a secure and unique filename to prevent conflicts and security issues
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newFileName = uniqid('bulk_upload_') . '.' . $fileExtension;
    $uploadPath = $uploadDir . $newFileName;
    
    // Attempt to move the uploaded file to its permanent location
    if (!move_uploaded_file($fileTmpName, $uploadPath)) {
        $_SESSION['error'] = "Failed to move the uploaded file.";
        header("Location: bulk-projects-invoice-cess-upload-form.php");
        exit();
    }

    // Start a database transaction for data integrity
    $conn->begin_transaction();

    try {
        // Load the spreadsheet file
        // $spreadsheet = IOFactory::load($fileTmpName);
        // $worksheet = $spreadsheet->getActiveSheet();
        // Load the spreadsheet file from its new, permanent location
        $spreadsheet = IOFactory::load($uploadPath);
        $worksheet = $spreadsheet->getActiveSheet();
        
        // Get the highest row and column to iterate through
        $highestRow = $worksheet->getHighestRow();
        
        // Prepare SQL statements once, outside the loop, for efficiency
        // 1. Check for existing employer
        $employerCheckStmt = $conn->prepare("SELECT id FROM employers WHERE email = ?");
        // 2. Insert new employer
        $employerInsertStmt = $conn->prepare("INSERT INTO employers (employer_type, name, email, phone, gstn, created_by) VALUES (?, ?, ?, ?, ?, ?)");
        // 3. Look up local authority ID from the name provided in the Excel sheet
        $localAuthorityCheckStmt = $conn->prepare("SELECT id FROM local_authorities WHERE name = ?");
        // 4. Look up project category and type IDs
        $projectCategoryCheckStmt = $conn->prepare("SELECT id FROM project_categories WHERE name = ?");
        $projectTypeCheckStmt = $conn->prepare("SELECT id FROM project_types WHERE name = ?");
        // Check for existing project
        $projectCheckStmt = $conn->prepare("SELECT id, construction_cost FROM projects WHERE project_name = ?");
        $projectInsertStmt = $conn->prepare("INSERT INTO projects (project_name, project_category_id, project_type_id, local_authority_id, construction_cost, project_start_date, project_end_date, cess_amount, status, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)");
        // 5. Insert new work order
        // Check for existing project_work_orders record
        $workOrderCheckStmt = $conn->prepare("SELECT id, work_order_amount FROM project_work_orders WHERE project_id = ? AND work_order_number = ?");
        $workOrderInsertStmt = $conn->prepare("INSERT INTO project_work_orders (project_id, work_order_number, work_order_date, work_order_amount, work_order_cess_amount, work_order_gst_cess_amount, work_order_administrative_cost, work_order_effective_cess_amount, employer_id, status, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', ?)");
        
        // 6. Bulk invoices history Insertion
        $bulkProjectsInvoicesHistoryInsertStmt = $conn->prepare("INSERT INTO bulk_projects_invoices_history (effective_cess_amount, bulk_project_invoices_template_file, cess_payment_mode, is_payment_verified) VALUES (?, ?, ?, ?)");

        // 7. Insert invoice cess payment history (This is the single, consolidated table as discussed)
        $cessPaymentHistoryInsertStmt = $conn->prepare("INSERT INTO cess_payment_history (bulk_invoice_id, project_id, workorder_id, invoice_amount, cess_amount, gst_cess_amount, administrative_cost, effective_cess_amount, employer_id, cess_payment_mode, cess_receipt_file, payment_status, is_payment_verified, invoice_upload_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // 8. Statements to check invoice totals and update status
        $totalInvoicedWorkOrderStmt = $conn->prepare("SELECT SUM(invoice_amount) AS total_invoiced FROM cess_payment_history WHERE workorder_id = ?");
        $updateWorkOrderStatusStmt = $conn->prepare("UPDATE project_work_orders SET status = 'Completed' WHERE id = ?");

        $totalInvoicedProjectStmt = $conn->prepare("SELECT SUM(invoice_amount) AS total_invoiced FROM cess_payment_history WHERE project_id = ?");
        $updateProjectStatusStmt = $conn->prepare("UPDATE projects SET status = 'Completed' WHERE id = ?");

        // Prepare statement for the new Razorpay transactions table
        $razorpayTransactionInsertStmt = $conn->prepare("INSERT INTO razorpay_transactions (order_id, user_id, bulk_invoice_id, amount, status, request_data) VALUES (?, ?, ?, ?, ?, ?)");

        // --- Bulk Project Invoices History Insertion ---
        // These values are from the form, not the Excel rows
        $templateTotalEffectiveCessAmount = isset($_POST['effective_cess_amount']) ? $_POST['effective_cess_amount'] : '';
        $bulkProjectsInvoicesTemplateFile = $newFileName;
        $cessPaymentMode = 1; // Assuming 'Online' is mode 1
        $isPaymentVerified = 2; // Assuming 'Pending Verification' is mode 2

        $bulkProjectsInvoicesHistoryInsertStmt->bind_param("dsii", $templateTotalEffectiveCessAmount, $bulkProjectsInvoicesTemplateFile, $cessPaymentMode, $isPaymentVerified);
        $bulkProjectsInvoicesHistoryInsertStmt->execute();
        $bulkInvoiceId = $bulkProjectsInvoicesHistoryInsertStmt->insert_id;

        // Initialize variables for tracking progress
        $rowCount = 0;
        $successfulInserts = 0;
        $errors = [];

        // Loop through each row of the worksheet, starting from the second row (skipping the header)
        for ($row = 2; $row <= $highestRow; ++$row) {
            $rowCount++;

            try {
                // Get cell values from the row, ensuring type safety and handling nulls
                // It's good practice to use getCalculatedValue() for formula support
                $projectName = trim($worksheet->getCell('B' . $row)->getCalculatedValue() ?? '');
                $projectCategoryName = trim($worksheet->getCell('C' . $row)->getCalculatedValue() ?? '');
                $projectTypeName = trim($worksheet->getCell('D' . $row)->getCalculatedValue() ?? '');
                $localAuthorityName = trim($worksheet->getCell('E' . $row)->getCalculatedValue() ?? '');
                $constructionCost = floatval($worksheet->getCell('F' . $row)->getCalculatedValue() ?? 0.0);

                // Handle date conversion carefully
                $projectStartDateValue = $worksheet->getCell('G' . $row)->getCalculatedValue();
                $projectStartDate = null;
                if (!empty($projectStartDateValue)) {
                    $projectStartDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($projectStartDateValue)->format('Y-m-d');
                }

                $projectEndDateValue = $worksheet->getCell('H' . $row)->getCalculatedValue();
                $projectEndDate = null;
                if (!empty($projectEndDateValue)) {
                    $projectEndDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($projectEndDateValue)->format('Y-m-d');
                }

                $workOrderNumber = trim($worksheet->getCell('I' . $row)->getCalculatedValue() ?? '');
                $workOrderDateValue = $worksheet->getCell('J' . $row)->getCalculatedValue();
                $workOrderDate = null;
                if (!empty($workOrderDateValue)) {
                    $workOrderDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($workOrderDateValue)->format('Y-m-d');
                }
                $workOrderAmount = floatval($worksheet->getCell('K' . $row)->getCalculatedValue() ?? 0.0);
                
                $invoiceAmount = floatval($worksheet->getCell('L' . $row)->getCalculatedValue() ?? 0.0);
                $cessAmount = floatval($worksheet->getCell('M' . $row)->getCalculatedValue() ?? 0.0);
                $gstCessAmount = floatval($worksheet->getCell('N' . $row)->getCalculatedValue() ?? 0.0);
                $administrativeCost = floatval($worksheet->getCell('O' . $row)->getCalculatedValue() ?? 0.0);
                $effectiveCessAmount = floatval($worksheet->getCell('P' . $row)->getCalculatedValue() ?? 0.0);

                $employerType = trim($worksheet->getCell('Q' . $row)->getCalculatedValue() ?? '');
                $employerName = trim($worksheet->getCell('R' . $row)->getCalculatedValue() ?? '');
                $employerEmail = trim($worksheet->getCell('S' . $row)->getCalculatedValue() ?? '');
                $employerMobile = trim($worksheet->getCell('T' . $row)->getCalculatedValue() ?? '');
                $employerGstn = trim($worksheet->getCell('U' . $row)->getCalculatedValue() ?? '');

                $cessPaymentMode = 1; // hardcoded as per business logic for bulk uploads
                $createdBy = $_SESSION['user_id'];
                
                // Initialize IDs to null
                $employerId = null;
                $projectId = null;
                $workOrderId = null;
                $localAuthorityId = null;
                $projectCategoryId = null;
                $projectTypeId = null;
                $projectConstructionCost = 0.0;
                $workOrderTotalAmount = 0.0;

                // --- VALIDATION CHECKS BEFORE PROCESSING ---
                // Skip the row if required data is missing.
                if (empty($employerType) || empty($employerEmail) || empty($employerName)) {
                    $errors[] = "Row {$rowCount} skipped: Missing required employer information (Type, Email, Name).";
                    continue; // Skip to the next row
                }
                if (empty($localAuthorityName)) {
                     $errors[] = "Row {$rowCount} skipped: Missing local authority name.";
                    continue;
                }
                if (empty($projectCategoryName)) {
                    $errors[] = "Row {$rowCount} skipped: Missing project category name.";
                    continue;
                }
                if (empty($projectTypeName)) {
                    $errors[] = "Row {$rowCount} skipped: Missing project type name.";
                    continue;
                }
                if (empty($projectName) || empty($workOrderNumber)) {
                    $errors[] = "Row {$rowCount} skipped: Missing Project Name or Work Order Number.";
                    continue;
                }
                // --- 1. Employer Insertion/Lookup ---
                $employerCheckStmt->bind_param("s", $employerEmail);
                $employerCheckStmt->execute();
                $result = $employerCheckStmt->get_result();
                if ($result->num_rows > 0) {
                    $employer = $result->fetch_assoc();
                    $employerId = $employer['id'];
                } else {
                    $employerInsertStmt->bind_param("sssssi", $employerType, $employerName, $employerEmail, $employerMobile, $employerGstn, $createdBy);
                    $employerInsertStmt->execute();
                    $employerId = $employerInsertStmt->insert_id;
                }

                // --- 2. Local Authority Lookup ---
                $localAuthorityCheckStmt->bind_param("s", $localAuthorityName);
                $localAuthorityCheckStmt->execute();
                $result = $localAuthorityCheckStmt->get_result();
                if ($result->num_rows > 0) {
                    $authority = $result->fetch_assoc();
                    $localAuthorityId = $authority['id'];
                } else {
                    $errors[] = "Row {$rowCount} skipped: Local authority '{$localAuthorityName}' not found in database.";
                    continue;
                }

                // --- 3. Project Category & Type Lookup ---
                $projectCategoryCheckStmt->bind_param("s", $projectCategoryName);
                $projectCategoryCheckStmt->execute();
                $result = $projectCategoryCheckStmt->get_result();
                if ($result->num_rows > 0) {
                    $category = $result->fetch_assoc();
                    $projectCategoryId = $category['id'];
                } else {
                    $errors[] = "Row {$rowCount} skipped: Project category '{$projectCategoryName}' not found in database.";
                    continue;
                }
                $projectTypeCheckStmt->bind_param("s", $projectTypeName);
                $projectTypeCheckStmt->execute();
                $result = $projectTypeCheckStmt->get_result();
                if ($result->num_rows > 0) {
                    $type = $result->fetch_assoc();
                    $projectTypeId = $type['id'];
                } else {
                    $errors[] = "Row {$rowCount} skipped: Project type '{$projectTypeName}' not found in database.";
                    continue;
                }

                // --- 4. Project Lookup/Insertion ---
                $projectCheckStmt->bind_param("s", $projectName);
                $projectCheckStmt->execute();
                $result = $projectCheckStmt->get_result();
                if ($result->num_rows > 0) {
                    // Project exists, get its ID and construction cost
                    $project = $result->fetch_assoc();
                    $projectId = $project['id'];
                    $projectConstructionCost = $project['construction_cost'];
                } else {
                    // Project does not exist, insert a new record
                    $projectInsertStmt->bind_param("siiidssdi", $projectName, $projectCategoryId, $projectTypeId, $localAuthorityId, $constructionCost, $projectStartDate, $projectEndDate, $cessAmount, $createdBy);
                    $projectInsertStmt->execute();
                    $projectId = $projectInsertStmt->insert_id;
                    $projectConstructionCost = $constructionCost;
                }
                
                // --- 5. Work Order Insertion/Lookup & VALIDATION ---
                $workOrderCheckStmt->bind_param("is", $projectId, $workOrderNumber);
                $workOrderCheckStmt->execute();
                $result = $workOrderCheckStmt->get_result();
                if ($result->num_rows > 0) {
                    // Work order exists, retrieve its ID and total amount
                    $workOrder = $result->fetch_assoc();
                    $workOrderId = $workOrder['id'];
                    $workOrderTotalAmount = $workOrder['work_order_amount'];
                    $errors[] = "Row {$rowCount} - Note: A work order with number '{$workOrderNumber}' for project '{$projectName}' already exists. Inserting invoice data to existing record.";
                } else {
                    // Work order does not exist, insert it
                    // NOTE: The calculation of cess amounts here seems to be a hardcoded business rule.
                    // If these percentages change, you'll need to update this code.
                    $workOrderCessAmount = $workOrderAmount * 0.01;
                    $workOrderGstCessAmount = $workOrderCessAmount * 1.025; // This seems to be the Cess amount + GST on Cess (2.5%)
                    $workOrderAdministrativeCost = $workOrderGstCessAmount * 0.01;
                    $workOrderEffectiveCessAmount = $workOrderGstCessAmount - $workOrderAdministrativeCost;
                    
                    $workOrderInsertStmt->bind_param("issdddddii", $projectId, $workOrderNumber, $workOrderDate, $workOrderAmount, $workOrderCessAmount, $workOrderGstCessAmount, $workOrderAdministrativeCost, $workOrderEffectiveCessAmount, $employerId, $createdBy);
                    $workOrderInsertStmt->execute();
                    $workOrderId = $workOrderInsertStmt->insert_id;
                    $workOrderTotalAmount = $workOrderAmount; // Since it's new, the total is the current amount
                }
                
                // --- 6. Invoice Payment History Insertion ---
                $cessReceiptFile = '';
                $isPaymentVerified = 2; // default 2 until admin verifies payment received.
                $paymentStatus = 'Pending';
                
                // Get total previously invoiced amount for this work order from the consolidated table
                $totalInvoicedWorkOrderStmt->bind_param("i", $workOrderId);
                $totalInvoicedWorkOrderStmt->execute();
                $invoicedResult = $totalInvoicedWorkOrderStmt->get_result();
                $invoicedData = $invoicedResult->fetch_assoc();
                $totalPreviouslyInvoiced = $invoicedData['total_invoiced'] ?? 0;
                
                $remainingWorkOrderAmount = $workOrderTotalAmount - $totalPreviouslyInvoiced;

                $invoiceUploadType = 'bulk'; // enum('bulk', 'single')	

                // Check if the new invoice amount is greater than the remaining work order amount
                if ($invoiceAmount > $remainingWorkOrderAmount) {
                    $errors[] = "Row {$rowCount} skipped: Invoice amount ({$invoiceAmount}) exceeds the remaining work order amount ({$remainingWorkOrderAmount}) for work order '{$workOrderNumber}'.";
                } else {
                    // Invoice Cess Payment History Insertion ---
                    // This correctly inserts into the single `cess_payment_history` table as discussed previously.
                    $cessPaymentHistoryInsertStmt->bind_param("iiidddddiissis", $bulkInvoiceId, $projectId, $workOrderId, $invoiceAmount, $cessAmount, $gstCessAmount, $administrativeCost, $effectiveCessAmount, $employerId, $cessPaymentMode, $cessReceiptFile, $paymentStatus, $isPaymentVerified, $invoiceUploadType);
                    $cessPaymentHistoryInsertStmt->execute();
                }
                
                // --- 7. Check and update work order status ---
                // Get the newly updated total for the work order
                $totalInvoicedWorkOrderStmt->bind_param("i", $workOrderId);
                $totalInvoicedWorkOrderStmt->execute();
                $invoicedResult = $totalInvoicedWorkOrderStmt->get_result();
                $invoicedData = $invoicedResult->fetch_assoc();
                $newTotalInvoicedWorkOrder = $invoicedData['total_invoiced'] ?? 0;
                
                // Compare with the work order's total amount
                // Using float comparison with a small epsilon can be safer than direct comparison
                if (abs($newTotalInvoicedWorkOrder - $workOrderTotalAmount) < 0.01 || $newTotalInvoicedWorkOrder > $workOrderTotalAmount) {
                    $updateWorkOrderStatusStmt->bind_param("i", $workOrderId);
                    $updateWorkOrderStatusStmt->execute();
                }

                // --- 8. Check and update project status ---
                $totalInvoicedProjectStmt->bind_param("i", $projectId);
                $totalInvoicedProjectStmt->execute();
                $projectInvoicedResult = $totalInvoicedProjectStmt->get_result();
                $projectInvoicedData = $projectInvoicedResult->fetch_assoc();
                $totalInvoicedForProject = $projectInvoicedData['total_invoiced'] ?? 0;

                // Again, using a float comparison for safety
                if (abs($totalInvoicedForProject - $projectConstructionCost) < 0.01 || $totalInvoicedForProject > $projectConstructionCost) {
                    $updateProjectStatusStmt->bind_param("i", $projectId);
                    $updateProjectStatusStmt->execute();
                }

                $successfulInserts++;
                
            } catch (CalculationException $e) {
                $errors[] = "Row {$rowCount} skipped due to a formula error: " . $e->getMessage();
            } catch (\Exception $e) {
                // Catch any other general exceptions during the loop
                $errors[] = "Error processing row {$rowCount}: " . $e->getMessage();
            }

        }

        // Final check on total processed rows
        if ($successfulInserts > 0) {
            // If data was successfully processed, proceed with Razorpay order creation
            $amountInPaisa = round($templateTotalEffectiveCessAmount * 100);
            
            $orderData = [
                'amount' => $amountInPaisa,
                'currency' => 'INR',
                'receipt' => 'bulk_invoice_' . $bulkInvoiceId,
                'notes' => [
                    'bulk_invoice_id' => $bulkInvoiceId,
                    'user_id' => $_SESSION['user_id'],
                ]
            ];
            
            $razorpayOrder = $api->order->create($orderData);
            
            // Log the order creation in the new table
            $orderId = $razorpayOrder['id'];
            $requestData = json_encode($orderData);
            $razorpayTransactionInsertStmt->bind_param("siidss", $orderId, $_SESSION['user_id'], $bulkInvoiceId, $templateTotalEffectiveCessAmount, $razorpayOrder['status'], $requestData);
            $razorpayTransactionInsertStmt->execute();

            // Commit the database transaction
            $conn->commit();
            
            // Set success message and redirect to the Razorpay checkout page
            $_SESSION['razorpay_checkout'] = [
                'order_id' => $orderId,
                'amount' => $amountInPaisa,
                'description' => 'Cess Payment for Bulk Invoice Upload',
                'name' => 'MBOCW-CESS Portal',
                'image' => 'https://www.your-website.com/path/to/your/logo.png', // Replace with your logo URL
                'currency' => 'INR',
                'notes' => $razorpayOrder['notes'],
            ];

            // Set the success message to be displayed after payment
            $_SESSION['success'] = "Successfully uploaded and saved {$successfulInserts} projects. Redirecting to payment page...";
            header("Location: razorpay-checkout.php");
            exit();

        } else {
            // If no data was processed, rollback and show an error
            $conn->rollback();
            $message = "No valid data found in the uploaded file.";
            if (!empty($errors)) {
                $message .= " Details: " . implode(" ", $errors);
            }
            $_SESSION['error'] = $message;
            header("Location: bulk-projects-invoice-cess-upload-form.php");
            exit();
        }

        // // If all good, commit the transaction
        // $conn->commit();

        // // Set a success message
        // $message = "Successfully uploaded and saved {$successfulInserts} projects.";
        // if (!empty($errors)) {
        //     $message .= " <br>Some rows were skipped due to errors: " . implode("<br>", $errors);
        //     $_SESSION['error'] = $message;
        // } else {
        //     $_SESSION['success'] = $message;
        // }

    } catch (ReaderException $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error reading the Excel file: " . $e->getMessage();
    } catch (\Exception $e) {
        // Handle any exceptions outside the loop
        $conn->rollback();
        $_SESSION['error'] = "An unexpected error occurred: " . $e->getMessage();
    }
} else {
    // If the form wasn't submitted correctly
    $_SESSION['error'] = "Invalid request.";
}

// A more robust way to close all prepared statements and the connection
// This ensures they are closed even if an exception occurs
if ($employerCheckStmt) $employerCheckStmt->close();
if ($employerInsertStmt) $employerInsertStmt->close();
if ($localAuthorityCheckStmt) $localAuthorityCheckStmt->close();
if ($projectCategoryCheckStmt) $projectCategoryCheckStmt->close();
if ($projectTypeCheckStmt) $projectTypeCheckStmt->close();
if ($projectCheckStmt) $projectCheckStmt->close();
if ($projectInsertStmt) $projectInsertStmt->close();
if ($workOrderCheckStmt) $workOrderCheckStmt->close();
if ($workOrderInsertStmt) $workOrderInsertStmt->close();
if ($bulkProjectsInvoicesHistoryInsertStmt) $bulkProjectsInvoicesHistoryInsertStmt->close();
if ($cessPaymentHistoryInsertStmt) $cessPaymentHistoryInsertStmt->close();
if ($totalInvoicedWorkOrderStmt) $totalInvoicedWorkOrderStmt->close();
if ($updateWorkOrderStatusStmt) $updateWorkOrderStatusStmt->close();
if ($totalInvoicedProjectStmt) $totalInvoicedProjectStmt->close();
if ($updateProjectStatusStmt) $updateProjectStatusStmt->close();
if ($razorpayTransactionInsertStmt) $razorpayTransactionInsertStmt->close();

$conn->close();

// Redirect back to the form page
header("Location: bulk-projects-invoice-cess-upload-form.php");
exit();
?>
