<?php

class break_dl
{
    public $enable_proxies = false;

    function format_title($title)
    {
        $title = str_replace(".mp4", "", $title);
        $title = str_replace("_", " ", $title);
        return $title;
    }

    function media_info($url)
    {
        $page_source = url_get_contents($url, $this->enable_proxies);
        preg_match('/<source src="(.*?)" type="video\/youtube"\/>/', $page_source, $youtube_url);
        preg_match_all('/<iframe src="(.*?)"/', $page_source, $embed_url);
        if (!empty($youtube_url[1])) {
            $youtube_url = $youtube_url[1];
            include_once __DIR__ . "/youtube.class.php";
            $yt = new youtube();
            return $yt->media_info($youtube_url);
        } elseif (!empty($embed_url[1][0])) {
            $embed_url = $embed_url[1][0];
            $embed_source = url_get_contents($embed_url, $this->enable_proxies);
            $video_url = get_string_between($embed_source, '_mvp.file = "', '";');
            $thumbnail_url = get_string_between($embed_source, '_mvp.image = "', '";');
            $video_title = get_string_between($embed_source, '<title>', '</title>');
            if ($video_url != "" && $thumbnail_url != "" && $video_title != "") {
                $video["title"] = $this->format_title($video_title);
                $video["source"] = "break";
                $video["thumbnail"] = $thumbnail_url;
                $video["links"][0]["url"] = $video_url;
                $video["links"][0]["type"] = "mp4";
                $video["links"][0]["size"] = get_file_size($video["links"][0]["url"], $this->enable_proxies);
                $video["links"][0]["quality"] = "SD";
                $video["links"][0]["mute"] = "no";
                return $video;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}