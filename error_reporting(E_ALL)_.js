// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// session_start();
// require 'includes/auth.php';
redirectIfNotLoggedIn();

// Optionally, restrict access by role
checkRole(['Admin', 'Faculty', 'Registrar', 'Building Manager']);