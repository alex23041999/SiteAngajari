<?php
session_start();
ini_set('display_errors', 1);
require_once('DbConnection.php');
require_once('Jobs.php');
require_once('Test.php');
ini_set('log_errors','On');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

?>

<html>

<head>
    <title>Pagină principală</title>
    <link rel="stylesheet" type="text/css" href="css/quiz_style.css">
    <link rel="stylesheet" href="./font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" type="image/png" href="css/images/FavIcon.png">
</head>

<body>
    <div class="page-container m-0">
        <div class="sidebar m-0">
            <div class="logo-firm"></div>
            <div class="sidebar-buttons">
                <button class="sidebar-button" onclick="window.location='MainPageAdmin.php';"> <i class="fa fa-home" aria-hidden="true"></i>Pagină principală</button>
                <button class="sidebar-button" onclick="window.location='NewJobByAdmin.php';"> <i class="fa fa-plus" aria-hidden="true"></i>Adăugare job</button>
                <button class="sidebar-button" onclick="window.location='AdminJobModifierPage.php';"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i>Modifică job</button>
                <button class="sidebar-button" onclick="window.location='UpdateApplicationsPage.php';"> <i class="fa fa-retweet" aria-hidden="true"></i>Update aplicări</button>
                <form method="POST" name="logout" action="LoginPage.php">
                <button class="logout-button" type="submit" name="logoutButton">Logout <i class="fa fa-sign-out" aria-hidden="true" style="margin-left: 10px;"></i></button>
                </form>
            </div>
        </div>
        <div class="mainpageAdmin_div">

        </div>
    </div>
</body>

</html>