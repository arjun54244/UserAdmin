<?php
include('include/connect.php'); // Make sure $con is defined here
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Collect input
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $treatment = $_POST['treatment'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $message = $_POST['message'];

    // Use prepared statement to avoid SQL errors
    $stmt = $con->prepare("INSERT INTO appointments (name, email, phone, treatment, appointment_date, appointment_time, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $email, $phone, $treatment, $appointment_date, $appointment_time, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Your appointment has been booked successfully!'); window.history.back();</script>";
    } else {
        echo "<script>alert('Something went wrong. Please try again later.'); window.history.back();</script>";
    }

    $stmt->close();
    $con->close();
} else {
    echo "<script>window.history.back();</script>";
    exit;
}
?>
