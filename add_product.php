<?php
include 'db_connection.php';

// 1. Get values from the form
$product_id   = $_POST['product_id'];
$product_name = $_POST['product_name'];
$price        = $_POST['price'];
$stocks       = $_POST['stocks'];

// 2. Where we will save the image
$uploadFolder = "uploads/";

// 3. Create the folder if it does not exist
if (!is_dir($uploadFolder)) {
    mkdir($uploadFolder, 0777, true);
}

// 4. Get the image file name
$imageName = basename($_FILES["product_image"]["name"]);

// 5. Full path where the image will be saved
$targetPath = $uploadFolder . $imageName;

// 6. Get file extension (jpg, png, etc.)
$fileExtension = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));

// 7. Allowed image types
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

// 8. Check if file type is allowed
if (!in_array($fileExtension, $allowedExtensions)) {
    die("Only JPG, JPEG, PNG & GIF files are allowed.");
}

// 9. Upload the file to the folder
if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetPath)) {

    // 10. Save product details to database (including image path)
    $sql = "INSERT INTO shop (product_id, product_name, product_image, price, stocks)
            VALUES ('$product_id', '$product_name', '$targetPath', '$price', '$stocks')";

    if ($conn->query($sql) === TRUE) {
        echo "Product added successfully";
    } else {
        echo "Error: " . $conn->error;
    }

} else {
    echo "Error uploading image.";
}
?>
