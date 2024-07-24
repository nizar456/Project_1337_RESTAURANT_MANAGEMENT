<?php
require_once 'db_connection.php';

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productName = $_POST['productName'];
    $categoryId = $_POST['category'];
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["productImage"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["productImage"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $response['message'] = "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        $response['message'] = "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["productImage"]["size"] > 5000000) { // Limit to 5000KB
        $response['message'] = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
   /* if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $response['message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }*/

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $response['message'] = "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFile)) {
            // Insert product information into the database
            $url = $targetFile;
            $stmt = $conn->prepare("INSERT INTO products (nom, url, category_id) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $productName, $url, $categoryId);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = "The product has been added successfully.";
            } else {
                $response['message'] = "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $response['message'] = "Sorry, there was an error uploading your file.";
        }
    }

    $conn->close();
}

echo json_encode($response);
?>
