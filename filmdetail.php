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

    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/filmdetail.css" rel="stylesheet">

    <script src="script/jquery-1.9.1.min.js"></script>
    <script src="script/jquery-ui.js"></script>
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