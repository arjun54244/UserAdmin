# 🔧 Setup UserAdmin

The UserAdmin module provides a complete administrative interface to manage website content, users, appointments, services, blogs, testimonials, and more.
This section explains how to install, configure, and access the Admin Panel.

## Preview

![preview](https://github.com/arjun54244/UserAdmin/blob/main/img.png)

## ✅ Features

- Secure Admin Login
- Manage Services
- Manage Blogs
- Manage Appointments
- Manage Contacts
- Manage Testimonials
- Manage Videos
- Dashboard Overview

## 🛠️ Configure Database

Import your SQL database and update your connection file:

`include/connect.php`

```php
<?php
$con = mysqli_connect("localhost", "root", "", "your_database_name");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
```

## Admin Table Setupnstallation

Run the SQL command below to create the table

```sql
CREATE TABLE `admin` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## 📥 Insert Default Admin User

```sql
INSERT INTO `admin` (`username`, `password`, `status`)
VALUES
('admin', '12345', 1);
```

## SQL — Create blogs Table

```sql
CREATE TABLE `blogs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `short_description` TEXT NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `image_alt_tag` VARCHAR(255) DEFAULT NULL,
  `description` LONGTEXT NOT NULL,
  `blog_url` VARCHAR(255) NOT NULL,
  `meta_title` VARCHAR(255) DEFAULT NULL,
  `meta_desc` VARCHAR(500) DEFAULT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_blog_url` (`blog_url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## 🗄️ SQL — Create services Table

```sql
CREATE TABLE `services` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `short_description` TEXT NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `icon` VARCHAR(255) DEFAULT NULL,
  `image_alt_tag` VARCHAR(255) DEFAULT NULL,
  `description` LONGTEXT NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `meta_title` VARCHAR(255) DEFAULT NULL,
  `meta_keyword` VARCHAR(500) DEFAULT NULL,
  `meta_desc` VARCHAR(500) DEFAULT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## 🗄️ SQL — Create appointments Table

```sql
CREATE TABLE `appointments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `treatment` VARCHAR(255) NOT NULL,
  `appointment_date` DATE NOT NULL,
  `appointment_time` TIME NOT NULL,
  `message` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```

## 🗄️ SQL — Create contacts Table

```sql
CREATE TABLE `contacts` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `services` VARCHAR(255) DEFAULT NULL,
  `message` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```

## SQL — Create testimonial Table

```sql
CREATE TABLE `testimonial` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `comment` TEXT NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## SQL — Create videos Table

```sql
CREATE TABLE `videos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `video_url` VARCHAR(500) NOT NULL,
  `short_description` TEXT DEFAULT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### submit_appointment.php — Appointment Form Handler

```bash
submit_appointment.php
```

```php
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
```
### submit_appointment.php form look like 

```html
  <form action="submit_appointment.php" method="POST">
```

## contact.php — Contact Form Handler

```bash
contact.php
```
```html
 <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
```

### Add This code on same contact page.
```php
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $services = mysqli_real_escape_string($con, $_POST['services']);
    $message = mysqli_real_escape_string($con, $_POST['message']);

    // Insert data into database
    $sql = "INSERT INTO contacts (name, email, phone, services, message)
    VALUES ('$name', '$email', '$phone', '$services', '$message')";

    if (mysqli_query($con, $sql)) {
        echo "<script>alert('Your message has been sent successfully!'); window.history.back();</script>";
    } else {
        echo "<script>alert('Something went wrong. Please try again later.'); window.history.back();</script>";
    }

        mysqli_close($con);
    }
?>
```



