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
- Manage Offline Videos
- YouTube API
- Dashboard Overview

## 📤 Uploading Files
```
uploads/
uploads/service/
uploads/gallery/
uploads/blogs/
uploads/offline-videos/
```
```powerShell
mkdir uploads/service, uploads/gallery, uploads/blogs, uploads/offline-videos
```

### create all th fontend file
```powerShell
New-Item -Path "include/head.php", "include/header.php", "include/foor.php", "include/footer.php", "include/connection.php" -ItemType File
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

## Admin Table Setup Installation

All in one 📦 Database Schema (MySQL) [Full Schema](#fullschema)
## 📦 Database Schema (MySQL)

Quick navigation — click to jump 👇

- [Admin](#admin)
- [Blogs](#blogs)
- [Services](#services)
- [Appointments](#appointments)
- [Gallery](#gallery)
- [Contacts](#contacts)
- [Testimonials](#testimonials)
- [Videos](#videos)
- [Offline Videos](#offlinevideos)

Frontend Setup 👇

- [Css Reset](#cssreset)
- [YouTube API](#youtubeapi)
- [Google Translater](#gtranslate)
- [Google Sheet Form](#gsheetform)

Run the SQL command below to create the table
<a id="admin"></a>
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
<a id="blogs"></a>
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
<a id="services"></a>
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
<a id="appointments"></a>
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
<a id="gallery"></a>
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
<a id="contacts"></a>
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
<a id="testimonials"></a>
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
<a id="videos"></a>
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

### 1️⃣ Database (important first)
<a id="offlinevideos"></a>
Create a new table (example):

```sql
CREATE TABLE offline_videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    video VARCHAR(255),
    video_alt_tag VARCHAR(255),
    meta_title VARCHAR(255),
    meta_desc TEXT,
    meta_keyword VARCHAR(255),
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
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


## 🔽 Full Schema
<a id="fullschema"></a>

```sql
-- =========================
-- Admin Table
-- =========================
CREATE TABLE `admin` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admin` (`username`, `password`, `status`)
VALUES ('admin', '12345', 1);

-- =========================
-- Blogs Table
-- =========================
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

-- =========================
-- Services Table
-- =========================
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

-- =========================
-- Appointments Table
-- =========================
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

-- =========================
-- Gallery Table
-- =========================
CREATE TABLE `gallery` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `short_description` TEXT,
  `status` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- Contacts Table
-- =========================
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

-- =========================
-- Testimonial Table
-- =========================
CREATE TABLE `testimonial` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `comment` TEXT NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- Videos Table
-- =========================
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

<a id="cssreset"></a>
## Reset CSS description details page
```css
<style>
    .blog-content {
        all: revert;
        }
                            
    .blog-content * {
         all: revert;
        }
                            
</style>
<div class="blog-content">
    <div>
        <?= $row['description'] ?>
    </div>
</div>
```

<a id="youtubeapi"></a>
# YouTube Channel Videos Fetcher (PHP)

```php
<?php
$API_KEY = '';
$channelID = '';
$maxResults = 6;

$apiData = file_get_contents("https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId={$channelID}&maxResults={$maxResults}&key={$API_KEY}");
$videoList = json_decode($apiData);
?>
 <?php 
       if (!empty($videoList->items)) {
        foreach ($videoList->items as $item) {
          if (isset($item->id->videoId)) {
            $videoId = $item->id->videoId;
            $title = $item->snippet->title;
            $thumbnail = $item->snippet->thumbnails->high->url;
            $publishedAt = date("M d, Y", strtotime($item->snippet->publishedAt));
      ?>
        <div class="col-md-4 mb-4">
          <div class="card shadow-sm border-0 h-100 rounded-4 overflow-hidden">
            <div class="ratio ratio-16x9">
              <iframe 
                src="https://www.youtube.com/embed/<?php echo $videoId; ?>" 
                title="<?php echo htmlspecialchars($title); ?>" 
                frameborder="0" allowfullscreen>
              </iframe>
            </div>
            <div class="card-body d-flex flex-column justify-content-between">
              <h5 class="card-title text-dark fw-semibold mb-2"><?php echo $title; ?></h5>
              <p class="mb-2 small text-muted"><?php echo $publishedAt; ?></p>
              <a href="https://www.youtube.com/watch?v=<?php echo $videoId; ?>" target="_blank" class="btn btn-sm btn-outline-primary mt-auto">
                Watch on YouTube <i class="fas fa-arrow-right ms-1"></i>
              </a>
            </div>
          </div>
        </div>
      <?php
          }
        }
      } else {
        echo "<p>No videos found.</p>";
      }
      ?>
```


<a id="gtranslate"></a>
# Add Google Translate
## 🧩 STEP 1 — Add Google Translate Script
### 📄 File: resources/views/layouts/app.blade.php

(or your main layout)

👉 Add before </body>
```js
<div id="google_translate_element" style="display:none;"></div>

<script>
function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'en',
        autoDisplay: false
    }, 'google_translate_element');
}
</script>

<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
```
## 🎨 STEP 2 — Update Language Dropdown (Connect to Google)
### 📄 Navbar Blade
```js
<li class="d-none d-md-inline-block">
    <div class="dropdown-link">
        <a class="dropdown-toggle" href="#" data-bs-toggle="dropdown">
            🌐 Language
        </a>

        <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item" href="#" onclick="changeLanguage('en')">English</a>
                <a class="dropdown-item" href="#" onclick="changeLanguage('de')">German</a>
                <a class="dropdown-item" href="#" onclick="changeLanguage('fr')">French</a>
                <a class="dropdown-item" href="#" onclick="changeLanguage('it')">Italian</a>
                <a class="dropdown-item" href="#" onclick="changeLanguage('ar')">Arabic</a>
            </li>
        </ul>
    </div>
</li>
```

## ⚙️ STEP 3 — JavaScript Language Switcher
### 📄 Same layout file (below scripts)
```js
<script>
function changeLanguage(lang) {
    const select = document.querySelector('.goog-te-combo');
    if (!select) return;

    select.value = lang;
    select.dispatchEvent(new Event('change'));
}
</script>
```
## 🧹 STEP 4 — Hide Google Translate Banner (Optional but Recommended)
### 📄 Add to your main CSS
```css
.goog-te-banner-frame.skiptranslate,
.goog-te-gadget {
    display: none !important;
}

body {
    top: 0 !important;
}
```

<a id="gsheetform"></a>
# 🏥 Appointment Booking Form (Google Sheets Integration)

This project is a simple **Appointment Booking Form** built with HTML, PHP, JavaScript, and Google Apps Script.  
Form submissions are stored directly in a **Google Sheet** using a deployed Google Apps Script Web App.

---

Your site will be live at:

<a href="https://github.com/arjun54244/UserAdmin/blob/main/googleSheet.html" target="_blank">
  View googleSheet.html File
</a>

## 📌 Features

- Collects patient details:
- Submits data to Google Sheets
- Button loading state during submission
- Success & error handling
- No page reload on submit

---

## 🛠️ Technologies Used
- JavaScript (Fetch API)
- Google Apps Script
- Google Sheets

---

## ⚙️ Setup Instructions

### 2️⃣ Google Sheets Setup

1. Create a new **Google Sheet**
2. Rename Sheet to:  
   ```
   Appointments
   ```
3. Create columns in the first row:

| Name | Email | Phone | Service | Hospital | Time | Date | Comment | Timestamp |

---

### 3️⃣ Google Apps Script Setup

1. Open the Google Sheet
2. Go to:
   ```
   Extensions → Apps Script
   ```
3. Replace default code with:

```javascript
function doPost(e) {
  var sheet = SpreadsheetApp.getActiveSpreadsheet().getSheetByName("Appointments");

  var data = [
    e.parameter.name || "",
    e.parameter.email || "",
    e.parameter.phone || "",
    e.parameter.service || "",
    e.parameter.hospital || "",
    e.parameter.time || "",
    e.parameter.date || "",
    e.parameter.comment || "",
    new Date(),
  ];

  sheet.appendRow(data);

  return ContentService.createTextOutput(
    JSON.stringify({ result: "success" })
  ).setMimeType(ContentService.MimeType.JSON);
}
```

4. Click **Deploy → New Deployment**
5. Select **Web App**
6. Set:
   - Execute as: **Me**
   - Who has access: **Anyone**
7. Click **Deploy**
8. Copy the Web App URL

---

### 4️⃣ Update Script URL in Your Form

Replace this line in your HTML:

```javascript
const scriptURL = "YOUR_WEB_APP_URL_HERE";
```

With your deployed Google Apps Script URL.

---

## 📬 How It Works

1. User fills the form.
2. JavaScript prevents default submission.
3. Form data is sent via `fetch()` to Google Apps Script.
4. Apps Script writes data into Google Sheets.
5. Success message is shown.

---

## 📄 License

This project is open-source and free to use.

---

## 👨‍💻 Author

Developed for appointment booking with Google Sheets integration.

---

If you want, I can also provide:
- ✅ Advanced version with validation
- ✅ AJAX + PHP backend version
- ✅ Admin panel to view bookings
- ✅ Email notification system
- ✅ WhatsApp API integration

Just tell me 👍
















