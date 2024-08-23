<?php
require_once("../db_connect.php");

if (session_status() == PHP_SESSION_NONE) {  //啟動session
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 開始事務
    $conn->begin_transaction();

    try {
        // 用戶資料
        $username = $_POST['username'];
        $account = $_POST['account'];
        $password = $_POST['password'];
        $birthday = $_POST['birthday'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $role = 'shop';
        $sign_up_time = date('Y-m-d H:i:s');
        $activation = 1;
        $portrait_path = '';

        $hashedPassword = md5($password);

        // 插入用戶資料
        $user_sql = "INSERT INTO users (role, name, account, password, birthday, email, phone, sign_up_time, activation, portrait_path) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $user_stmt = $conn->prepare($user_sql);
        $user_stmt->bind_param("ssssssssss", $role, $username, $account, $hashedPassword, $birthday, $email, $phone, $sign_up_time, $activation, $portrait_path);
        $user_stmt->execute();

        // 獲取新插入用戶的 ID
        $user_id = $conn->insert_id;

        // 店鋪資料
        $shopName = $_POST['shopName'];
        $shopPhone = $_POST['shopPhone'];
        $shopAddress = $_POST['shopAddress'];
        $shopDescription = $_POST['shopDescription'];
        // 假設經緯度暫時為空，您可以根據需要添加這些字段到表單中
        $longitude = '';
        $latitude = '';
        $shop_activation = 1; // 假設新店鋪默認激活

        // 添加這行來記錄 $_FILES 的內容
        error_log("FILES array content: " . print_r($_FILES, true));

        // 處理圖片上傳
        $logo_path = '';
        if(isset($_FILES['shopImage']) && $_FILES['shopImage']['error'] == UPLOAD_ERR_OK) {
            $target_dir = "../images/shop_logo/";
            // 確保目錄存在
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES["shopImage"]["name"], PATHINFO_EXTENSION));
            
            error_log("Shop name before processing: " . $shopName);
            
            if (empty($shopName)) {
                $shopName = "default_shop";
            }

            // 清理店名，保留中文字符和基本英文字符
            $sanitized_shop_name = preg_replace("/[^\p{Han}a-zA-Z0-9\s-]/u", "", $shopName);
            $sanitized_shop_name = trim($sanitized_shop_name);
            $sanitized_shop_name = str_replace(' ', '_', $sanitized_shop_name);
            $sanitized_shop_name = substr($sanitized_shop_name, 0, 50);
            
            // 創建新的文件名
            $new_file_name = $sanitized_shop_name . "_logo." . $file_extension;
            $target_file = $target_dir . $new_file_name;
            
            error_log("New file name: " . $new_file_name);
            
            // 檢查文件大小（例如，限制為5MB）
            if ($_FILES["shopImage"]["size"] > 5000000) {
                throw new Exception("對不起，您的圖片太大。");
            }
            
            // 允許的文件格式
            $allowed_extensions = array("jpg", "jpeg", "png", "gif");
            if (!in_array($file_extension, $allowed_extensions)) {
                throw new Exception("對不起，只允許 JPG, JPEG, PNG 和 GIF 文件。");
            }
            
            // 嘗試移動上傳的文件
            if (move_uploaded_file($_FILES["shopImage"]["tmp_name"], $target_file)) {
                $logo_path_address = "images/shop_logo/" . $new_file_name;  // 存儲相對路徑
                $logo_path = $new_file_name;  // 存儲相對路徑
            } else {
                error_log("File upload failed. Error code: " . $_FILES['shopImage']['error']);
                throw new Exception("對不起，上傳文件時出現錯誤。");
            }
        } else if (isset($_FILES['shopImage']) && $_FILES['shopImage']['error'] != UPLOAD_ERR_NO_FILE) {
            // 如果有文件被選擇但上傳失敗
            error_log("File upload error. Error code: " . $_FILES['shopImage']['error']);
            throw new Exception("圖片上傳失敗。錯誤碼：" . $_FILES['shopImage']['error']);
        }

        // 在插入資料庫之前，檢查 $logo_path_address
        error_log("Logo path before database insertion: " . $logo_path_address);

        // 插入店鋪資料
        $shop_sql = "INSERT INTO shop (user_id, name, phone, address, description, sign_up_time, logo_path, longitude, latitude, activation) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $shop_stmt = $conn->prepare($shop_sql);
        $shop_stmt->bind_param("issssssssi", $user_id, $shopName, $shopPhone, $shopAddress, $shopDescription, $sign_up_time, $logo_path, $longitude, $latitude, $shop_activation);
        // 執行語句並檢查結果
        if (!$shop_stmt->execute()) {
            error_log("Shop insertion failed: " . $shop_stmt->error);
            throw new Exception("店鋪資料插入失敗。");
        }

        // 提交事務
        $conn->commit();

        $_SESSION['success_message'] = "註冊成功！";
        header("Location: ../page/login.php");
        exit;
        } catch (Exception $e) {
            // 如果出現錯誤，回滾事務
            $conn->rollback();
            $_SESSION['error_message'] = "錯誤：" . $e->getMessage();
            // 可以記錄錯誤到日誌文件
            error_log("Shop creation error: " . $e->getMessage(), 0);
            header("Location: ../page/login.php"); // 重定向回表單頁面
            exit;
        }

    // 關閉語句和連接
    $user_stmt->close();
    $shop_stmt->close();
    $conn->close();
} else {
    $_SESSION['error_message'] = "Invalid request method.";
}
header("location: ../page/login.php");
exit;
?>