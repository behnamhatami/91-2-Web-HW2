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
    <link href="css/base.css" rel="stylesheet">

    <script src="script/jquery-1.9.1.min.js"></script>
    <script src="script/jquery-ui.js"></script>

    <script src="script/base.js"></script>
</head>
<body>
<div id="wrapper">
    <?php
    include_once('view/base.php');
    get_header_as_html();
    ?>
    <div id="content">
        <div class="spacer"></div>
        <div class="spacer"></div>
    </div>
    <div id="footer"></div>
</div>
</body>
</html>