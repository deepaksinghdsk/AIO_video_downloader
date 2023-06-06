<?php

class imgur
{
    public $enable_proxies = false;

    function media_info($url)
    {
        $web_page = url_get_contents($url, $this->enable_proxies);
        $video["title"] = get_string_between($web_page, '<title>', '</title>');
        $video["source"] = "imgur";
        $video["thumbnail"] = get_string_between($web_page, '<meta name="twitter:image" data-react-helmet="true" content="', '">');
        $video["links"][0]["url"] = get_string_between($web_page, '<meta property="og:video:secure_url" data-react-helmet="true" content="', '">');
        $video["links"][0]["type"] = "mp4";
        $video["links"][0]["size"] = get_file_size($video["links"][0]["url"], $this->enable_proxies);
        $video["links"][0]["quality"] = "hd";
        $video["links"][0]["mute"] = false;
        return $video;
    }
}