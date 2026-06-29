<?php
header('Content-Type: application/json');

// Start session
session_start();

// Include database connection and Razorpay config
require_once 'include/db_connect.php';
require_once 'include/razorpay_config.php';

// Retrieve JSON input from fetch request
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'No input data received.']);
    exit;
}

$razorpay_payment_id = $data['razorpay_payment_id'] ?? '';
$razorpay_order_id = $data['razorpay_order_id'] ?? '';
$razorpay_signature = $data['razorpay_signature'] ?? '';
$order_no = $data['order_no'] ?? '';

if (empty($razorpay_payment_id) || empty($razorpay_order_id) || empty($razorpay_signature) || empty($order_no)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required payment parameters.']);
    exit;
}

try {
    // Instantiate Razorpay Api
    $api = new Razorpay\Api\Api(RAZORPAY_KEY_ID, RAZORPAY_KEY_SECRET);

    // Prepare signature attributes for verification
    $attributes = [
        'razorpay_order_id' => $razorpay_order_id,
        'razorpay_payment_id' => $razorpay_payment_id,
        'razorpay_signature' => $razorpay_signature
    ];

    // Verify payment signature
    $api->utility->verifyPaymentSignature($attributes);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Payment signature verification failed: ' . $e->getMessage()
    ]);
    exit;
}

// Signature is valid! Save payment details in MySQL
$order_no_safe = mysqli_real_escape_string($conn, $order_no);

// Retrieve local order's total amount
$order_query = mysqli_query($conn, "
    SELECT id, total_amount 
    FROM orders 
    WHERE order_no = '$order_no_safe'
");

if (!$order_query || mysqli_num_rows($order_query) === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Local order record not found.']);
    exit;
}

$order_data = mysqli_fetch_assoc($order_query);
$amount = (float) $order_data['total_amount'];

$razorpay_order_id_safe = mysqli_real_escape_string($conn, $razorpay_order_id);
$razorpay_payment_id_safe = mysqli_real_escape_string($conn, $razorpay_payment_id);
$razorpay_signature_safe = mysqli_real_escape_string($conn, $razorpay_signature);

// Insert record in payments table
$insert_sql = "
    INSERT INTO payments 
    (order_no, razorpay_order_id, razorpay_payment_id, razorpay_signature, amount, currency, status, created_at)
    VALUES 
    ('$order_no_safe', '$razorpay_order_id_safe', '$razorpay_payment_id_safe', '$razorpay_signature_safe', '$amount', 'INR', 'success', NOW())
";

if (!mysqli_query($conn, $insert_sql)) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save payment record: ' . mysqli_error($conn)]);
    exit;
}

// Update order status to 'Processing' and payment_status to 'Paid'
$update_sql = "
    UPDATE orders 
    SET order_status = 'Processing',
        payment_status = 'Paid',
        razorpay_payment_id = '$razorpay_payment_id_safe',
        razorpay_signature = '$razorpay_signature_safe'
    WHERE order_no = '$order_no_safe'
";

if (!mysqli_query($conn, $update_sql)) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update order status: ' . mysqli_error($conn)]);
    exit;
}

// Fetch full order details needed for the email
$order_details_query = mysqli_query($conn, "
    SELECT order_no, customer_name, customer_email, whatsapp_number, 
           delivery_address, total_amount, razorpay_payment_id,
           (SELECT title FROM products WHERE products.id = orders.product_id) AS product_title
    FROM orders 
    WHERE order_no = '$order_no_safe'
");
$order_details = mysqli_fetch_assoc($order_details_query);

if ($order_details) {
    require_once 'include/send_order_mail.php';
    $mail_result = send_order_confirmation_mail($order_details);
    // Optional: log mail_result, but don't block the payment success response on it
}

// Return success response
echo json_encode(['status' => 'success']);
exit;

