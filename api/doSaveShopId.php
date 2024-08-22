<?php
session_start();

if (isset($_POST['shop_id'])) {
    $_SESSION['shop']['shop_id'] = $_POST['shop_id'];
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
?>