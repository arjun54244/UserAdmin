<?php
header('Content-Type: application/json');

// Start session
session_start();

// Include database and Razorpay configuration
require_once 'include/db_connect.php';
require_once 'include/razorpay_config.php';

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'errors' => ['Invalid request method.']]);
    exit;
}

// Collector & Sanitiser helper
function safe_input($conn, $val) {
    return mysqli_real_escape_string($conn, trim(strip_tags($val)));
}

$product_id       = (int)($_POST['product_id'] ?? 0);
$frame_size_id    = (int)($_POST['frame_size_id'] ?? 0);
$order_type       = safe_input($conn, $_POST['order_type'] ?? '');
$customer_name    = safe_input($conn, $_POST['customer_name'] ?? '');
$whatsapp_number  = safe_input($conn, $_POST['whatsapp_number'] ?? '');
$delivery_address = safe_input($conn, $_POST['delivery_address'] ?? '');
$notes            = safe_input($conn, $_POST['notes'] ?? '');

$errors = [];

// Validate fields
if ($frame_size_id <= 0)     $errors[] = "Please select a frame size.";
if (empty($customer_name))   $errors[] = "Your name is required.";
if (empty($whatsapp_number)) $errors[] = "WhatsApp number is required.";
if (!preg_match('/^[0-9]{10}$/', $whatsapp_number)) $errors[] = "Enter a valid 10-digit WhatsApp number.";
if (empty($delivery_address)) $errors[] = "Delivery address is required.";

$allowed_order_types = ['prebuilt', 'custom_photo', 'custom_logo'];
if (!in_array($order_type, $allowed_order_types)) $errors[] = "Invalid order type.";

if (!empty($errors)) {
    echo json_encode(['status' => 'error', 'errors' => $errors]);
    exit;
}

// Check product details if product_id is provided
$product_title = "Custom Glass Carved Portrait Art";
$product_id_sql = "NULL";
if ($product_id > 0) {
    $prod_check = mysqli_query($conn, "SELECT id, title FROM products WHERE id='$product_id'");
    if ($prod_check && mysqli_num_rows($prod_check) > 0) {
        $product_data = mysqli_fetch_assoc($prod_check);
        $product_title = $product_data['title'];
        $product_id_sql = "'" . $product_id . "'";
    }
}

// Validate Frame Size exists & retrieve price
$frame_check = mysqli_query($conn, "SELECT id, price FROM frame_sizes WHERE id='$frame_size_id' AND status=1");
if (!$frame_check || mysqli_num_rows($frame_check) == 0) {
    echo json_encode(['status' => 'error', 'errors' => ["Selected frame size not found or inactive."]]);
    exit;
}
$frame_data = mysqli_fetch_assoc($frame_check);
$price = (int)$frame_data['price'];

// Handle image upload if custom
$customer_image = '';
if (in_array($order_type, ['custom_photo', 'custom_logo'])) {
    if (empty($_FILES['client_image']['name'])) {
        echo json_encode(['status' => 'error', 'errors' => ["Please upload your " . ($order_type == 'custom_photo' ? "photo" : "logo/design") . "."]]);
        exit;
    } else {
        $allowed_types = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];
        $max_size      = 5 * 1024 * 1024; // 5 MB

        $file_type = $_FILES['client_image']['type'];
        $file_size = $_FILES['client_image']['size'];
        $file_name = $_FILES['client_image']['name'];
        $file_tmp  = $_FILES['client_image']['tmp_name'];
        $file_error= $_FILES['client_image']['error'];

        if ($file_error !== UPLOAD_ERR_OK) {
            echo json_encode(['status' => 'error', 'errors' => ["File upload error. Please try again."]]);
            exit;
        } elseif (!in_array($file_type, $allowed_types)) {
            echo json_encode(['status' => 'error', 'errors' => ["Only JPG, PNG, or WebP images are allowed."]]);
            exit;
        } elseif ($file_size > $max_size) {
            echo json_encode(['status' => 'error', 'errors' => ["Image must be under 5MB."]]);
            exit;
        } else {
            $ext        = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $clean_name = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $file_name);
            $new_name   = time() . '_' . rand(1000, 9999) . '_' . $clean_name;

            $upload_dir = 'uploads/orders/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

            if (move_uploaded_file($file_tmp, $upload_dir . $new_name)) {
                $customer_image = mysqli_real_escape_string($conn, $new_name);
            } else {
                echo json_encode(['status' => 'error', 'errors' => ["Failed to save uploaded file. Please try again."]]);
                exit;
            }
        }
    }
}

// Generate unique order number
function generate_order_no($conn) {
    do {
        $order_no = 'ORD-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        $check = mysqli_query($conn, "SELECT id FROM orders WHERE order_no='$order_no'");
    } while (mysqli_num_rows($check) > 0);
    return $order_no;
}

$order_no = generate_order_no($conn);
$order_no_safe = mysqli_real_escape_string($conn, $order_no);

// Add order_type to notes
$full_notes = "[Type: " . ucfirst(str_replace('_', ' ', $order_type)) . "] " . $notes;
$full_notes_safe = mysqli_real_escape_string($conn, $full_notes);

// Calculate price in paise
$amount_in_paise = $price * 100;

try {
    // Instantiate Razorpay Api
    $api = new Razorpay\Api\Api(RAZORPAY_KEY_ID, RAZORPAY_KEY_SECRET);

    // Create Razorpay Order
    $razorpayOrder = $api->order->create([
        'receipt'         => $order_no,
        'amount'          => $amount_in_paise,
        'currency'        => 'INR'
    ]);

    $razorpay_order_id = $razorpayOrder['id'];
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'errors' => ['Razorpay Order Creation Failed: ' . $e->getMessage()]]);
    exit;
}

// Save Order in database as pending with the Razorpay order ID
$insert_query = "
    INSERT INTO orders 
    (order_no, product_id, frame_size_id, quantity, total_amount, customer_name, whatsapp_number, delivery_address, customer_image, notes, razorpay_order_id, payment_status, order_status, created_at)
    VALUES 
    ('$order_no_safe', $product_id_sql, '$frame_size_id', 1, $price, '$customer_name', '$whatsapp_number', '$delivery_address', '$customer_image', '$full_notes_safe', '$razorpay_order_id', 'Pending', 'Pending', NOW())
";

if (mysqli_query($conn, $insert_query)) {
    echo json_encode([
        'status'            => 'success',
        'key_id'            => RAZORPAY_KEY_ID,
        'razorpay_order_id' => $razorpay_order_id,
        'amount'            => $amount_in_paise,
        'currency'          => 'INR',
        'order_no'          => $order_no,
        'customer_name'     => $customer_name,
        'whatsapp_number'   => $whatsapp_number,
        'product_title'     => $product_title
    ]);
} else {
    echo json_encode(['status' => 'error', 'errors' => ['Database Error: ' . mysqli_error($conn)]]);
}
exit;
