<?php
session_start();
require('include/db_connect.php');

// ─── Method guard ────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// ─── Helpers ─────────────────────────────────────────────────────────
function safe($conn, $val) {
    return mysqli_real_escape_string($conn, trim(strip_tags($val)));
}

function back($errors) {
    $_SESSION['order_errors'] = $errors;
    $_SESSION['order_old']    = $_POST;
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

function generate_order_no($conn) {
    do {
        $no    = 'ORD-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        $check = mysqli_query($conn, "SELECT id FROM orders WHERE order_no = '$no'");
    } while (mysqli_num_rows($check) > 0);
    return $no;
}

// ─── Collect & sanitise ───────────────────────────────────────────────
$product_id       = !empty($_POST['product_id']) ? (int)$_POST['product_id'] : NULL;
$order_type       = safe($conn, $_POST['order_type']       ?? '');
$frame_size_id    = (int)($_POST['frame_size_id']          ?? 0);
$customer_name    = safe($conn, $_POST['customer_name']    ?? '');
$whatsapp_number  = safe($conn, $_POST['whatsapp_number']  ?? '');
$delivery_address = safe($conn, $_POST['delivery_address'] ?? '');
$notes            = safe($conn, $_POST['notes']            ?? '');

$errors = [];

// ─── Validate basic fields ────────────────────────────────────────────
$allowed_order_types = ['prebuilt', 'custom_photo', 'custom_logo'];
if (!in_array($order_type, $allowed_order_types))
    $errors[] = 'Invalid order type.';

if ($frame_size_id <= 0)
    $errors[] = 'Please select a frame size.';

if (empty($customer_name))
    $errors[] = 'Your name is required.';

if (empty($whatsapp_number) || !preg_match('/^[6-9][0-9]{9}$/', $whatsapp_number))
    $errors[] = 'Enter a valid 10-digit WhatsApp number (starting with 6–9).';

if (empty($delivery_address))
    $errors[] = 'Delivery address is required.';

// ─── Validate product (only for prebuilt orders) ──────────────────────
if ($order_type === 'prebuilt') {
    if (empty($product_id)) {
        $errors[] = 'Invalid product.';
    } else {
        $prod_check = mysqli_query($conn,
            "SELECT id FROM products WHERE id = '$product_id' AND status = 'active'"
        );
        if (!$prod_check || mysqli_num_rows($prod_check) === 0)
            $errors[] = 'Product not found or unavailable.';
    }
}

// ─── Validate frame size exists ───────────────────────────────────────
$frame_check = mysqli_query($conn,
    "SELECT id FROM frame_sizes WHERE id = '$frame_size_id' AND status = 1"
);
if (!$frame_check || mysqli_num_rows($frame_check) === 0)
    $errors[] = 'Selected frame size not found.';

// ─── Handle image upload ──────────────────────────────────────────────
$customer_image = '';

if (in_array($order_type, ['custom_photo', 'custom_logo'])) {
    $label = ($order_type === 'custom_photo') ? 'photo' : 'logo/design';

    if (empty($_FILES['client_image']['name'])) {
        $errors[] = "Please upload your $label.";
    } elseif ($_FILES['client_image']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'File upload error (code ' . $_FILES['client_image']['error'] . '). Please try again.';
    } else {
        $f_tmp  = $_FILES['client_image']['tmp_name'];
        $f_name = $_FILES['client_image']['name'];
        $f_size = $_FILES['client_image']['size'];

        // Use finfo for real MIME — $_FILES['type'] is user-supplied and spoofable
        $finfo  = finfo_open(FILEINFO_MIME_TYPE);
        $f_mime = finfo_file($finfo, $f_tmp);
        finfo_close($finfo);

        $allowed_mime = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        $max_bytes    = 5 * 1024 * 1024; // 5 MB

        if (!in_array($f_mime, $allowed_mime)) {
            $errors[] = 'Only JPG, PNG, or WebP images are allowed.';
        } elseif ($f_size > $max_bytes) {
            $errors[] = 'Image must be under 5 MB.';
        } else {
            $ext       = strtolower(pathinfo($f_name, PATHINFO_EXTENSION));
            $safe_ext  = in_array($ext, ['jpg','jpeg','png','webp']) ? $ext : 'jpg';
            $new_name  = time() . '_' . mt_rand(1000, 9999) . '.' . $safe_ext;
            $upload_dir = 'uploads/orders/';

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            if (move_uploaded_file($f_tmp, $upload_dir . $new_name)) {
                $customer_image = mysqli_real_escape_string($conn, $new_name);
            } else {
                $errors[] = 'Failed to save uploaded file. Please try again.';
            }
        }
    }
}

// ─── Redirect back if any errors ─────────────────────────────────────
if (!empty($errors)) {
    back($errors);
}

// ─── Build full notes (append order type) ────────────────────────────
$type_label = ucfirst(str_replace('_', ' ', $order_type));
$full_notes = "[Type: $type_label]" . (!empty($notes) ? " $notes" : '');
$full_notes = mysqli_real_escape_string($conn, $full_notes);

// ─── Generate order number ────────────────────────────────────────────
$order_no      = generate_order_no($conn);
$order_no_safe = mysqli_real_escape_string($conn, $order_no);

// ─── Insert order ─────────────────────────────────────────────────────
$pid_val = ($product_id !== NULL) ? "'$product_id'" : 'NULL';

$insert = mysqli_query($conn, "
    INSERT INTO orders
        (order_no, product_id, frame_size_id, customer_name,
         whatsapp_number, delivery_address, customer_image, notes,
         order_status, created_at)
    VALUES
        ('$order_no_safe', $pid_val, '$frame_size_id', '$customer_name',
         '$whatsapp_number', '$delivery_address', '$customer_image', '$full_notes',
         'pending', NOW())
");

if (!$insert) {
    back(['Something went wrong saving your order. Please try again.']);
}

// ─── Success ──────────────────────────────────────────────────────────
header('Location: order_success.php?order=' . urlencode($order_no));
exit;