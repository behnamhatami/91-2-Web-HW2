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
    <style type="text/css">
        body {
            padding-top: 100px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            max-width: 300px;
            padding: 19px 29px 29px;
            margin: 0 auto 20px;
            background-color: #fff;
            border: 1px solid #e5e5e5;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
        }

        .form-signin .form-signin-heading,
        .form-signin .checkbox {
            margin-bottom: 10px;
        }

        .form-signin input[type="text"],
        .form-signin input[type="password"] {
            font-size: 16px;
            height: auto;
            margin-bottom: 15px;
            padding: 7px 9px;
        }

    </style>
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