<?php

session_start();

$_SESSION["user"] = [
    "user_id" => "20",
    "name" => "果昂甜品",
    "account" => "fruitaunt",
    "password" => "827ccb0eea8a706c4c34a16891f84e7b",
    "role" => "shop"
];

echo "shop #20 登入成功";
