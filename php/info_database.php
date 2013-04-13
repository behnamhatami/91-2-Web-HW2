<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Behnam
 * Date: 4/6/13
 * Time: 11:11 PM
 * To change this template use File | Settings | File Templates.
 */

define('username', 'behnam');
define('password', 'ensaniat');
define('address', 'mysql:host=localhost;dbname=whw_2');
include_once('info_extractor.php');

// low-level functions
function get_db_connection()
{
    $db = new PDO(address, username, password);
    $db->query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
    return $db;
}

function insert_cinema($db, $info)
{
    $id = get_cinema_id($db, $info['phone']);
    if (isset($id)) {
        $info['id'] = $id;
        update_cinema($db, $info);
    } else {
        $sql = "INSERT INTO `whw_2`.`php_cinema` (`id` ,`name` ,`address` ,`phone`) " .
            "VALUES (NULL , :name, :address, :phone);";
        run_sql_command($db, $sql, $info);
    }
}

function update_cinema($db, $info)
{
    $sql = "UPDATE `whw_2`.`php_cinema` SET `name` = :name," .
        "`address` = :address,`phone` = :phone WHERE `php_cinema`.`id`=:id;";
    run_sql_command($db, $sql, $info);
}

function insert_film($db, $info)
{
    $id = get_film_id($db, $info['name']);
    if (isset($id)) {
        $info['id'] = $id;
        update_film($db, $info);
    } else {
        $sql = "INSERT `whw_2`.`php_film` (`id`, `name`, `poster`, `producers`, `directors`, `actors`)" .
            "VALUES (NULL, :name, :poster, :producers, :directors, :actors)";
        run_sql_command($db, $sql, $info);
    }
}

function update_film($db, $info)
{
    $sql = "UPDATE `whw_2`.`php_film` SET `name` = :name," .
        " `poster` = :poster, `producers` = :producers, `directors`: :directors," .
        " `actors`: :actors WHERE `php_film`.`id`=:id;";
    run_sql_command($db, $sql, $info);
}


function insert_scene($db, $info, $film_id)
{
    $info['film_id'] = $film_id;
    $info['cinema_id'] = get_cinema_id($db, $info['phone']);
    $time_to = $info['time_fr'] + 145;
    if ($time_to % 100 >= 60)
        $time_to += 40;
    $info['time_to'] = $time_to % 2400;

    unset($info['phone']);
    unset($info['day']);

    $sql = "INSERT `whw_2`.`php_scene` (`id`, `date`, `time_fr`, `time_to`, `cinema_id`, `film_id`)" .
        "VALUES (NULL, :date, :time_fr, :time_to, :cinema_id, :film_id)";
    run_sql_command($db, $sql, $info);
}

function get_film_id($db, $name)
{
    $sql = "SELECT * FROM `whw_2`.`php_film` Where `php_film`.`name`='$name';";
    foreach ($db->query($sql) as $row)
        return $row['id'];
}

function get_cinema_id($db, $phone)
{
    $sql = "SELECT * FROM `whw_2`.`php_cinema` Where `php_cinema`.`phone`='$phone';";
    foreach ($db->query($sql) as $row)
        return $row['id'];
}

function update_database()
{
    gc_disable();
    $db = get_db_connection();

    $films_url = get_films_url(url_film);
    print_r($films_url);
    $cinemas_url = get_cinemas_url(url_cinema);
    print_r($cinemas_url);
    foreach ($cinemas_url as $url) {
        insert_cinema($db, get_cinema_info(get_html_content($url)));
        print_r($url . '\n');
    }

    foreach ($films_url as $url) {
        $html = get_html_content($url);
        print_r($url . '\n');
        $info = get_film_info($html);
        insert_film($db, $info);
        $film_id = get_film_id($db, $info['name']);
        foreach (get_scene_of_film($html) as $scene)
            insert_scene($db, $scene, $film_id);

        gc_enable();
        gc_disable();
    }
    gc_enable();
}

function get_films()
{
    $db = get_db_connection();
    $sql = "SELECT * FROM `whw_2`.`php_film`;";
    return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function get_cinemas()
{
    $db = get_db_connection();
    $sql = "SELECT * FROM `whw_2`.`php_cinema`;";
    return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function get_film_full_info($id)
{
    $db = get_db_connection();
    $sql = "SELECT * FROM `whw_2`.`php_film` WHERE `php_film`.`id`='$id';";
    $res = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    if (count($res) > 0)
        return $res[0];
    else return null;
}

function get_cinema_full_info($id)
{
    $db = get_db_connection();
    $sql = "SELECT * FROM `whw_2`.`php_cinema` WHERE `php_cinema`.`id`='$id';";
    $res = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    if (count($res) > 0)
        return $res[0];
    else return null;
}

function get_film_cinemas($id)
{
    $db = get_db_connection();
    $sql = "SELECT DISTINCT(`php_scene`.`cinema_id`) FROM `whw_2`.`php_scene` WHERE `php_scene`.`film_id`='$id';";
    $items = $db->query($sql)->fetchALL(PDO::FETCH_COLUMN);
    $cinema_infos = array();
    foreach ($items as $item) {
        $cinema_infos[$item] = get_cinema_full_info($item);
        $cinema_infos[$item]['instance'] = array();
    }
    $sql = "SELECT * FROM `whw_2`.`php_scene` WHERE `php_scene`.`film_id`='$id';";
    $items = $db->query($sql)->fetchALL(PDO::FETCH_ASSOC);
    foreach ($items as $item) {
        $cinema_id = $item['cinema_id'];
        $date = $item['date'];
        unset($item['date']);
        unset($item['cinema_id']);
        unset($item['film_id']);
        $cinema_infos[$cinema_id]['instance'][$date][] = $item;
    }
    return $cinema_infos;
}

function search_cinema($name)
{
    $db = get_db_connection();
    $sql = "SELECT * FROM `whw_2`.`php_cinema` WHERE `php_cinema`.`name` LIKE '%$name%'";
    $items = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    return $items;

}

function search_film($name)
{
    $db = get_db_connection();
    $sql = "SELECT * FROM `whw_2`.`php_film` WHERE `php_film`.`name` LIKE '%$name%'";
    $items = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    return $items;
}

function search_scene($date_fr, $date_to, $time_fr, $time_to)
{
    $db = get_db_connection();
    $sql = "SELECT * FROM `whw_2`.`php_scene` WHERE `php_scene`.`date` >= '$date_fr' AND `php_scene`.`date` <= '$date_to'".
    "AND  `php_scene`.`time_fr` >= $time_fr AND `php_scene`.`time_to` <= $time_to";
    $items = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    return $items;
}

//update_database();