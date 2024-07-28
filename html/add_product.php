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
    $check = getimagesize($_FILES["productImage"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $response['message'] = "File is not an image.";
        $uploadOk = 0;
    }
    if (file_exists($targetFile)) {
        $response['message'] = "Sorry, file already exists.";
        $uploadOk = 0;
    }
    if ($_FILES["productImage"]["size"] > 5000000) {
        $response['message'] = "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    if ($uploadOk == 0) {
        $response['message'] = "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFile)) {
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
