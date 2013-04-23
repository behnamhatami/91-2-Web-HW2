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
    <link href="css/film.css" rel="stylesheet">

    <script src="script/jquery-1.9.1.min.js"></script>
    <script src="script/jquery-ui.js"></script>

    <script src="script/util.js"></script>
    <script src="script/film.js"></script>
</head>

<body>
<div class="overlay" style="display: none;">
    <div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-draggable ui-resizable"
         style="outline: 0; height: auto; width: 1163px; top: 46px; margin: auto; line-height: 1.25em; opacity: 1;">
        <div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
            <span class="ui-dialog-title">[name|="value"]</span>

            <div class="ui-dialog-titlebar-close ui-corner-all" style="cursor: pointer;">
                <span class="ui-icon ui-icon-closethick">close</span>
            </div>
        </div>
        <div id="modal" class="ui-dialog-content ui-widget-content"
             style="padding: 5px 0 0; width: auto; min-height: 0; height: 539.4px;" scrolltop="0" scrollleft="0">
        </div>
    </div>
</div>
<div id="wrapper">
    <?php
    include_once('view/base.php');
    get_header_as_html();
    ?>
    <div id="content">
        <div class="spacer"></div>
        <?php
        include_once('php/info_database.php');
        include_once('view/film.php');
        view_all_films();
        ?>
        <div class="spacer"></div>
    </div>
    <div id="footer"></div>
</div>
</body>
</html>