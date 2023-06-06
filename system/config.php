<?php
const _REQUEST_USER_AGENT = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36";
require_once __DIR__ . "/db.php";
$config = json_decode(database::find("SELECT * FROM options WHERE option_name='general_settings' LIMIT 1")[0]["option_value"], true);

//$website_domain = str_ireplace("www.", "", parse_url($config["url"], PHP_URL_HOST));
//$config["fingerprint"] = sha1($website_domain . $config["purchase_code"]);

$template_config = json_decode(database::find("SELECT * FROM options WHERE option_name='theme.general' LIMIT 1")[0]["option_value"], true);
session_start();
if (isset($_SESSION["current_language"]) != "") {
    require_once(__DIR__ . "/../language/" . $_SESSION["current_language"] . ".php");
} else {
    $_SESSION["current_language"] = $config["language"];
    require_once(__DIR__ . "/../language/" . $config["language"] . ".php");
}
include(__DIR__ . "/functions.php");
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = generate_csrf_token();
}