<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Behnam
 * Date: 4/6/13
 * Time: 9:58 AM
 * To change this template use File | Settings | File Templates.
 */


include_once('simple_html_dom.php');
include_once('util.php');

define('url_film', 'http://cinematicket.org/?p=Films');
define('url_cinema', 'http://cinematicket.org/?p=cinema');

function get_films_url($url)
{
    $html = get_html_content($url);
    $film_box = $html->find('div.filmbox', 0);
    $ret = array();
    foreach ($film_box->find('div.con') as $film)
        $ret[] = standardize_url($film->find('span', 1)->find('a', 0)->href);
    return $ret;
}

function get_cinemas_url($url)
{
    $html = get_html_content($url);
    $tabs = $html->find('div.tab_container', 0);
    $ret = array();
    foreach ($tabs->find('div.cintab_content div.cinbox div.cin div.right h2 a') as $a)
        $ret[] = standardize_url($a->href);
    return $ret;
}

function get_cinema_info($html)
{

    $cinema = $html->find('div.detailbox div.contain div.film', 0);
    $ret = array(
        'name' => normalize_string($cinema->find('h1', 0)->innertext),
        'address' => normalize_string($cinema->find('p span.info', 0)->plaintext),
        'phone' => normalize_string($cinema->find('p span.info', 1)->plaintext)
    );
    return $ret;
}

function get_film_info($html)
{
    $html = $html->find('div.detailbox div.contain', 0);
    $film = $html->find('div.film', 0);
    $poster_url = $html->find('div.imgside p img.image', 0)->src;
    $title = normalize_string($film->find('h1', 0)->innertext);
    $title = trim(mb_substr($title, 0, mb_strpos($title, '(', 0, encoding), encoding));
    $directors = normalize_string($film->find('p span.info', 0)->innertext);
    $producers = normalize_string($film->find('p span.info', 1)->innertext);
    $actors = normalize_string($film->find('p span.info', 2)->innertext);
    $actors = trim(mb_substr($actors, 0, new_strpos($actors, '،', 2), encoding));
    $actors = trim(mb_substr($actors, 0, new_strpos($actors, '-', 2), encoding));

    $ret = array(
        'name' => $title,
        'poster' => standardize_url($poster_url),
        'directors' => $directors,
        'producers' => $producers,
        'actors' => $actors
    );

    return $ret;
}


function get_scene_of_film($html)
{
    $boxonline = $html->find('table#ctl00_dlcinemaonline', 0);
    $boxoffline = $html->find('table#ctl00_dlcinema', 0);
    $ret = array();
    foreach (array($boxoffline, $boxonline) as $box) {
        if (!isset($box))
            continue;
        foreach ($box->find('tbody tr td') as $row) {
            $tgtop = $row->find('div.toggletop', 0);
            if ($tgtop == null)
                continue;
            $phone = normalize_string($tgtop->find('div.r span', 0)->innertext);
            $phone = trim(mb_substr($phone, mb_strpos($phone, ':', 0, encoding) + 1, mb_strlen($phone, encoding), encoding));
            foreach ($row->find('div.togglebox div.content div.sance table tbody tr td') as $sance) {
                $date = normalize_string($sance->innertext);
                $start = mb_strpos($date, '(', 0, encoding);
                $finish = mb_strpos($date, ')', 0, encoding);
                $day = trim(mb_substr($date, 0, $start, encoding));
                $date = trim(mb_substr($date, $start + 1, $finish - $start - 1, encoding));
                foreach ($sance->find(' div.sancebox div.s') as $scene) {
                    $time = trim(mb_replace(':', '', normalize_string($scene->innertext)));
                    $ret[] = array(
                        'phone' => $phone,
                        'day' => $day,
                        'date' => $date,
                        'time_fr' => $time
                    );
                }
            }
        }
    }
    return $ret;
}
