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

function insert_or_update_cinema_in_database($db, $info)
{
    $id = get_cinema_id_by_phone($db, $info['phone']);
    if (isset($id)) {
        $info['id'] = $id;
        update_cinema_in_database($db, $info);
    } else {
        $sql = "INSERT INTO `whw_2`.`php_cinema` (`id` ,`name` ,`address` ,`phone`) " .
            "VALUES (NULL , :name, :address, :phone);";
        run_sql_command($db, $sql, $info);
    }
}

function update_cinema_in_database($db, $info)
{
    $sql = "UPDATE `whw_2`.`php_cinema` SET `name` = :name," .
        "`address` = :address,`phone` = :phone WHERE `php_cinema`.`id`=:id;";
    run_sql_command($db, $sql, $info);
}

function insert_or_update_film_in_database($db, $info)
{
    $id = get_film_id_by_name($db, $info['name']);
    if (isset($id)) {
        $info['id'] = $id;
        update_film_in_database($db, $info);
    } else {
        $sql = "INSERT `whw_2`.`php_film` (`id`, `name`, `poster`, `producers`, `directors`, `actors`)" .
            "VALUES (NULL, :name, :poster, :producers, :directors, :actors)";
        run_sql_command($db, $sql, $info);
    }
}

function update_film_in_database($db, $info)
{
    $sql = "UPDATE `whw_2`.`php_film` SET `name` = :name," .
        " `poster` = :poster, `producers` = :producers, `directors`: :directors," .
        " `actors`: :actors WHERE `php_film`.`id`=:id;";
    run_sql_command($db, $sql, $info);
}


function insert_scene_in_database($db, $info, $film_id)
{
    $info['film_id'] = $film_id;
    $info['cinema_id'] = get_cinema_id_by_phone($db, $info['phone']);
    $time_to = $info['time_fr'] + 145;
    if ($time_to % 100 >= 60)
        $time_to += 40;
    $info['time_to'] = $time_to % 2400;

    unset($info['phone']);

    $sql = "INSERT `whw_2`.`php_scene` (`id`, `date`, `day`, `time_fr`, `time_to`, `cinema_id`, `film_id`)" .
        "VALUES (NULL, :date, :day, :time_fr, :time_to, :cinema_id, :film_id)";
    run_sql_command($db, $sql, $info);
}

function get_film_id_by_name($db, $name)
{
    $sql = "SELECT * FROM `whw_2`.`php_film` Where `php_film`.`name`='$name';";
    foreach ($db->query($sql) as $row)
        return $row['id'];
    return null;
}

function get_cinema_id_by_phone($db, $phone)
{
    $sql = "SELECT * FROM `whw_2`.`php_cinema` Where `php_cinema`.`phone`='$phone';";
    foreach ($db->query($sql) as $row)
        return $row['id'];
    return null;
}


function update_all_live_film_info()
{
    $db = get_db_connection();
    $film_infos = get_live_films_from_internet(url_film);
    $ret = array();
    foreach ($film_infos as $film) {
        if (get_film_id_by_name($db, $film['name']) == null) {
            insert_or_update_film($db, $film['url']);
            $ret[] = get_film_full_info(get_film_id_by_name($db, $film['name']));
        }
    }
    return $ret;
}

function update_special_live_film_info($id)
{
    $db = get_db_connection();
    $info = get_film_full_info($id);
    $film_infos = get_live_films_from_internet(url_film);
    foreach ($film_infos as $film)
        if ($film['name'] == $info['name'])
            insert_or_update_film($db, $film['url']);
}

function insert_or_update_film($db, $url)
{
    gc_disable();
    $html = get_html_content($url);
    $info = get_film_info_from_internet($html);
    insert_or_update_film_in_database($db, $info);
    $film_id = get_film_id_by_name($db, $info['name']);
    foreach (get_scenes_of_film_from_internet($html) as $scene)
        insert_scene_in_database($db, $scene, $film_id);

    gc_enable();
}


function update_all_database()
{
    $db = get_db_connection();

    foreach (get_cinemas_info_from_internet(url_cinema) as $info)
        insert_or_update_cinema_in_database($db, $info);

    foreach (get_films_url_from_internet(url_film) as $url) {
        insert_or_update_film($db, $url);
    }
}

function get_all_films()
{
    $db = get_db_connection();
    $sql = "SELECT * FROM `whw_2`.`php_film`;";
    return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function get_all_cinemas()
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

function get_cinemas_that_show_film($id)
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

function search_cinema_by_name($name)
{
    $db = get_db_connection();
    $sql = "SELECT * FROM `whw_2`.`php_cinema` WHERE `php_cinema`.`name` LIKE '%$name%'";
    $items = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    return $items;

}

function seach_film_by_name($name)
{
    $db = get_db_connection();
    $sql = "SELECT * FROM `whw_2`.`php_film` WHERE `php_film`.`name` LIKE '%$name%'";
    $items = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    return $items;
}

function general_scene_search($date_fr, $date_to, $time_fr, $time_to, $film_name, $cinema_name, $city_name)
{
    $db = get_db_connection();

    $sql = "SELECT " .
        "`TEMP`.`id`, `TEMP`.`time_fr`, `TEMP`.`day`, `TEMP`.`time_to`, `TEMP`.`date`, " .
        "`TEMP`.`film_name`, `TEMP`.`directors`, `TEMP`.`producers`, `TEMP`.`actors`, " .
        "`php_cinema`.`name` as `cinema_name`, `php_cinema`.`address`, `php_cinema`.`phone` " .
        "FROM (" .
        "SELECT " .
        "`TEMP`.`id`, `TEMP`.`cinema_id`, `TEMP`.`day`, `TEMP`.`time_fr`, " .
        "`TEMP`.`time_to`, `TEMP`.`date`, `php_film`.`name` as `film_name`, " .
        "`php_film`.`directors` , `php_film`.`producers`, `php_film`.`actors` " .
        "FROM " .
        "(SELECT * FROM `whw_2`.`php_scene` " .
        "WHERE `php_scene`.`date` >= '$date_fr' AND `php_scene`.`date` <= '$date_to' " .
        "AND `php_scene`.`time_fr` >= $time_fr AND `php_scene`.`time_to` <= $time_to AND " .
        "`php_scene`.`film_id` IN " .
        "( SELECT `php_film`.`id` FROM `whw_2`.`php_film` " .
        "WHERE `php_film`.`name` LIKE '%$film_name%' ) " .
        "AND `php_scene`.`cinema_id` IN " .
        "( SELECT `php_cinema`.`id` FROM `whw_2`.`php_cinema` " .
        "WHERE `php_cinema`.`name` LIKE '%$cinema_name%' AND " .
        "`php_cinema`.`address` LIKE '%$city_name%' ) " .
        "ORDER BY `php_scene`.`time_fr` ASC LIMIT 0 , 60) AS `TEMP`, `whw_2`.`php_film` " .
        "WHERE `php_film`.`id` = `TEMP`.`film_id`) AS `TEMP`, `whw_2`.`php_cinema` " .
        "WHERE `php_cinema`.`id` = `TEMP`.`cinema_id`";
    $items = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    return $items;
}

function validate_search_info($info)
{
    if ($info['from_date'] == "")
        $info['from_date'] = '1390-1-1';
    if ($info['date_to'] == "")
        $info['date_to'] = '1399-1-1';
    if ($info['time_to'] == "")
        $info['time_to'] = '2359';
    if ($info['from_time'] == "")
        $info['from_time'] = "0000";
    return $info;
}

function search($info)
{
    $info = validate_search_info($info);
    $film_name = $info['film_name'];
    $cinema_name = $info['cinema_name'];
    $time_to = str_to_time($info['time_to']);
    $from_time = str_to_time($info['from_time']);
    $date_to = $info['date_to'];
    $from_date = $info['from_date'];
    $city_name = $info['city_name'];
    $scenes = general_scene_search($from_date, $date_to, $from_time, $time_to, $film_name, $cinema_name, $city_name);

    return $scenes;
}

function add_user_if_not_exist($username)
{
    $db = get_db_connection();
    $sql = "INSERT INTO `whw_2`.`php_user` " .
        "(`id` ,`username`) " .
        "VALUES " .
        "(NULL , '$username');";
    run_sql_command($db, $sql, null);
    $sql = "SELECT *" .
        "FROM `whw_2` . `php_user`" .
        "WHERE `php_user`.`username` = '$username'";
    foreach ($db->query($sql) as $row)
        return $row['id'];
}

function get_all_scene_attend()
{
    $db = get_db_connection();
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT `TEMP`.`id`, `TEMP`.`time_fr`, `TEMP`.`day`, `TEMP`.`time_to`, `TEMP`.`date`, " .
        "`TEMP`.`film_name`, `TEMP`.`directors`, `TEMP`.`producers`, `TEMP`.`actors`, " .
        "`php_cinema`.`name` as `cinema_name`, `php_cinema`.`address`, `php_cinema`.`phone` " .
        "FROM (SELECT `TEMP`.`id`, `TEMP`.`cinema_id`, `TEMP`.`day`, `TEMP`.`time_fr`, " .
        "`TEMP`.`time_to`, `TEMP`.`date`, `php_film`.`name` as `film_name`, `php_film`.`directors` , " .
        "`php_film`.`producers`, `php_film`.`actors` FROM (SELECT * FROM `php_scene` WHERE " .
        "`php_scene`.`id` IN (SELECT `php_user_attends`.`scene_id` as `id` FROM " .
        "`whw_2`.`php_user_attends` WHERE `php_user_attends`.`user_id` = $user_id)) AS `TEMP`, " .
        "`whw_2`.`php_film` WHERE `php_film`.`id` = `TEMP`.`film_id`) AS `TEMP`, " .
        "`whw_2`.`php_cinema` WHERE `php_cinema`.`id` = `TEMP`.`cinema_id`";
    return ($db->query($sql)->fetchAll(PDO::FETCH_ASSOC));
}

function remove_scene_attend($scene_id)
{
    $db = get_db_connection();
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM `whw_2`.`php_user_attends` WHERE `php_user_attends`.`user_id` = '$user_id' AND `php_user_attends`.`scene_id` = '$scene_id'";
    run_sql_command($db, $sql, null);
}

function add_scene_attend($scene_id)
{
    $db = get_db_connection();
    $user_id = $_SESSION['user_id'];

    $sql = $sql = "INSERT INTO `whw_2`.`php_user_attends` (`id`, `user_id`, `scene_id`) VALUES (NULL, '$user_id', '$scene_id');";
    run_sql_command($db, $sql, null);
}