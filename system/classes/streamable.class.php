<?php

class streamable
{
    public $enable_proxies = false;

    public function media_info($url)
    {
        $web_page = url_get_contents($url, $this->enable_proxies);
        $video_data = get_string_between($web_page, 'var videoObject =', ';');
        $video_data = json_decode($video_data, true);
        if (empty($video_data)) {
            return false;
        }
        $video["title"] = $video_data["title"];
        $video["source"] = "streamable";
        $video["thumbnail"] = $video_data["thumbnail_url"];
        $video["duration"] = format_seconds((int)ceil($video_data["duration"]));
        $video["links"] = array();
        foreach ($video_data["files"] as $key => $data) {
            $url = "https:" . $data["url"];
            array_push($video["links"], array(
                "url" => $url,
                "type" => pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION),
                "size" => get_file_size($url, $this->enable_proxies),
                "quality" => $data["height"] . "p",
                "mute" => false
            ));
        }
        usort($video["links"], 'sort_by_quality');
        return $video;
    }
}