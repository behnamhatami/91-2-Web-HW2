<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Behnam
 * Date: 4/6/13
 * Time: 11:09 PM
 * To change this template use File | Settings | File Templates.
 */

function truncate_string($str, $limit)
{
    if (strlen($str) > $limit)
        return substr($str, 0, $limit - 4) . ' ...';
    else return $str;
}

function array_to_header($field_array)
{
    $curl_request_headers = array();
    foreach ($field_array as $key => $value) {
        $curl_request_headers[] = "$key: $value";
    }
    return $curl_request_headers;
}

function run_sql_command(&$db, $sql, $info)
{
    if (isset($info)) {
        $cmd = $db->prepare($sql);
        bindCommand($cmd, $info);
        $cmd->execute();
    } else {
        $db->execute($sql);
    }
}

function normalize($str)
{
    return trim(html_entity_decode($str));
}

function bindCommand(&$cmd, $info)
{
    foreach ($info as $key => &$value)
        $cmd->bindParam(':' . $key, $value);
}

function standardize_url($url)
{
    $base = 'http://cinematicket.org';
    if (strlen($url) > 2 && $url[0] == '.' && $url[1] == '/')
        return $base . substr($url, 1);
    if (strlen($url) > 1 && $url[0] == '/')
        return $base . $url;
    return $url;
}

function mystrpos($string, $needle, $nth)
{
    $s_len = strlen($string);
    $n_len = strlen($needle);
    $n = 0;

    for ($i = 0; $i < $s_len - $n_len; $i++) {
        for ($j = 0; $j < $n_len; $j++) {
            if ($string[$i + $j] != $needle[$j])
                break;
            if ($j == $n_len - 1) {
                if ($n == $nth)
                    return $i;
                else $n++;
            }
        }
    }
    return $s_len;
}

function get_html_content($url, $header)
{
    $curl_request_headers = array_to_header($header);
    session_write_close();

    $curl_url = $url;
//Open connection
    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_COOKIE, '__utma=217119108.1168416451.1364750461.1364750461.1364752762.2; __utmc=217119108; __utmz=217119108.1364750461.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); ASP.NET_SessionId=5ppzrf55kzwxln45slti3jqh; __utmb=217119108.4.10.1364752762');
//Set the url, POST data
    curl_setopt($curl_handle, CURLOPT_URL, $curl_url);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_HEADER, 1);
    curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $curl_request_headers);

    $result = curl_exec($curl_handle);
//Close connection
    curl_close($curl_handle);

    $htmlStr = substr($result, strpos($result, "\r\n\r\n"));

    return str_get_html($htmlStr);
}
