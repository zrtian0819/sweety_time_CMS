<?php
require_once("../db_connect.php");

session_start();

// 判定使用者是否傳入account
if(!isset($_POST["account"])){
    $data=[
        "status"=>0,
        "message"=>"沒有帶入正確的帳號密碼"
    ];
    echo json_encode($data);
    exit;
}

$account = $_POST["account"];
$password = $_POST["password"];

// 帳號為空的處理方式
if(empty($account)){
    $data=[
        "status"=>0,
        "message"=>"請輸入帳號"
    ];
    echo json_encode($data);

    exit;
}

// 密碼為空的處理方式
if(empty($password)){
    $data=[
        "status"=>0,
        "message"=>"請輸入密碼"
    ];
    echo json_encode($data);

    exit;
}

//密碼加密
$password = md5($password);

$sql = "SELECT * FROM users WHERE account='$account' AND password='$password'";

$result = $conn->query($sql);
$userCount = $result->num_rows;

//判定有沒有此使用者的方式
if($userCount>0){
    
    unset($_SESSION["error"]);
    $user = $result->fetch_assoc();

    // 僅將部分資訊存入session的方法
    $_SESSION["user"] =[
        "account"=> $user["account"],
        "name"=> $user["name"],
        "email"=> $user["email"],
        "phone"=> $user["phone"],
    ];
    
    $data=[
        "status"=>1,
        "message"=>"登入成功",
    ];
    echo json_encode($data);

}else{
    // $_SESSION["error"]["message"]="帳號或密碼錯誤";

    if(!isset($_SESSION["error"]["times"])){
        $_SESSION["error"]["times"] = 1 ;
    }else{
        $_SESSION["error"]["times"] ++ ;
    }
    $errorTimes = $_SESSION["error"]["times"];
    $acceptErrorTimes = 6;
    $remainErrorTimes = $acceptErrorTimes - $errorTimes;

    // $_SESSION["error"]["message"] .= "還有 $remainErrorTimes 次機會" ;

    $data=[
        "status"=>2,
        "message"=>"還有 $remainErrorTimes 次機會",
        "remains"=>$remainErrorTimes
    ];
    echo json_encode($data);
    
}


$conn->close();
?>