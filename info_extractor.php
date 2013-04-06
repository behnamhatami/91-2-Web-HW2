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
$url_film = 'http://cinematicket.org/?p=Films';
$url_cinema = 'http://cinematicket.org/?p=cinema';
$header = array(
    'Accept' => 'text/html',
    'Accept-Language' => 'en-US,en;q=0.5',
    'Cache-Control' => 'max-age=0',
    'Connection' => 'keep-alive',
    'Host' => 'cinematicket.org',
    'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:19.0) Gecko/20100101 Firefox/19.0'
);

function get_films_url($url, $header)
{
    $html = get_html_content($url, $header);
    $film_box = $html->find('div.filmbox', 0);
    $ret = array();
    foreach ($film_box->find('div.con') as $film) {
        $ret[] = $film->find('span', 1)->find('a', 0)->href;
    }
    return $ret;
}

function get_cinemas_url($url, $header)
{
    $html = get_html_content($url, $header);
    $tabs = $html->find('div.tab_container', 0);
    $ret = array();
    foreach ($tabs->find('div.cintab_content div.cinbox div.cin div.right h2 a') as $a) {
        $ret[] = $a->href;
    }
    return $ret;
}

function get_cinema_info($url, $header)
{
    $html = get_html_content($url, $header);
    $cinema = $html->find('div.detailbox div.contain div.film', 0);
    $ret = [
        'title' => $cinema->find('h1', 0)->innertext,
        'address' => $cinema->find('p span.info', 0)->innertext,
        'phone' => $cinema->find('p span.info', 1)->innertext
    ];
    return $ret;
}

function get_film_info($url, $header)
{
    $html = get_html_content($url, $header);
    #html = $html->find('div.detailbox div.contain', 0);
    $film = $html->find('div.film', 0);
    $poster_url = $html->find('div.imgside p img.image', 0)->src;
    $title = html_entity_decode($film->find('h1', 0)->innertext);
    $title = trim(substr($title, 0, strpos($title, '(')));
    $directors = trim(html_entity_decode($film->find('p span.info', 0)->innertext));
    $producers = trim(html_entity_decode($film->find('p span.info', 1)->innertext));
    $actors = trim(html_entity_decode($film->find('p span.info', 2)->innertext));
    $actors = preg_split('[،]', trim(substr($actors, 0, mystrpos($actors, '،', 2))));

    $ret = [
        'title' => $title,
        'poster_url' => $poster_url,
        'directors' => $directors,
        'producers' => $producers,
        'actors' => $actors
    ];

    return $ret;
}


function get_scene_of_film($url, $header)
{
    $html = get_html_content($url, $header);
    $boxonline = $html->find('table#ctl00_dlcinemaonline', 0);
    $boxoffline = $html->find('table#ctl00_dlcinema', 0);
    $ret = array();
    foreach ([$boxoffline, $boxonline] as $box) {
        foreach ($box->find('tbody tr td') as $row) {
            $tgtop = $row->find('div.toggletop', 0);
            if ($tgtop == null)
                continue;
            $phone = html_entity_decode($tgtop->find('div.r span', 0)->innertext);
            $phone = trim(substr($phone, strpos($phone, ':') + 1));

            foreach ($row->find('div.togglebox div.content div.sance table tbody tr td') as $sance) {
                $date = html_entity_decode($sance->innertext);
                $start = strpos($date, '(');
                $finish = strpos($date, ')');
                $day = trim(substr($date, 0, $start));
                $date = trim(substr($date, $start + 1, $finish - $start - 1));
                foreach ($sance->find(' div.sancebox div.s') as $scene) {
                    $time = trim(str_replace(':', '', html_entity_decode($scene->innertext)));
                    $ret[] = [
                        'phone' => $phone,
                        'day' => $day,
                        'date' => $date,
                        'time' => $time
                    ];
                    print_r([
                        'phone' => $phone,
                        'day' => $day,
                        'date' => $date,
                        'time' => $time
                    ]);
                }
            }
        }
    }
    return $ret;
}

