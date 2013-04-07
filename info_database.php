<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Behnam
 * Date: 4/6/13
 * Time: 11:11 PM
 * To change this template use File | Settings | File Templates.
 */
include_once('info_extractor.php');


function insert_cinema($db, $info)
{
    $id = get_cinema_id($db, $info['phone']);
    print_r($id);
    if (isset($id)) {
        $info['id'] = $id;
        update_cinema($db, $info);
    } else {
        $cmd = $db->prepare("INSERT INTO `whw_2`.`php_cinema` (`id` ,`name` ,`address` ,`phone`) " .
            "VALUES (NULL , :name, :address, :phone);");

        bindCommand($cmd, $info);
        $cmd->execute();
    }
}

function update_cinema($db, $info)
{
    $cmd = $db->prepare("UPDATE `whw_2`.`php_cinema` SET `name` = :name," .
        "`address` = :address,`phone` = :phone WHERE `php_cinema`.`id`=:id;");
    bindCommand($cmd, $info);
    $cmd->execute();
}

function get_cinema_id($db, &$phone)
{
    $sql = "SELECT * FROM `whw_2`.`php_cinema` Where `php_cinema`.`phone`='$phone';";
    echo $sql;
    foreach ($db->query($sql) as $row) {
        return $row['id'];
    }
}

function insert_film($db, $info)
{
}

function insert_scene($db, $info, $film_id)
{
}

echo '<pre>';
$db = new PDO('mysql:host=localhost;dbname=whw_2', 'behnam', 'ensaniat');
//$films_url = get_films_url($url_film, $header);
$cinemas_url = get_cinemas_url($url_cinema, $header);
foreach ($cinemas_url as $url) {
    $url = standardize_url($url, $url_base);
    $cinema_id = insert_cinema($db, get_cinema_info($url, $header));
    return;
}

foreach ($films_url as $url) {
    $url = standardize_url($url, $url_base);
    $film_id = insert_film($db, get_film_info($url, $header));
    foreach (get_scene_of_film($url, $header) as $scene) {
        $scene_id = insert_scene($db, $scene, $film_id);
    }
}
