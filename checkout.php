<?php
include 'db_connection.php';

$product_id = $_POST['product_id'];
$quantity   = $_POST['quantity'];

// get product stock
$sql = "SELECT * FROM shop WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if ($quantity > $product['stocks']) {
    echo json_encode([
        'success' => false,
        'message' => 'Insufficient stock available.'
    ]);
    exit;
}

// deduct stock
$new_stock = $product['stocks'] - $quantity;

$update = "UPDATE shop SET stocks = ? WHERE product_id = ?";
$stmt2 = $conn->prepare($update);
$stmt2->bind_param("ii", $new_stock, $product_id);
$stmt2->execute();

echo json_encode([
    'success' => true,
    'message' => 'Checkout successful!',
    'new_stock' => $new_stock
]);
?>
