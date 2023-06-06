<?php

class bandcamp
{
    public $enable_proxies = false;

    function media_info($url)
    {
        $web_page = url_get_contents($url, $this->enable_proxies);
        $embed_url = get_string_between($web_page, 'property="twitter:player" content="', '"');
        if (empty($embed_url)) {
            return false;
        }
        $video["title"] = get_string_between($web_page, '<title>', '</title>');
        $video["source"] = "bandcamp";
        $video["thumbnail"] = get_string_between($web_page, 'property="og:image" content="', '"');
        $video["duration"] = format_seconds(get_string_between($web_page, 'itemprop="duration" content="', '"'));
        $embed_page = url_get_contents($embed_url, $this->enable_proxies);
        $player_data = get_string_between($embed_page, 'var playerdata =', ';');
        $player_data = json_decode($player_data, true);
        $audio_url = $player_data["tracks"][0]["file"]["mp3-128"];
        if (empty($audio_url)) {
            return false;
        }
        $video["links"][0]["url"] = $audio_url;
        $video["links"][0]["type"] = "mp3";
        $video["links"][0]["size"] = get_file_size($audio_url, $this->enable_proxies);
        $video["links"][0]["quality"] = "128kbps";
        $video["links"][0]["mute"] = "no";
        return $video;
    }
}