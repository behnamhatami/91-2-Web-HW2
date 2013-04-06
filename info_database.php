<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Behnam
 * Date: 4/6/13
 * Time: 11:11 PM
 * To change this template use File | Settings | File Templates.
 */

include_once('info_extractor.php');

function insert_cinema($db, $info){

}

function insert_film($db, $info){

}

function insert_scene($db, $info, $film_id){

}

echo '<pre>';
$films_url = get_films_url($url_film, $header);
print_r($films_url);
$cinemas_url = get_cinemas_url($url_cinema, $header);
echo "URl's Fetched";
$db = 0;
print_r($cinemas_url);
return;
foreach($cinemas_url as $url){
    echo $url;
    $cinema_id = insert_cinema($db, get_cinema_info($url, $header));
}

echo "Cinema's Information Updated";
foreach($films_url as $url){
    $film_id = insert_film($db, get_film_info($url, $header));
    foreach(get_scene_of_film($url, $header) as $scene){
        $scene_id = insert_scene($db, $scene, $film_id);
    }
}

echo "Film's Information Updated";