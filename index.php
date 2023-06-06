<?php
require_once __DIR__ . "/system/config.php";
$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
$parse_main_url = parse_url($config["url"]);
$parse_current_url = parse_url($current_url);
$canonical_url = $current_url;
if (!isset($parse_main_url["path"])) {
    $parse_main_url["path"] = "";
}
$slug = substr(str_replace($parse_main_url["path"], "", $parse_current_url["path"]), 1);
$content["content_type"] = -1;
preg_match('/watch\?v=(.*)/', $_SERVER["REQUEST_URI"], $watchSlug);
if (isset($_GET["lang"]) != "") {
    if (language_exists(clear_string($_GET["lang"])) === true) {
        $_SESSION["current_language"] = clear_string($_GET["lang"]);
        require_once(__DIR__ . "/language/" . $_SESSION["current_language"] . ".php");
    } else if (isset($_SERVER["HTTP_REFERER"]) != "") {
        redirect($_SERVER["HTTP_REFERER"] . "?lang=" . $_GET["lang"]);
    } else {
        redirect($config["url"]);
    }
} else {
    $canonical_url .= "?lang=" . $_SESSION["current_language"];
}
$template = $config["template"];
include(__DIR__ . "/template/" . $template . "/functions.php");
switch (true) {
    default:
        include(__DIR__ . "/template/" . $template . "/header.php");
        include(__DIR__ . "/template/" . $template . "/main.php");
        include(__DIR__ . "/template/" . $template . "/footer.php");
        break;
    case($slug == "sitemap.xml"):
        include(__DIR__ . "/sitemap.php");
        break;
    case(isset($_GET["u"]) != "" && filter_var($_GET["u"], FILTER_VALIDATE_URL)):
        $newURL = $config["url"] . "/#url=" . $_GET["u"];
        redirect($newURL);
        break;
    case(!empty($watchSlug[1])):
        if (isset($_GET["v"]) != "") {
            $videoId = $_GET["v"];
            $newURL = $config["url"] . "/#url=https://www.youtube.com/watch?v=" . $videoId;
            redirect($newURL);
        } else {
            redirect($config["url"]);
        }
        break;
    case($slug != "" && substr($slug, 0, 1) != "?"):
        $page_exists = database::slug_exists($slug);
        if ($page_exists === 0 || $slug == "home") {
            header('HTTP/1.0 404 Not Found');
            $slug = "";
            $content["content_type"] = 0;
            include(__DIR__ . "/template/" . $template . "/header.php");
            include(__DIR__ . "/template/" . $template . "/404.php");
            include(__DIR__ . "/template/" . $template . "/footer.php");
        } else {
            include(__DIR__ . "/template/" . $template . "/header.php");
            include(__DIR__ . "/template/" . $template . "/page.php");
            include(__DIR__ . "/template/" . $template . "/footer.php");
        }
        break;
}