<?php

session_start();

$_SESSION["user"] = [
    "user_id" => "66",
    "name" => "時飴Approprie",
    "account" => "hughdessert",
    "password" => "827ccb0eea8a706c4c34a16891f84e7b",
    "role" => "shop"
];

$_SESSION["shop"] = [
    "shop_id" => "67",
];

echo "user #66 [shop] 登入成功";
