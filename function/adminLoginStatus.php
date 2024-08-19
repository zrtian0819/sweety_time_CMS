<?php

session_start();

$_SESSION["user"] = [
    "user_id" => "1",
    "name" => "Frontend Hero",
    "account" => "admin",
    "password" => "827ccb0eea8a706c4c34a16891f84e7b",
    "role" => "admin"
];

echo "admin登入成功";
