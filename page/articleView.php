<?php
require_once("../db_connect.php");

if (isset($_GET['id'])) {
    $article_id = $_GET['id'];

    // 查詢文章
    $sql = "SELECT * FROM articles WHERE article_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $article = $result->fetch_assoc();

        // 回傳 JSON 資料
        echo json_encode($article);
    } else {
        echo json_encode(['error' => '文章未找到']);
    }

    $stmt->close();
}
$conn->close();
?>
