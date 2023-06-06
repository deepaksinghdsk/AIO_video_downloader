<?php
require_once("config.php");
$website_domain = str_ireplace("www.", "", parse_url($config["url"], PHP_URL_HOST));
if (empty(option("api_key.recaptcha_public"))) {
    $stmt = !empty($_POST["url"]) && hash_equals($_SESSION['token'], $_POST['token']) && hash_equals($config["fingerprint"], create_fingerprint($website_domain, $config["purchase_code"]));
} else {
    $stmt = !empty($_POST["url"]) && verify_captcha($_POST['token'], get_client_ip()) && hash_equals($config["fingerprint"], create_fingerprint($website_domain, $config["purchase_code"]));
}
if ($stmt) {
    $host = parse_url($_POST["url"], PHP_URL_HOST);
    $domain = str_ireplace("www.", "", $host);
    $main_domain = get_main_domain($host);
    $_SESSION['video'][$_SESSION["token"]] = $_POST["url"];
    $_SESSION['ip'][$_SESSION["token"]] = get_client_ip();
    switch (true) {
        case($domain == "instagram.com"):
            include(__DIR__ . "/classes/instagram.class.php");
            $download = new instagram();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "youtube.com" || $domain == "m.youtube.com" || $domain == "youtu.be"):
            include(__DIR__ . "/classes/youtube.class.php");
            $download = new youtube();
            $download->m4a_mp3 = $config["m4a_mp3"];
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "facebook.com" || $domain == "m.facebook.com" || $domain == "web.facebook.com" || $domain == "fb.watch" || $main_domain == "facebook.com"):
            include(__DIR__ . "/classes/facebook.class.php");
            $download = new facebook();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "twitter.com" || $domain == "mobile.twitter.com"):
            include(__DIR__ . "/classes/twitter.class.php");
            $download = new twitter();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "dailymotion.com" || $domain == "dai.ly"):
            include(__DIR__ . "/classes/dailymotion.class.php");
            $download = new dailymotion();
            return_json($download->media_info($_POST["url"]));
            break;
        case ($domain == "vimeo.com"):
            include(__DIR__ . "/classes/vimeo.class.php");
            $download = new vimeo();
            return_json($download->media_info($_POST["url"]));
            break;
        case ($main_domain == "tumblr.com"):
            include(__DIR__ . "/classes/tumblr.class.php");
            $download = new tumblr();
            return_json($download->media_info($_POST["url"]));
            break;
        case ($domain == "pin.it" || explode('.', $main_domain)[0] == "pinterest"):
            include(__DIR__ . "/classes/pinterest.class.php");
            $download = new pinterest();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "imgur.com" || $domain == "0imgur.com"):
            include(__DIR__ . "/classes/imgur.class.php");
            $download = new imgur();
            return_json($download->media_info($_POST["url"]));
            break;
        case ($domain == "liveleak.com"):
            include(__DIR__ . "/classes/liveleak.class.php");
            $download = new liveleak();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "ted.com"):
            include(__DIR__ . "/classes/ted.class.php");
            $download = new ted();
            return_json($download->media_info($_POST["url"]));
            break;
        case($main_domain == "mashable.com"):
            include(__DIR__ . "/classes/mashable.class.php");
            $download = new mashable();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "vk.com" || $domain == "m.vk.com"):
            include(__DIR__ . "/classes/vkontakte.class.php");
            $download = new vk();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "9gag.com" || $domain == "m.9gag.com"):
            include(__DIR__ . "/classes/ninegag.class.php");
            $download = new ninegag();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "break.com"):
            include(__DIR__ . "/classes/break_dl.class.php");
            $download = new break_dl();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "soundcloud.com" || $domain == "m.soundcloud.com" || $domain == "soundcloud.app.goo.gl"):
            include(__DIR__ . "/classes/soundcloud.class.php");
            $download = new soundcloud();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "flickr.com" || $domain == "flic.kr"):
            include(__DIR__ . "/classes/flickr.class.php");
            $download = new flickr();
            return_json($download->media_info($_POST["url"]));
            break;
        case($main_domain == "bandcamp.com"):
            include(__DIR__ . "/classes/bandcamp.class.php");
            $download = new bandcamp();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "espn.com" || $domain == "espn.in" || $domain == "africa.espn.com"):
            include(__DIR__ . "/classes/espn.class.php");
            $download = new espn();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "imdb.com" || $domain == "m.imdb.com"):
            include(__DIR__ . "/classes/imdb.class.php");
            $download = new imdb();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "izlesene.com"):
            include(__DIR__ . "/classes/izlesene.class.php");
            $download = new izlesene();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "buzzfeed.com" || $domain == "www.buzzfeed.com"):
            include(__DIR__ . "/classes/buzzfeed.class.php");
            $download = new buzzfeed();
            return_json($download->media_info($_POST["url"]));
            break;
        case($main_domain == "tiktok.com"):
            include(__DIR__ . "/classes/tiktok.class.php");
            $download = new tiktok();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "ok.ru"):
            include(__DIR__ . "/classes/odnoklassniki.class.php");
            $download = new odnoklassniki();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "likee.com" || $domain == "l.likee.video" || $domain == "likee.video" || $domain  == "like.video"):
            include(__DIR__ . "/classes/likee.class.php");
            $download = new likee();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "www.twitch.tv" || $domain == "twitch.tv" || $domain == "m.twitch.tv" || $domain == "clips.twitch.tv"):
            include(__DIR__ . "/classes/twitch.class.php");
            $download = new twitch();
            return_json($download->media_info($_POST["url"]));
            break;
        case((explode('.', $domain)[1] ?? "") == "blogspot"):
            include(__DIR__ . "/classes/blogger.class.php");
            $download = new blogger();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "reddit.com"):
            include(__DIR__ . "/classes/reddit.class.php");
            $download = new reddit();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "v.douyin.com"):
            include(__DIR__ . "/classes/douyin.class.php");
            $download = new douyin();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "kwai.com" || $domain == "kw.ai"):
            include(__DIR__ . "/classes/kwai.class.php");
            $download = new kwai();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "linkedin.com"):
            include(__DIR__ . "/classes/linkedin.class.php");
            $download = new linkedin();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "streamable.com"):
            include(__DIR__ . "/classes/streamable.class.php");
            $download = new streamable();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "bitchute.com"):
            include(__DIR__ . "/classes/bitchute.class.php");
            $download = new bitchute();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "akilli.tv"):
            include(__DIR__ . "/classes/akillitv.class.php");
            $download = new akillitv();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "gaana.com"):
            include(__DIR__ . "/classes/gaana.class.php");
            $download = new gaana();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "bilibili.com"):
            include(__DIR__ . "/classes/bilibili.class.php");
            $download = new bilibili();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "febspot.com"):
            include(__DIR__ . "/classes/febspot.class.php");
            $download = new febspot();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "rumble.com"):
            include(__DIR__ . "/classes/rumble.class.php");
            $download = new rumble();
            return_json($download->media_info($_POST["url"]));
            break;
        case($domain == "pscp.tv"):
            include(__DIR__ . "/classes/periscope.class.php");
            $download = new periscope();
            return_json($download->media_info($_POST["url"]));
            break;
        default:
            echo "error";
            die();
            break;
    }
} else {
    echo "error";
    die();
}