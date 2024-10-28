<?php
// upload_file.php
session_start();
include 'config.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patientID = $_POST['patient_id'];
    $targetDir = "uploads/"; // Directory where files will be uploaded
    $targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if file is an actual file or a fake file
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (example: limit to 5MB)
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats (you can adjust this as needed)
    if ($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "pdf") {
        echo "Sorry, only JPG, JPEG, PNG & PDF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Try to upload file
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
            // Insert into database
            $insert_query = "INSERT INTO tblfiles (PatientID, FilePath, UploadDate) VALUES ('$patientID', '$targetFile', NOW())";
            if (mysqli_query($connect, $insert_query)) {
                $_SESSION['message'] = "File uploaded successfully!";
            } else {
                $_SESSION['message'] = "Database error: " . mysqli_error($connect);
            }
        } else {
            $_SESSION['message'] = "File upload failed. Please try again.";
        }
    }

    // Redirect back to the upload page
    header("Location: uploads.php");
    exit();
}
?>
