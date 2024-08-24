<?php
require_once("../db_connect.php");

header('Content-Type: application/json');  // 設置響應類型為 JSON

if(isset($_POST['shop_id']) && isset($_POST['activation'])) {
    $shop_id = intval($_POST['shop_id']);
    $activation = intval($_POST['activation']);

    $sql = "UPDATE shop SET activation = ? WHERE shop_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $activation, $shop_id);

    if($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
}

$conn->close();
?>