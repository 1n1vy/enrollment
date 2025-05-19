<?php
// auth.php
session_start();

function isLoggedIn()
{
    return isset($_SESSION['username']);
}

function redirectIfNotLoggedIn()
{
    if (!isLoggedIn()) {
        header("Location: http://localhost/capst/index.php"); // Redirect to index.php for login
        exit();
    }
}

function redirectIfLoggedIn()
{
    if (isLoggedIn()) {
        header("Location: http://localhost/capst/welcome.php"); // Redirect to dashboard
        exit();
    }
}

function checkRole($allowedRoles)
{
    if (!isset($_SESSION['role_as']) || !in_array($_SESSION['role_as'], $allowedRoles)) {
        header("Location: http://localhost/capst/unauthorized.php"); // Redirect to unauthorized page
        exit();
    }
}
