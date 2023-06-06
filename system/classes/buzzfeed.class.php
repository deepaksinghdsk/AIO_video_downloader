<?php

class buzzfeed
{
    public $enable_proxies = false;

    function media_info($url)
    {
        $web_page = url_get_contents($url, $this->enable_proxies);
        preg_match_all('@__NEXT_DATA__ =(.*?);@si', $web_page, $matches);
        $data = json_decode($matches[1][0], true)["props"]["pageProps"]["video"];
        if (!empty($data)) {
            $video["title"] = $data["title"];
            $video["source"] = "buzzfeed";
            $video["thumbnail"] = $data["thumbnail_url"];
            $video["links"][0]["url"] = $data["url"];
            $video["links"][0]["type"] = "mp4";
            $video["links"][0]["size"] = get_file_size($video["links"][0]["url"], $this->enable_proxies);
            $video["links"][0]["quality"] = "1080p";
            $video["links"][0]["mute"] = "no";
        }
        preg_match_all('/"contentUrl": "https:\/\/www\.youtube\.com\/watch\?v=(.*?)"/', $web_page, $matches);
        if (!empty($matches[1][0])) {
            require_once __DIR__ . '/youtube.class.php';
            $yt = new youtube();
            return $yt->media_info("https://www.youtube.com/watch?v=" . $matches[1][0]);
        }
        return $video;
    }
}