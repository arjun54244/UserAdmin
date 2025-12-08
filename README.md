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
- Manage Gallery
- Manage Videos
- Dashboard Overview

## 📤 Uploading Files
```
uploads/
uploads/service/
uploads/gallery/
uploads/blogs/
```

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
  `meta_keyword` VARCHAR(500) DEFAULT NULL,
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
  `meta_desc` VARCHAR(500) DEFAULT NULL,
  `meta_keyword` VARCHAR(500) DEFAULT NULL,
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

## 🗄️ SQL — Create gallery Table

```sql
CREATE TABLE gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL,
    short_description TEXT,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
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

## 📝 Blog & Service SEO System – PHP Guide
This project uses a simple and effective method to dynamically generate SEO meta tags for blog pages using PHP.
The goal is to ensure every blog & service post has its own title, description, and keywords automatically loaded from the database.

###📁 File Structure
```
/project
│
├── include/
│   ├── connect.php
│   └── head.php
│
├── blog-details.php
└── blogs.php
├── service-details.php
└── services.php
```

### 🔧 include/head.php
This file is responsible for inserting SEO-friendly meta tags inside the <head> section of every page.
```php
<?php
include('include/connect.php');

// Default fallback values
(!isset($meta_title)) ?? $meta_title = "Expert Neurology Care in Greater Noida | Dr. Chirag Gupta";
(!isset($meta_desc)) ?? $meta_desc = "Providing comprehensive neurological care and treatment in Greater Noida. Consult Dr. Chirag Gupta for expert diagnosis and personalized care." ;
(!isset($meta_keyword)) ?? $meta_keyword = "Neurology, Neurologist in Greater Noida, Brain Health, Stroke Treatment, Epilepsy Care, Headache Specialist"; 
?>

<head>
    <title><?= htmlspecialchars($meta_title); ?></title>
    <meta name="title" content="<?= htmlspecialchars($meta_title); ?>">
    <meta name="description" content="<?= htmlspecialchars($meta_desc); ?>">
    <meta name="keywords" content="<?= htmlspecialchars($meta_keyword); ?>">
</head>
```
Here is the correct and clean PHP 7–compatible version of your include/head.php.
Your earlier code used the null-coalescing operator incorrectly. In PHP 7, the operator ?? is valid, but not in the pattern you used.
### ✅ Correct & Clean PHP 7 Version
```php
<?php
include('include/connect.php');

// Default fallback values (PHP 7 compatible)
if (!isset($meta_title)) {
    $meta_title = "Expert Neurology Care in Greater Noida | Dr. Chirag Gupta";
}

if (!isset($meta_desc)) {
    $meta_desc = "Providing comprehensive neurological care and treatment in Greater Noida. Consult Dr. Chirag Gupta for expert diagnosis and personalized care.";
}

if (!isset($meta_keyword)) {
    $meta_keyword = "Neurology, Neurologist in Greater Noida, Brain Health, Stroke Treatment, Epilepsy Care, Headache Specialist";
}
?>

<head>
    <title><?= htmlspecialchars($meta_title); ?></title>
    <meta name="title" content="<?= htmlspecialchars($meta_title); ?>">
    <meta name="description" content="<?= htmlspecialchars($meta_desc); ?>">
    <meta name="keywords" content="<?= htmlspecialchars($meta_keyword); ?>">
</head>
```

## 📄 blog-details.php

This file loads a single blog post based on its SEO-friendly URL and sets the meta tags for that page.

### 📌 What it does

- Reads the `url` parameter from the browser.
- Fetches the matching blog post from the database.
- If no blog is found, it displays **"Blog Not Found"**.
- Sets dynamic SEO variables:
  - `$meta_title`
  - `$meta_desc`
- Passes these variables to `include/head.php` to generate the correct SEO meta tags for the blog page.
### 📌 Code Overview
```php
<?php
    include('include/connect.php');
    
    if (!isset($_GET['url']) || $_GET['url'] == '') {
        header("location: blogs.php");
        exit;
    }
    
    $blog_url = mysqli_real_escape_string($con, $_GET['url']);
    
    $blog_sql = mysqli_query(
        $con,
        "SELECT * FROM blogs WHERE blog_url='$blog_url' AND status=1 LIMIT 1"
    );
    
    if (mysqli_num_rows($blog_sql) == 0) {
        echo "<h2>Blog Not Found</h2>";
        exit;
    }
    
    $blog = mysqli_fetch_assoc($blog_sql);
    
    // SEO META
    $meta_title = $blog['meta_title'];
    $meta_desc = $blog['meta_desc'];
    $meta_keyword = $blog['meta_keyword'];
?>
```
---
## 📄 service-details.php

This file loads a single service page based on its SEO-friendly URL and applies the correct meta tags dynamically.

### 📌 What it does

- Reads the `url` parameter from the browser.
- Fetches the matching service record from the `services` table in the database.
- If no service is found, it displays **"Service Not Found"**.
- Sets dynamic SEO variables:
  - `$meta_title`
  - `$meta_desc`
  - `$meta_keyword`
- Passes these variables to `include/head.php` to generate the correct SEO meta tags for the service page.

```php
    <?php 
    include('include/connect.php');
    
    if (!isset($_GET['url']) || $_GET['url'] == '') {
        header("location: services.php");
        exit;
    }
    
    $service_url = mysqli_real_escape_string($con, $_GET['url']);
    
    $service_sql = mysqli_query(
        $con,
        "SELECT * FROM services WHERE url='$service_url' AND status=1 LIMIT 1"
    );
    
    if (mysqli_num_rows($service_sql) == 0) {
        echo "<h2>Service Not Found</h2>";
        exit;
    }
    
    $service = mysqli_fetch_assoc($service_sql);
    
    // SEO META
    $meta_title = $service['meta_title'];
    $meta_desc = $service['meta_desc'];
    $meta_keyword = $service['meta_keyword'];
?>
```
---

## 🔗 Linking Blog & Service Pages Using SEO-Friendly URLs

To ensure every **Blog** and **Service** opens the correct detail page, your listing pages (`blogs.php` and `services.php`) must pass an **SEO-friendly URL slug** using the `url` parameter.

These slugs allow the detail pages to load the correct database record and generate dynamic SEO meta tags.

---

## 📘 blogs.php → Linking to Blog Details

Every blog card should link to the blog details page using:

`blog-details.php?url=your-blog-slug`

### ✅ Example (Correct Usage)

```php
<?php
$blogs = mysqli_query($con, "SELECT * FROM blogs WHERE status = 1 ORDER BY id DESC");
while ($row = mysqli_fetch_assoc($blogs)) { ?>

    <div class="single-blog-post">
        <div class="post-featured-thumb bg-cover wow fadeInUp" data-wow-delay=".3s"
            style="background-image: url('uploads/blogs/<?php echo $row['image']; ?>');">
        </div>

        <div class="post-content">

            <div class="post-meta wow fadeInUp" data-wow-delay=".2s">
                <span><i class="far fa-user"></i> Dr. Chirag Gupta</span>
            </div>

            <h3 class="wow fadeInUp" data-wow-delay=".4s">
                <a href="blog-details.php?url=<?php echo $row['blog_url']; ?>">
                    <?= $row['title']; ?>
                </a>
            </h3>

            <p class="wow fadeInUp" data-wow-delay=".6s">
                <?= $row['short_description']; ?>
            </p>

            <a href="blog-details.php?url=<?= $row['blog_url']; ?>" class="theme-btn mt-4">
                <i class="far fa-chevron-right"></i>
                Read More
            </a>

        </div>
    </div>

<?php } ?>
```

### 📌 What This Does

- Sends the blog’s SEO slug (blog_url) to blog-details.php

- Allows the details page to load the correct blog using:

`$_GET['url'];`

### 🛠 services.php → Linking to Service Details

Each service card should link to:

`service-details.php?url=your-service-slug`

### ✅ Example (Correct Usage)
```php
<?php
$qry = "SELECT * FROM services WHERE status = 1";
$res = mysqli_query($con, $qry);
while ($row = mysqli_fetch_assoc($res)) {
?>
  <div class="col-lg-4 col-md-6 col-auto wow fadeInUp d-flex" data-wow-delay=".3s">
    <div class="service-card-items-3 d-flex align-items-center flex-column">
      <div class="service-content">

        <div class="service-icon">
          <div class="icon">
            <a href="service-details.php?url=<?= $row['url']; ?>">
              <div class="service">
                <img src="uploads/service/<?= $row['image']; ?>" class="w-100 rounded-2" alt="">
              </div>
            </a>
          </div>
        </div>

        <h5>
          <a href="service-details.php?url=<?= $row['url']; ?>">
            <?= $row['title']; ?>
          </a>
        </h5>

        <p><b><?= substr($row['short_description'], 0, 80) . '....'; ?></b></p>

        <a href="service-details.php?url=<?= $row['url']; ?>" class="theme-btn style-2">
          <i class="far fa-chevron-right"></i>
          View Details
        </a>

      </div>
    </div>
  </div>
<?php } ?>
```


    












