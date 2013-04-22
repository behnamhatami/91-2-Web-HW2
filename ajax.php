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
set_time_limit(30);
if (isset($_GET['command'])) {
    include_once('php/info_database.php');
    $success = array('result' => 'success');
    if ($_GET['command'] == "search") {
        echo json_encode(search($_GET));
        return;
    }

    if ($_GET['command'] == "get_all_scene_attend") {
        echo json_encode(get_all_scene_attend());
        return;
    }

    if ($_GET['command'] == "remove") {
        remove_scene_attend($_GET['id']);
        echo json_encode($success);
        return;
    }

    if ($_GET['command'] == "add") {
        add_scene_attend($_GET['id']);
        echo json_encode($success);
        return;
    }

    if ($_GET['command'] == "update_live_films") {
        echo json_encode(update_all_live_film_info());
        return;
    }

    if ($_GET['command'] == "get_all_films") {
        $temp = get_all_films();
        foreach ($temp as &$inp) {
            unset($inp['producers']);
            unset($inp['actors']);
        }
        echo json_encode(get_all_films());
        return;
    }

    if ($_GET['command'] == "update_film_scenes") {
        update_special_live_film_info($_GET['id']);
        echo json_encode(get_cinemas_that_show_film($_GET['id']));
        return;
    }

    if ($_GET['command'] == "get_cinemas_that_show_film"){
        echo json_encode(get_cinemas_that_show_film($_GET['id']));
        return;
    }
}