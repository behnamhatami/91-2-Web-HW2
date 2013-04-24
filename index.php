<?php
session_start();

include_once('php/util.php');
login();
login_passed();
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>برنامه ی هفتگی سینمای من</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link href="css/login.css" rel="stylesheet">

    <script src="script/jquery-1.9.1.min.js"></script>
    <script src="script/jquery-ui.js"></script>

</head>
<body>
<div class="container">
    <form class="form-signin" method="POST">
        <h3 class="form-signin-heading">لطفا وارد شوید</h3>
        <br>
        <input name="username" type="text" class="input-block-level" placeholder="Email address">
        <button class="btn btn-small btn-block btn-primary btn-login" type="submit">ورود</button>
    </form>

</div>
</body>
</html>