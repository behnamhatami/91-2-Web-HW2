<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>برنامه ی هفتگی سینمای من</title>
    <link href="css/filmdetail.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
    <script src="script/filmdetail.js"></script>
</head>
<body>
<div id="wrapper">
    <?php
        include_once('views.php');
        include_once('php/info_database.php');
        if (isset($_GET['id']))
            view_detailed_film($_GET['id']);
    ?>
</div>
</body>
</html>