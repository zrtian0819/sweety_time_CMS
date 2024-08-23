<?php
require_once("../db_connect.php");

// 獲取請求中的教師 ID
$teacher_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($teacher_id > 0) {
    // 從資料庫中查找教師的詳細資料
    $sql = "SELECT teacher_id, name, expertise, experience, education, licence, awards, description, img_path, valid 
            FROM teacher 
            WHERE teacher_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // 如果找到資料，返回 JSON 格式
    if ($result->num_rows > 0) {
        $teacher = $result->fetch_assoc();

        // 返回教師詳細資料的 JSON 格式
        echo json_encode([
            "teacher_id" => $teacher["teacher_id"],
            "name" => $teacher["name"],
            "expertise" => $teacher["expertise"],
            "experience" => $teacher["experience"],
            "education" => $teacher["education"],
            "licence" => $teacher["licence"],
            "awards" => $teacher["awards"],
            "description" => $teacher["description"],
            "img_path" => $teacher["img_path"],
            "valid" => $teacher["valid"]
        ]);
    } else {
        // 如果未找到資料，返回錯誤信息
        echo json_encode(["error" => "教師資料未找到"]);
    }
} else {
    // 如果教師 ID 無效，返回錯誤信息
    echo json_encode(["error" => "無效的教師 ID"]);
}

$stmt->close();
$conn->close();
?>