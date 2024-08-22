<?php
require_once("../db_connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shop_id = $_POST['shop_id'];
    $shop_name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $description = $_POST['description'];
    
    // 先從資料庫中獲取當前的 logo 檔案名稱
    $sql = "SELECT logo_path FROM shop WHERE shop_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $shop_id);
    $stmt->execute();
    $stmt->bind_result($currentLogoName);
    $stmt->fetch();
    $stmt->close();

    // 檢查是否有上傳新檔案
    if (isset($_FILES['shop_logo']) && $_FILES['shop_logo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['shop_logo']['tmp_name'];
        $fileName = $_FILES['shop_logo']['name'];
        $fileSize = $_FILES['shop_logo']['size'];
        $fileType = $_FILES['shop_logo']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        
        // 設定允許的檔案類型
        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // 使用資料庫中的檔案名稱保存新檔案
            $uploadFileDir = '../images/shop_logo/';
            $dest_path = $uploadFileDir . $currentLogoName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // 成功上傳後，更新其他資料（不改變 logo 檔案名稱）
                $sql = "UPDATE shop 
                        SET name=?, phone=?, address=?, description=? 
                        WHERE shop_id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssi", $shop_name, $phone, $address, $description, $shop_id);
                $stmt->execute();

                echo "檔案已成功上傳和資料更新";
            } else {
                echo "上傳檔案時出錯";
            }
        } else {
            echo "上傳的檔案類型不被允許";
        }
    } else {
        // 若未上傳新檔案，僅更新其他資料
        $sql = "UPDATE shop 
                SET name=?, phone=?, address=?, description=? 
                WHERE shop_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $shop_name, $phone, $address, $description, $shop_id);
        $stmt->execute();

        echo "資料已成功更新";
    }

    $conn->close();

    // 重定向
    header("Location: ../page/shop-info.php?shopId=" . $shop_id);
    exit;

} else {
    echo "請使用POST方法提交表單";
}
?>
