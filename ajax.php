<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Behnam
 * Date: 4/16/13
 * Time: 8:56 PM
 * To change this template use File | Settings | File Templates.
 */
if (isset($_GET['command'])) {
    $response = array('success' => '1');
    if ($_GET['command'] == "remove_scene") {
        if (isset($_Get['id'])) {
            $result = array('result' => 'success');
        } else {
            $result = array('result' => 'failure');
        }
    }

    if($_GET['command'] == "search"){
        include_once('php/info_database.php');
        echo json_encode(search($_GET));
    }
}