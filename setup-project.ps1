# ================================
# PHP Project Auto Generator
# ================================

param(
    [string]$projectName
)

# Ask if not passed
if (-not $projectName) {
    $projectName = Read-Host "Enter your project name"
}

# Base path
$basePath = Join-Path (Get-Location) $projectName

Write-Host "🚀 Creating project at $basePath..."

# ================================
# Create Project Folder
# ================================
New-Item -ItemType Directory -Path $basePath -Force | Out-Null

# ================================
# Create Upload Directories
# ================================
$uploadPaths = @(
    "uploads",
    "uploads/service",
    "uploads/gallery",
    "uploads/blogs",
    "uploads/offline-videos"
)

foreach ($path in $uploadPaths) {
    New-Item -ItemType Directory -Path (Join-Path $basePath $path) -Force | Out-Null
}

Write-Host "✅ Upload folders created"

# ================================
# Download & Extract admin.zip
# ================================
$downloadAdmin = Read-Host "Do you want to download admin.zip? (yes/no)"

if ($downloadAdmin -eq "yes") {

    $zipUrl = "https://github.com/arjun54244/UserAdmin/raw/main/admin.zip"
    $zipPath = Join-Path $basePath "admin.zip"
    $extractPath = Join-Path $basePath "admin"

    Write-Host "Downloading admin.zip..."

    try {
        Invoke-WebRequest -Uri $zipUrl -OutFile $zipPath
        Write-Host "Downloaded successfully"
    }
    catch {
        Write-Host "Download failed"
        exit
    }

    Write-Host "Extracting files..."

    try {
        Expand-Archive -Path $zipPath -DestinationPath $extractPath -Force
        Remove-Item $zipPath -Force
        Write-Host "Admin panel ready"
    }
    catch {
        Write-Host "Extraction failed"
        exit
    }
}
else {
    Write-Host "Skipped admin download"
}

# ================================
# Create Include Files
# ================================
$includePath = Join-Path $basePath "include"
New-Item -ItemType Directory -Path $includePath -Force | Out-Null

$files = @("head.php","header.php","footer.php","connection.php")

foreach ($file in $files) {
    New-Item -ItemType File -Path (Join-Path $includePath $file) -Force | Out-Null
}

Write-Host "✅ Include files created"

# ================================
# DB Connection File
# ================================
$mysqlPort = 3307

$dbContent = @"
<?php
`$con = mysqli_connect("localhost", "root", "", "$projectName", $mysqlPort);

if (!`$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
"@

Set-Content -Path (Join-Path $includePath "connection.php") -Value $dbContent

Write-Host "✅ DB connection file created"

# ================================
# Frontend Files
# ================================
$createFrontend = Read-Host "Do you want frontend files? (yes/no)"

if ($createFrontend -eq "yes") {

    $frontendFiles = @("index.php","about.php","contact.php","services.php","blog.php")

    foreach ($file in $frontendFiles) {
        $filePath = Join-Path $basePath $file
        
        $content = @"
<?php include('include/head.php'); ?>
<?php include('include/header.php'); ?>

<h1>$file Page</h1>

<?php include('include/footer.php'); ?>
"@

        Set-Content -Path $filePath -Value $content
    }

    Write-Host "✅ Frontend files created"
}
else {
    Write-Host "⏭️ Skipped frontend"
}

# ================================
# Import Database
# ================================
$importDb = Read-Host "Do you want to import schema.sql? (yes/no)"

if ($importDb -eq "yes") {

    $mysqlPath = "C:\xampp\mysql\bin\mysql.exe"
    $dbName = $projectName

    $rootSchema = Join-Path (Get-Location) "schema.sql"
    $schemaPath = Join-Path $basePath "schema.sql"

    if (!(Test-Path $rootSchema)) {
        Write-Host "schema.sql not found"
        exit
    }

    Copy-Item $rootSchema $schemaPath -Force

    Write-Host "Importing database..."

    cmd /c "`"$mysqlPath`" -u root --port=$mysqlPort -e `"CREATE DATABASE IF NOT EXISTS $dbName;`""

    Get-Content $schemaPath | & $mysqlPath -u root --port=$mysqlPort $dbName

    Write-Host "Database imported successfully"
}  # ✅ THIS CLOSING BRACE IS REQUIRED

# ================================
# DONE
# ================================
Write-Host "Setup completed successfully!"
