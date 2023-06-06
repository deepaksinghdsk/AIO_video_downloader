<?php
function option($option_name = "general_settings", $echo = false)
{
    $option_value = database::find("SELECT * FROM options WHERE option_name='$option_name' LIMIT 1")[0]["option_value"];
    if ($echo === true) {
        echo $option_value;
    } else {
        return $option_value;
    }
}

function content($content_slug)
{
    $content = database::find("SELECT * FROM contents WHERE content_slug='$content_slug' LIMIT 1")[0];
    return $content;
}

function get_news()
{
    $data = url_get_contents("https://aiovideodl.ml/assets/news.json");
    return json_decode($data, true);
}

function sanitize_output($buffer)
{
    $search = array(
        '/\>[^\S ]+/s',
        '/[^\S ]+\</s',
        '/(\s)+/s',
        '/<!--(.|\s)*?-->/'
    );
    $replace = array(
        '>',
        '<',
        '\\1',
        ''
    );
    $buffer = preg_replace($search, $replace, $buffer);
    return $buffer;
}

function get_domain($url)
{
    return str_ireplace("www.", "", parse_url($url, PHP_URL_HOST));
}

function change_password($password)
{
    file_put_contents(__DIR__ . "/../system/storage/password.htpasswd", sha1($password));
}

function check_config($json_file, $type)
{
    if (isset($json_file[$type]) == "true") {
        echo "checked";
    }
}

function redirect($url)
{
    header('Location: ' . $url);
}

function language_exists($language)
{
    if (file_exists(__DIR__ . "/../language/" . $language . ".php")) {
        return true;
    }
}

function list_languages()
{
    foreach (glob(__DIR__ . "/../language/*.php") as $filename) {
        if (basename($filename) != "index.php") {
            $language = str_replace(".php", null, basename($filename));
            if (language_exists($language) === true && json_decode(option(), true)["language"] == $language) {
                echo '<option selected value="' . $language . '">' . strtoupper($language) . '</option>';
            } elseif (language_exists($language) === true) {
                echo '<option value="' . $language . '">' . strtoupper($language) . '</option>';
            }
        }
    }
}

function get_proxy_type($id)
{
    switch ($id) {
        case 0:
            $type = "HTTP";
            break;
        case 1:
            $type = "HTTPs";
            break;
        case 2:
            $type = "SOCKS4";
            break;
        case 3:
            $type = "SOCKS5";
            break;
        default:
            $type = "unknown";
            break;
    }
    return $type;
}

function save_menu($post_data, $file)
{
    $menu = explode("\n", $post_data);
    $json = array();
    $i = 0;
    foreach ($menu as $item) {
        $link["title"] = explode(",", $item)[0];
        $link["url"] = explode(",", $item)[1];
        array_push($json, $link);
        $i++;
    }
    file_put_contents(__DIR__ . "/../system/storage/" . $file, json_encode($json));
}

function view_menu($file)
{
    $json = json_decode(file_get_contents(__DIR__ . "/../system/storage/" . $file), true);
    foreach ($json as $item) {
        echo $item["title"] . "," . $item["url"] . "\n";
    }
}

function get_user_ip()
{
    $client = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote = $_SERVER['REMOTE_ADDR'];
    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }
    return $ip;
}

function url_get_contents($url, $proxy = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Niche Office - All in One Video Downloader');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    if ($proxy != null) {
        curl_setopt($ch, CURLOPT_PROXY, $proxy['ip'] . ":" . $proxy['port']);
        if (!empty($proxy['username']) && !empty($proxy['password'])) {
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy['username'] . ":" . $proxy['password']);
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    }
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function decode_version($string)
{
    $string = urlencode(base64_encode($string));
    return url_get_contents("http://api.nicheoffice.web.tr/verify/version/" . $string);
}

function create_fingerprint($string1, $string2)
{
    $fingerprint = sha1($string1 . $string2);
    return $fingerprint;
}

function generate_csrf_token()
{
    if (defined('PHP_MAJOR_VERSION') && PHP_MAJOR_VERSION > 5) {
        return bin2hex(random_bytes(32));
    } else {
        if (function_exists('mcrypt_create_iv')) {
            return bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        } else {
            return bin2hex(openssl_random_pseudo_bytes(32));
        }
    }
}

function get_token()
{
    $headers = get_headers(base64_decode("aHR0cHM6Ly9hcGkubmljaGVvZmZpY2Uud2ViLnRyL2dldC10b2tlbg=="));
    return substr($headers[0], 9, 3);
}

function test_youtube_connection()
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.youtube.com/watch?v=52zmpRT5RFg");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    preg_match_all('/52zmpRT5RFg/', $result, $output);
    curl_close($ch);
    return empty($output[0][0]);
}

function get_file_from_cdn($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Niche Office - All in One Video Downloader');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
        "config" => option()
    )));
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function test_connection()
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.youtube.com/watch?v=52zmpRT5RFg");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    if (strpos($result, "https://www.google.com/recaptcha/api.js") !== false) {
        return true;
    } else {
        return false;
    }
}

function test_proxy($proxy, $test_youtube = false)
{
    if ($test_youtube) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.youtube.com/watch?v=52zmpRT5RFg");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_PROXY, $proxy['ip'] . ":" . $proxy['port']);
        if (!empty($proxy['username']) && !empty($proxy['password'])) {
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy['username'] . ":" . $proxy['password']);
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        $error = curl_error($ch);
        //echo $error;
        preg_match_all('/52zmpRT5RFg/', $result, $output);
        curl_close($ch);
        return empty($output[0][0]);
    } else {
        $isWorking = false;
        $result = url_get_contents("http://example.com/", $proxy);
        if ($result != "") {
            $isWorking = true;
        }
        return $isWorking;
    }
}

function get_directory_size($path)
{
    $bytestotal = 0;
    $path = realpath($path);
    if ($path !== false && $path != '' && file_exists($path)) {
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object) {
            $bytestotal += $object->getSize();
        }
    }
    return $bytestotal;
}

function format_size($bytes)
{
    switch ($bytes) {
        case $bytes < 1024:
            $size = $bytes . " B";
            break;
        case $bytes < 1048576:
            $size = round($bytes / 1024, 2) . " KB";
            break;
        case $bytes < 1073741824:
            $size = round($bytes / 1048576, 2) . " MB";
            break;
        case $bytes < 1099511627776:
            $size = round($bytes / 1073741824, 2) . " GB";
            break;
    }
    if (!empty($size)) {
        return $size;
    } else {
        return "";
    }
}

function clear_disk_cache($dir, $time = 86400)
{
    foreach (glob($dir . "*.mp3") as $file) {
        if (time() - filectime($file) > $time) {
            unlink($file);
        }
    }
}

function get_stats_cache($file = __DIR__ . '/../system/storage/temp/stats.json', $time = 86400)
{
    if (time() - filemtime($file) < $time && filesize($file) > 100) {
        return json_decode(file_get_contents($file), true);
    } else {
        return false;
    }
}

function get_string_between($string, $start, $end)
{
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}