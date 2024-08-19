<?php

session_start();

unset($_SESSION["user"]);

echo "狀態被登出";
