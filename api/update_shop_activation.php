<?php
require_once("../db_connect.php");

header('Content-Type: application/json');  // 設置響應類型為 JSON

if(isset($_POST['shop_id']) && isset($_POST['activation'])) {
    $shop_id = intval($_POST['shop_id']);
    $activation = intval($_POST['activation']);

    // 根據啟用狀態決定角色
    $role = $activation == 1 ? 'shop' : 'user';

    // 開始事務
    $conn->begin_transaction();

    try {
        // 更新 shop 表的 activation
        $sql_shop = "UPDATE shop SET activation = ? WHERE shop_id = ?";
        $stmt_shop = $conn->prepare($sql_shop);
        $stmt_shop->bind_param("ii", $activation, $shop_id);
        $stmt_shop->execute();
        $stmt_shop->close();

        // 從 shop 表獲取 user_id
        $sql_get_user_id = "SELECT user_id FROM shop WHERE shop_id = ?";
        $stmt_get_user_id = $conn->prepare($sql_get_user_id);
        $stmt_get_user_id->bind_param("i", $shop_id);
        $stmt_get_user_id->execute();
        $result = $stmt_get_user_id->get_result();
        $user_data = $result->fetch_assoc();
        $stmt_get_user_id->close();

        if ($user_data) {
            $user_id = $user_data['user_id'];

            // 更新 users 表的 role
            $sql_user = "UPDATE users SET role = ? WHERE user_id = ?";
            $stmt_user = $conn->prepare($sql_user);
            $stmt_user->bind_param("si", $role, $user_id);
            $stmt_user->execute();
            $stmt_user->close();

            // 提交事務
            $conn->commit();

            echo json_encode(['success' => true]);
        } else {
            throw new Exception('找不到對應的 user_id');
        }
    } catch (Exception $e) {
        // 如果發生錯誤，回滾事務
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => '缺少必要的數據']);
}

$conn->close();
?>