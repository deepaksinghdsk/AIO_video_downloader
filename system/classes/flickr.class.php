<?php

class flickr
{
    public $enable_proxies = false;

    function media_info($url)
    {
        $page_source = url_get_contents($url, $this->enable_proxies);
        $secret_key = get_string_between($page_source, '"secret":"', '"');
        $site_key = get_string_between($page_source, '"site_key":"', '"');
        $media_id = get_string_between($page_source, '"photoId":"', '"');
        $api_url = "https://api.flickr.com/services/rest?photo_id=$media_id&secret=$secret_key&method=flickr.video.getStreamInfo&api_key=$site_key&format=json&nojsoncallback=1";
        $video["title"] = get_string_between($page_source, '<title>', '</title>');
        $video["source"] = "flickr";
        $video["thumbnail"] = get_string_between($page_source, '<meta property="og:image" content="', '"/>');
        if ($media_id != "" && $site_key != "" && $secret_key != "") {
            $streams = url_get_contents($api_url, $this->enable_proxies);
            $streams = json_decode($streams, true)["streams"]["stream"];
            for ($i = 0; $i < count($streams); $i++) {
                $file_size = get_file_size($streams[$i]["_content"], $this->enable_proxies);
                if (!empty($file_size)) {
                    $video["links"][$i]["url"] = $streams[$i]["_content"];
                    $video["links"][$i]["type"] = "mp4";
                    $video["links"][$i]["quality"] = (string)$streams[$i]["type"];
                    $video["links"][$i]["size"] = $file_size;
                    $video["links"][$i]["mute"] = "no";
                    $i++;
                }
            }
            usort($video["links"], 'sort_by_quality');
            return $video;
        } else {
            return false;
        }
    }
}