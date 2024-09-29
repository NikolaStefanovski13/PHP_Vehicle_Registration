<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'config.php';
require_once 'functions.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Licensing Management</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <?php if (isLoggedIn()): ?>
                <li><a href="index.php?page=admin">Admin Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <main>
        <?php
        switch ($page) {
            case 'home':
                include 'home.php';
                break;
            case 'admin':
                if (isLoggedIn()) {
                    include 'admin.php';
                } else {
                    header('Location: login.php');
                    exit;
                }
                break;
            default:
                include '404.php';
                break;
        }
        ?>
    </main>

    <script src="script.js"></script>
</body>

</html>