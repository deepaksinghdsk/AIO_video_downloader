<?php

class akillitv
{
    public $enable_proxies = false;

    public function media_info($url)
    {
        $web_page = url_get_contents($url, $this->enable_proxies);
        $video["title"] = get_string_between($web_page, '<title>', '</title>');
        $video["source"] = "akillitv";
        $video["thumbnail"] = $this->clean_url(get_string_between($web_page, 'property="og:image" content="', '"'));
        preg_match_all('/<source src="(.*?)" type="video\/mp4" data-quality="(.*?)"/', $web_page, $matches);
        if (!isset($matches[1]) || !isset($matches[2])) {
            return false;
        }
        for ($i = 0; $i < count($matches[1]); $i++) {
            $video_url = $this->clean_url($matches[1][$i]);
            $video["links"][$i]["url"] = $video_url;
            $video["links"][$i]["type"] = "mp4";
            $video["links"][$i]["size"] = get_file_size($video_url, $this->enable_proxies);
            $video["links"][$i]["quality"] = $matches[2][$i];
            $video["links"][$i]["mute"] = false;
        }
        $video["links"] = array_reverse($video["links"]);
        return $video;
    }

    private function clean_url($url)
    {
        return str_replace("////", "https://", $url);
    }
}