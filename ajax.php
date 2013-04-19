<?php
session_start();
include_once('php/util.php');
login_required();
/**
 * Created by JetBrains PhpStorm.
 * User: Behnam
 * Date: 4/16/13
 * Time: 8:56 PM
 * To change this template use File | Settings | File Templates.
 */
if (isset($_GET['command'])) {
    include_once('php/info_database.php');

    if ($_GET['command'] == "search") {
        echo json_encode(search($_GET));
        return;
    }

    if($_GET['command'] == "get_all") {
        echo json_encode(get_all_scene_attend());
        return;
    }

    if($_GET['command'] == "remove") {
        remove_scene_attend($_GET['id']);
        return;
    }

    if($_GET['command'] == "add") {
        add_scene_attend($_GET['id']);
        return;
    }
}