<?php

class febspot
{
    // https://st1.febspot.com/remote_control.php?file=B64YTo0OntzOjQ6InRpbWUiO2k6MTYwNTI4NTQzOTtzOjU6ImxpbWl0IjtpOjA7czo0OiJmaWxlIjtzOjI2OiIvdmlkZW9zLzAvMTM4LzEzOF83MjBwLm1wNCI7czoyOiJjdiI7czozMjoiYTljZmRjMTRhNjM2MDRkOGMwNzFhN2U1NjA3NDM3OWEiO30%3D
    public $enable_proxies = false;

    public function media_info($url)
    {
        $web_page = url_get_contents($url, $this->enable_proxies);
        $video["title"] = get_string_between($web_page, '<title>', '</title>');
        $video["source"] = "febspot";
        $video["thumbnail"] = get_string_between($web_page, 'property="og:image" content="', '"');
        $video_url = get_string_between($web_page, 'property="og:video" content="', '"');
        if (!filter_var($video_url, FILTER_VALIDATE_URL)) {
            return false;
        }
        $video["links"][0]["url"] = $video_url;
        $video["links"][0]["type"] = "mp4";
        $video["links"][0]["size"] = get_file_size($video_url, $this->enable_proxies);
        $video["links"][0]["quality"] = "480p";
        $video["links"][0]["mute"] = false;
        /*
        $rnd = get_string_between($web_page, "rnd: '", "',");
        preg_match_all('/https:\/\/www.febspot.com\/get_file\/(.*?)\/(\d{3,8})(|_(\d{3,4}p)).mp4\//', $web_page, $matches);
        if (isset($matches[0]) == "" || isset($matches[4]) == "") {
            return false;
        }
        for ($i = 0; $i < count($matches[0]); $i++) {
            if (empty($matches[4][$i])) {
                $matches[4][$i] = "480p";
            }
            $video_url = $matches[0][$i] . "?rnd=" . $rnd;
            $video["links"][$i]["url"] = $video_url;
            $video["links"][$i]["type"] = "mp4";
            $video["links"][$i]["size"] = get_file_size($video_url, $this->enable_proxies);
            $video["links"][$i]["quality"] = $matches[4][$i];
            $video["links"][$i]["mute"] = false;
        }
        */
        return $video;
    }
}