<?php
session_start();

$routes = [
    "" => "tickets/dashboard.php",
    "dashboard" => "tickets/dashboard.php",
    "login" => "auth/login.php",
    "register" => "auth/register.php",
    "success" => "auth/success.php",
    "assignment" => "tickets/assignment.php",
    "create" => "tickets/create.php",
    "edit" => "tickets/edit.php",
    "status" => "tickets/status.php",
    "view" => "tickets/view.php"
];

$route = isset($_GET['route']) ? trim($_GET['route'], '/') : '';

if (array_key_exists($route, $routes)) 
{
    require $routes[$route];
} 
else 
{
    require "views/404.php"; 
}
?>
