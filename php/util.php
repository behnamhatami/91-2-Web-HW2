<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Behnam
 * Date: 4/6/13
 * Time: 11:09 PM
 * To change this template use File | Settings | File Templates.
 */

define('url_base', 'http://cinematicket.org');
define('encoding', 'UTF-8');

function time_to_int($in)
{
    return ($in - $in % 100) + intval(($in % 100) / 60 * 100);
}

function str_to_time($in)
{
    return trim(mb_replace(':', '', normalize_string($in)));
}

function day_to_int($in)
{
    $week = ['شنبه' => 0, 'يکشنبه' => 1, 'دوشنبه' => 2, 'سه شنبه' => 3, 'چهارشنبه' => 4, 'پنج شنبه' => 5, 'جمعه' => 6];
    return $week[$in];
}

function truncate_string($str, $limit)
{
    if (mb_strlen($str, encoding) > $limit)
        return mb_substr($str, 0, $limit - 4, encoding) . ' ...';
    else return $str;
}

function header_maker($field_array)
{
    $curl_request_headers = array();
    foreach ($field_array as $key => $value)
        $curl_request_headers[] = "$key: $value";

    return $curl_request_headers;
}

function run_sql_command(&$db, $sql, $info)
{
    if (isset($info)) {
        $cmd = $db->prepare($sql);
        bind_command($cmd, $info);
        $cmd->execute();
    } else {
        $db->exec($sql);
    }
}

function login_required()
{
    if (!isset($_SESSION['username'])) {
        header('Location: index.php');
        exit(0);
    }
}

function login_passed()
{
    if (isset($_SESSION['username'])) {
        header('Location: home.php');
        exit(0);
    }
}

function logout()
{
    unset($_SESSION['username']);
    login_required();
}

function login(){
    if (isset($_POST['username'])) {
        include_once('php/info_database.php');
        $_SESSION['user_id'] = add_user_if_not_exist($_POST['username']);
        $_SESSION['username'] = $_POST['username'];
    }
}


function unicode_trim ($str) {
    return preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $str);
}

function normalize_string($str)
{
    return unicode_trim(html_entity_decode($str));
}

function bind_command(&$cmd, $info)
{
    foreach ($info as $key => &$value)
        $cmd->bindParam(':' . $key, $value);
}

function standardize_url($url)
{
    $len_str = mb_Strlen($url, encoding);
    if ($len_str > 2 && mb_substr($url, 0, 1, encoding) == '.' && mb_substr($url, 1, 1, encoding) == '/')
        return url_base . mb_substr($url, 1, $len_str - 1, encoding);
    if ($len_str > 1 && mb_substr($url, 0, 1, encoding) == '/')
        return url_base . $url;
    return $url;
}

function new_strpos($string, $needle, $nth)
{
    $s_len = mb_strlen($string, encoding);
    $n_len = mb_strlen($needle, encoding);
    $n = 0;

    for ($i = 0; $i < $s_len - $n_len; $i++) {
        for ($j = 0; $j < $n_len; $j++) {
            if (mb_substr($string, $i + $j, 1, encoding) != mb_substr($needle, $j, 1, encoding))
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

function mb_replace($search, $replace, $subject, &$count = 0)
{
    if (!is_array($search) && is_array($replace)) {
        return false;
    }
    if (is_array($subject)) {
        // call mb_replace for each single string in $subject
        foreach ($subject as &$string) {
            $string = & mb_replace($search, $replace, $string, $c);
            $count += $c;
        }
    } elseif (is_array($search)) {
        if (!is_array($replace)) {
            foreach ($search as &$string) {
                $subject = mb_replace($string, $replace, $subject, $c);
                $count += $c;
            }
        } else {
            $n = max(count($search), count($replace));
            while ($n--) {
                $subject = mb_replace(current($search), current($replace), $subject, $c);
                $count += $c;
                next($search);
                next($replace);
            }
        }
    } else {
        $parts = mb_split(preg_quote($search), $subject);
        $count = count($parts) - 1;
        $subject = implode($replace, $parts);
    }
    return $subject;
}

function println($string_message = '')
{
    return isset($_SERVER['SERVER_PROTOCOL']) ? print "$string_message<br />" . PHP_EOL :
        print $string_message . PHP_EOL;
}

function get_html_content($url)
{

    $curl_request_headers = header_maker(array(
        'Accept' => 'text/html',
        'Accept-Language' => 'en-US,en;q=0.5',
        'Cache-Control' => 'max-age=0',
        'Connection' => 'keep-alive',
        'Host' => 'cinematicket.org',
        'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:19.0) Gecko/20100101 Firefox/19.0'
    ));
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
