<?php
require_once("../db_connect.php");

$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($user_id > 0) {
    $sql = "SELECT user_id, name, account, birthday, email, phone, sign_up_time, portrait_path 
            FROM users 
            WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // 找到資料，返回 JSON 
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // 返回用戶資料的 JSON 格式
        echo json_encode($user);
    } else {
        echo json_encode(["error" => "會員資料未找到"]);
    }
} else {
    echo json_encode(["error" => "無效的會員 ID"]);
}

$stmt->close();
$conn->close();
?>
