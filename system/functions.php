<?php
function option($option_name = "general_settings", $echo = false)
{
    $option_value = database::find_option($option_name)["option_value"];
    if ($echo === true) {
        echo $option_value;
    } else {
        return $option_value;
    }
}

function get_proxy()
{
    $proxy = database::find_random_proxy();
    if (!empty($_SESSION["proxy"]["ip"] ?? null)) {
        return $_SESSION["proxy"];
    } else if (!empty($proxy["ip"])) {
        $_SESSION["proxy"] = $proxy;
        return $proxy;
    } else {
        return false;
    }
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

function content($content_slug)
{
    $content = database::find("SELECT * FROM contents WHERE content_slug='$content_slug' LIMIT 1")[0];
    return $content;
}

function language_exists($language)
{
    if (file_exists(__DIR__ . "/../language/" . $language . ".php")) {
        return true;
    } else {
        return false;
    }
}

function return_json($array)
{
    unset($_SESSION['result'][$_SESSION["token"]]);
    if (empty($array["links"]["0"]["url"])) {
        echo "error";
        die();
    } else {
        $array["video_url"] = $_SESSION['video'][$_SESSION["token"]];
        $array["client_ip"] = $_SESSION['ip'][$_SESSION["token"]];
        database::create_log($array);
        $_SESSION["result"][$_SESSION["token"]] = $array;
        header("Content-Type: application/json");
        echo json_encode($array);
        die();
    }
}

function check_url($url)
{
    if (empty($url)) {
        echo "error";
        die();
    }
}

function redirect($url)
{
    header('Location: ' . $url);
}

function format_seconds($seconds)
{
    return gmdate(($seconds > 3600 ? "H:i:s" : "i:s"), $seconds);
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

function sort_by_quality($a, $b)
{
    return (int)$a['quality'] - (int)$b['quality'];
}

function sort_by_name($a, $b)
{
    return strcmp($a["name"], $b["name"]);
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

function generate_string($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function create_fingerprint($string1, $string2)
{
    $fingerprint = sha1($string1 . $string2);
    return $fingerprint;
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

function get_proxy_type($id)
{
    switch ($id ?? 0) {
        case 1:
            $type = CURLPROTO_HTTPS;
            break;
        case 2:
            $type = CURLPROXY_SOCKS4;
            break;
        case 3:
            $type = CURLPROXY_SOCKS5;
            break;
        default:
            $type = CURLPROXY_HTTP;
            break;
    }
    return $type;
}

function url_get_contents($url, $enable_proxies = false)
{
    $cookie_file_name = $_SESSION["token"] . ".txt";
    $cookie_file = join(DIRECTORY_SEPARATOR, [sys_get_temp_dir(), $cookie_file_name]);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, _REQUEST_USER_AGENT);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    if ($enable_proxies) {
        if (!empty($_SESSION["proxy"] ?? null)) {
            $proxy = $_SESSION["proxy"];
        } else {
            $proxy = get_proxy();
            $_SESSION["proxy"] = $proxy;
        }
        curl_setopt($ch, CURLOPT_PROXY, $proxy['ip'] . ":" . $proxy['port']);
        curl_setopt($ch, CURLOPT_PROXYTYPE, get_proxy_type($proxy['type']));
        if (!empty($proxy['username']) && !empty($proxy['password'])) {
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy['username'] . ":" . $proxy['password']);
        }
        $chunkSize = 1000000;
        curl_setopt($ch, CURLOPT_TIMEOUT, (int)ceil(3 * (round($chunkSize / 1048576, 2) / (1 / 8))));
    }
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    if (file_exists($cookie_file)) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    }
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function unshorten($url, $enable_proxies = false, $max_redirs = 3)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, $max_redirs);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_USERAGENT, _REQUEST_USER_AGENT);
    curl_setopt($ch, CURLOPT_URL, $url);
    if ($enable_proxies) {
        if (!empty($_SESSION["proxy"] ?? null)) {
            $proxy = $_SESSION["proxy"];
        } else {
            $proxy = get_proxy();
            $_SESSION["proxy"] = $proxy;
        }
        curl_setopt($ch, CURLOPT_PROXY, $proxy['ip'] . ":" . $proxy['port']);
        curl_setopt($ch, CURLOPT_PROXYTYPE, get_proxy_type($proxy['type']));
        if (!empty($proxy['username']) && !empty($proxy['password'])) {
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy['username'] . ":" . $proxy['password']);
        }
        $chunkSize = 1000000;
        curl_setopt($ch, CURLOPT_TIMEOUT, (int)ceil(3 * (round($chunkSize / 1048576, 2) / (1 / 8))));
    }
    curl_exec($ch);
    $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);
    return $url;
}

function get_file_size($url, $enable_proxies = false, $format = true)
{
    $cookie_file_name = $_SESSION["token"] . ".txt";
    $cookie_file = join(DIRECTORY_SEPARATOR, [sys_get_temp_dir(), $cookie_file_name]);
    $result = -1;  // Assume failure.
    // Issue a HEAD request and follow any redirects.
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_NOBODY, true);
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_REFERER, '');
    //curl_setopt($curl, CURLOPT_INTERFACE, '');
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_USERAGENT, _REQUEST_USER_AGENT);
    if ($enable_proxies) {
        if (!empty($_SESSION["proxy"] ?? null)) {
            $proxy = $_SESSION["proxy"];
        } else {
            $proxy = get_proxy();
            $_SESSION["proxy"] = $proxy;
        }
        curl_setopt($curl, CURLOPT_PROXY, $proxy['ip'] . ":" . $proxy['port']);
        curl_setopt($curl, CURLOPT_PROXYTYPE, get_proxy_type($proxy['type']));
        if (!empty($proxy['username']) && !empty($proxy['password'])) {
            curl_setopt($curl, CURLOPT_PROXYUSERPWD, $proxy['username'] . ":" . $proxy['password']);
        }
        $chunkSize = 1000000;
        curl_setopt($curl, CURLOPT_TIMEOUT, (int)ceil(3 * (round($chunkSize / 1048576, 2) / (1 / 8))));
    }
    if (file_exists($cookie_file)) {
        curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_file);
    }
    $headers = curl_exec($curl);
    if (curl_errno($curl) == 0) {
        $result = (int)curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    }
    curl_close($curl);
    if ($result > 100) {
        switch ($format) {
            case true:
                return format_size($result);
                break;
            case false:
                return $result;
                break;
            default:
                return format_size($result);
                break;
        }
    } else {
        return "";
    }
}

function beautify_filename($filename)
{
    // reduce consecutive characters
    $filename = preg_replace(array(
        // "file   name.zip" becomes "file-name.zip"
        '/ +/',
        // "file___name.zip" becomes "file-name.zip"
        '/_+/',
        // "file---name.zip" becomes "file-name.zip"
        '/-+/'
    ), '-', $filename);
    $filename = preg_replace(array(
        // "file--.--.-.--name.zip" becomes "file.name.zip"
        '/-*\.-*/',
        // "file...name..zip" becomes "file.name.zip"
        '/\.{2,}/'
    ), '.', $filename);
    // lowercase for windows/unix interoperability http://support.microsoft.com/kb/100625
    $filename = mb_strtolower($filename, mb_detect_encoding($filename));
    // ".file-name.-" becomes "file-name"
    $filename = trim($filename, '.-');
    return $filename;
}

function filter_filename($filename, $beautify = true)
{
    // sanitize filename
    $filename = preg_replace(
        '~
        [<>:"/\\|?*]|            # file system reserved https://en.wikipedia.org/wiki/Filename#Reserved_characters_and_words
        [\x00-\x1F]|             # control characters http://msdn.microsoft.com/en-us/library/windows/desktop/aa365247%28v=vs.85%29.aspx
        [\x7F\xA0\xAD]|          # non-printing characters DEL, NO-BREAK SPACE, SOFT HYPHEN
        [#\[\]@!$&\'()+,;=]|     # URI reserved https://tools.ietf.org/html/rfc3986#section-2.2
        [{}^\~`]                 # URL unsafe characters https://www.ietf.org/rfc/rfc1738.txt
        ~x',
        '-', $filename);
    // avoids ".", ".." or ".hiddenFiles"
    $filename = ltrim($filename, '.-');
    // optional beautification
    if ($beautify) $filename = beautify_filename($filename);
    // maximize filename length to 255 bytes http://serverfault.com/a/9548/44086
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $filename = mb_strcut(pathinfo($filename, PATHINFO_FILENAME), 0, 255 - ($ext ? strlen($ext) + 1 : 0), mb_detect_encoding($filename)) . ($ext ? '.' . $ext : '');
    return $filename;
}

function sanitize_filename($string, $ftype)
{
    return (filter_filename($string) ?? "video") . "." . $ftype;
}

function clear_string($data)
{
    $data = stripslashes(trim($data));
    $data = strip_tags($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function xss_clean($data)
{
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function get_main_domain($host)
{
    $main_host = strtolower(trim($host));
    $count = substr_count($main_host, '.');
    if ($count === 2) {
        if (strlen(explode('.', $main_host)[1]) > 3) $main_host = explode('.', $main_host, 2)[1];
    } else if ($count > 2) {
        $main_host = get_main_domain(explode('.', $main_host, 2)[1]);
    }
    return $main_host;
}

function force_download($remoteURL, $vidName, $ftype)
{
    $fsize = get_file_size($remoteURL, false, false);
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);
    //header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . htmlspecialchars_decode(sanitize_filename($vidName, $ftype)) . '"');
    header("Content-Transfer-Encoding: binary");
    header("Accept-Ranges: bytes");
    if ($fsize > 0) {
        header('Content-Length: ' . $fsize);
    }
    header('Connection: Close');
    @ini_set('max_execution_time', 0);
    @set_time_limit();
    ob_clean();
    flush();
    // Activate flush
    if (function_exists('apache_setenv')) {
        apache_setenv('no-gzip', 1);
    }
    @ini_set('zlib.output_compression', false);
    ini_set('implicit_flush', true);
    // CURL Process
    $ch = curl_init();
    $chunkEnd = $chunkSize = 1000000;  // 1 MB in bytes
    $tries = $count = $chunkStart = 0;
    $cookie_file_name = $_SESSION["token"] . ".txt";
    $cookie_file = join(DIRECTORY_SEPARATOR, [sys_get_temp_dir(), $cookie_file_name]);
    while ($fsize > $chunkStart) {
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $remoteURL);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, _REQUEST_USER_AGENT);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RANGE, $chunkStart . '-' . $chunkEnd);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BUFFERSIZE, $chunkSize);
        if (!empty($_SESSION["proxy"])) {
            $proxy = $_SESSION["proxy"];
            curl_setopt($ch, CURLOPT_PROXY, $proxy['ip'] . ":" . $proxy['port']);
            curl_setopt($ch, CURLOPT_PROXYTYPE, get_proxy_type($proxy['type']));
            if (!empty($proxy['username']) && !empty($proxy['password'])) {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy['username'] . ":" . $proxy['password']);
            }
            $chunkSize = 1000000;
            curl_setopt($ch, CURLOPT_TIMEOUT, (int)ceil(3 * (round($chunkSize / 1048576, 2) / (1 / 8))));
        }
        if (file_exists($cookie_file)) {
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
        }
        //curl_setopt($ch, CURLOPT_MAX_RECV_SPEED_LARGE, "100");
        $output = curl_exec($ch);
        $curlInfo = curl_getinfo($ch);
        if ($curlInfo['http_code'] != "206" && $tries < 10) {
            $tries++;
            continue;
        } else {
            $tries = 0;
            echo $output;
            flush();
            ob_implicit_flush(true);
            if (ob_get_length() > 0) ob_end_flush();
        }
        $chunkStart += $chunkSize;
        $chunkStart += ($count == 0) ? 1 : 0;
        $chunkEnd += $chunkSize;
        $count++;
        //sleep(10);
    }
    curl_close($ch);
    exit;
}

function force_download_legacy($url, $title, $type)
{
    $context_options = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        )
    );
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . sanitize_filename($title, $type) . '"');
    header("Content-Transfer-Encoding: binary");
    header('Expires: 0');
    header('Pragma: public');
    $file_size = get_file_size($url, false, false);
    if ($file_size > 100) {
        header('Content-Length: ' . $file_size);
    }
    if (isset($_SERVER['HTTP_REQUEST_USER_AGENT']) && strpos($_SERVER['HTTP_REQUEST_USER_AGENT'], 'MSIE') !== FALSE) {
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
    }
    header('Connection: Close');
    ob_clean();
    flush();
    readfile($url, "", stream_context_create($context_options));
    exit;
}

function force_download_chunks($urls, $title, $type)
{
    $context_options = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        )
    );
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . sanitize_filename($title, $type) . '"');
    header("Content-Transfer-Encoding: binary");
    header('Expires: 0');
    header('Pragma: public');
    if (isset($_SERVER['HTTP_REQUEST_USER_AGENT']) && strpos($_SERVER['HTTP_REQUEST_USER_AGENT'], 'MSIE') !== FALSE) {
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
    }
    header('Connection: Close');
    ob_clean();
    flush();
    foreach ($urls as $url) {
        readfile($url, "", stream_context_create($context_options));
    }
    exit;
}

function get_client_ip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function verify_captcha($response, $client_ip)
{
    $secret_key = option("api_key.recaptcha_private");
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.google.com/recaptcha/api/siteverify",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "secret=$secret_key&response=$response&remoteip=$client_ip",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/x-www-form-urlencoded"
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return false;
    } else {
        $response = json_decode($response, true);
        if ($response['success'] === true) {
            return true;
        } else {
            return false;
        }
    }
}

function copyfile_chunked($infile, $outfile)
{
    $chunksize = 10 * (1024 * 1024); // 10 Megs
    $parts = parse_url($infile);
    $i_handle = fsockopen($parts['host'], 80, $errstr, $errcode, 5);
    $o_handle = fopen($outfile, 'wb');
    if ($i_handle == false || $o_handle == false) {
        return false;
    }
    if (!empty($parts['query'])) {
        $parts['path'] .= '?' . $parts['query'];
    }
    $request = "GET {$parts['path']} HTTP/1.1\r\n";
    $request .= "Host: {$parts['host']}\r\n";
    $request .= "User-Agent: Mozilla/5.0\r\n";
    $request .= "Keep-Alive: 115\r\n";
    $request .= "Connection: keep-alive\r\n\r\n";
    fwrite($i_handle, $request);
    $headers = array();
    while (!feof($i_handle)) {
        $line = fgets($i_handle);
        if ($line == "\r\n") break;
        $headers[] = $line;
    }
    $length = 0;
    foreach ($headers as $header) {
        if (stripos($header, 'Content-Length:') === 0) {
            $length = (int)str_replace('Content-Length: ', '', $header);
            break;
        }
    }
    $cnt = 0;
    while (!feof($i_handle)) {
        $buf = '';
        $buf = fread($i_handle, $chunksize);
        $bytes = fwrite($o_handle, $buf);
        if ($bytes == false) {
            return false;
        }
        $cnt += $bytes;
        if ($cnt >= $length) break;
    }
    fclose($i_handle);
    fclose($o_handle);
    return $cnt;
}