<?php
include 'db_connection.php';

$id = $_GET['id'];

$sql = "SELECT * FROM shop WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$product = $result->fetch_assoc();

echo json_encode($product);
?>
