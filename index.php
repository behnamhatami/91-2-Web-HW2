<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>برنامه ی هفتگی سینمای من</title>
    <link href="css/style.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
    <script src="script/script.js"></script>
</head>
<body>
<div id="wrapper">
    <div id="header"></div>
    <div id="content">
        <div class="spacer"></div>
        <?php
        include_once('php/info_database.php');
        include_once('views.php');
        if (isset($_GET['p']) && $_GET['p'] == 'films')
            view_all_films();
        if (isset($_GET['p']) && $_GET['p'] == 'search')
            view_search_pain();
        ?>
        <div class="spacer"></div>
    </div>
    <div id="footer"></div>
</div>
</body>
</html>