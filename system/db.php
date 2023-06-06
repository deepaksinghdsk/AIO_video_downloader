<?php
$database["host"] = "[2409:4072:e92:116c:d0d5:1742:cc71:a3e9]:3306";//"localhost:3306";
$database["name"] = "db";
$database["user"] = "kali";
$database["password"] = "kali";
require_once __DIR__ . "/../admin/classes/database.class.php";
database::connect("mysql:host=" . $database["host"] . ";dbname=" . $database["name"] . ";charset=utf8mb4", $database["user"], $database["password"]);