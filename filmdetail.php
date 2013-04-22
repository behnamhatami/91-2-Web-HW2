<?php
session_start();
include_once('php/util.php');
login_required();
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>برنامه ی هفتگی سینمای من</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/filmdetail.css">

    <script src="script/jquery-1.9.1.min.js"></script>
    <script src="script/jquery-ui.js"></script>
    <script src="script/util.js"></script>
    <script src="script/filmdetail.js"></script>
</head>
<body>
<div id="wrapper">
    <?php
    include_once('view/film_detail.php');
    include_once('php/info_database.php');
    if (isset($_GET['id']))
        view_film_detail($_GET['id']);
    ?>
</div>
</body>
</html>