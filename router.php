<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

if ($uri == '/') {
    $uri = "login";
}
$uri = str_replace('/', "", $uri);

$routes = [
    "dashboard" => "tickets/dashboard.php",
    "login" => "auth/login.html",
    "registration" => "auth/registration.html",
    "success" => "auth/success.php",
    "assignment" => "tickets/assignment.php",
    "create" => "tickets/create.php",
    "edit" => "tickets/edit.php",
    "status" => "tickets/status.php",
    "view" => "tickets/view.php",
    "login_handler" => "auth/login_handler.php",
    "assignment_handler" => "tickets/assignment_handler.php",
    "logout" => "tickets/logout.php",
];

function routeToController($uri, $routes) {
    $protectedRoutes = ["dashboard", "assignment", "create", "edit", "status", "view"];

    if (in_array($uri, $protectedRoutes) && !isset($_SESSION['user_id'])) {
        require "auth/login.html";
        exit(); 
    }

    if (array_key_exists($uri, $routes)) {
        require $routes[$uri];
    } else {
        abort();
    }
}

function abort($code = 404) {
    require "views/{$code}.php";
    die();
}

routeToController($uri, $routes);
?>
