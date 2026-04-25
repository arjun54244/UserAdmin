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

    # GitHub direct download link
    $zipUrl = "https://github.com/arjun54244/UserAdmin/raw/main/admin.zip"

    # Save zip inside project folder
    $zipPath = Join-Path $basePath "admin.zip"

    # Extract to admin folder
    $extractPath = Join-Path $basePath "admin"

    Write-Host "📥 Downloading admin.zip..."

    try {
        Invoke-WebRequest -Uri $zipUrl -OutFile $zipPath -UseBasicParsing
        Write-Host "✅ admin.zip downloaded"
    }
    catch {
        Write-Host "❌ Failed to download zip file"
        exit
    }

    Write-Host "📂 Extracting files..."

    try {
        Expand-Archive -Path $zipPath -DestinationPath $extractPath -Force
        Remove-Item $zipPath -Force
        Write-Host "✅ admin folder created successfully"
    }
    catch {
        Write-Host "❌ Failed to extract zip file"
        exit
    }
}
else {
    Write-Host "Skipped admin panel download"
}

# ================================
# Create Include Files
# ================================
$includePath = Join-Path $basePath "include"
New-Item -ItemType Directory -Path $includePath -Force | Out-Null

$files = @("head.php", "header.php", "footer.php", "connection.php")

foreach ($file in $files) {
    New-Item -ItemType File -Path (Join-Path $includePath $file) -Force | Out-Null
}

Write-Host "✅ Include files created"

# ================================
# DB Connection File (PORT FIXED)
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

Write-Host "✅ Database connection file configured"

# ================================
# Create Frontend Files
# ================================
$createFrontend = Read-Host "Do you want to create frontend files? (yes/no)"
if ($createFrontend -eq "yes") {
    $frontendFiles = @("index.php", "about.php", "contact.php", "services.php", "blog.php")

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
    Write-Host "Skipped frontend creation"
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

    # Copy schema.sql
    if (Test-Path $rootSchema) {
        Copy-Item $rootSchema $schemaPath -Force
    }
    else {
        Write-Host "❌ schema.sql not found in root folder"
        exit
    }

    Write-Host "📦 Importing database..."

    # Create DB
    cmd /c "`"$mysqlPath`" -u root --port=$mysqlPort -e `"CREATE DATABASE IF NOT EXISTS $dbName;`""
    if ($LASTEXITCODE -ne 0) {
        Write-Host "❌ Failed to create database (Check MySQL running)"
        exit
    }

    # Import schema
    cmd /c "type `"$schemaPath`" | `"$mysqlPath`" -u root --port=$mysqlPort $dbName"
    if ($LASTEXITCODE -ne 0) {
        Write-Host "❌ Failed to import schema"
        exit
    }

    Write-Host "✅ Database imported successfully"
}

# ================================
# Done
# ================================
Write-Host "Setup completed successfully!"
