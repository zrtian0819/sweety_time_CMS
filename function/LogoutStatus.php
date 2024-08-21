<?php

session_start();

unset($_SESSION);
session_destroy();

echo "狀態被登出";
