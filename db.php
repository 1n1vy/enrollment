<?php
// db.php
if (file_exists('includes/db_connection.php')) {
    require_once 'includes/db_connection.php';
} else {
    die('Database connection file not found!');
}
