<?php

class periscope
{
    public $enable_proxies = false;

    public function media_info($url)
    {
        if (isset(explode("/", $url)[4]) != "") {
            $broadcast_id = explode("/", $url)[4];
        } else {
            return false;
        }
        $data = url_get_contents("https://proxsee.pscp.tv/api/v2/accessVideoPublic?broadcast_id=$broadcast_id&replay_redirect=false", $this->enable_proxies);
        $data = json_decode($data, true);
        if ($data["broadcast"]["state"] != "ENDED") {
            return false;
        }
        $playlist = url_get_contents($data["replay_url"], $this->enable_proxies);
        $parsed_url = parse_url($data["replay_url"]);
        $playlist_host = $parsed_url["host"];
        $playlist_path = $parsed_url["path"];
        preg_match_all('/(.*).m3u8/', $playlist, $matches);
        if (isset($matches[0][0]) == "") {
            return false;
        }
        $stream_list = url_get_contents("https://" . $playlist_host . $matches[0][0], $this->enable_proxies);
        preg_match_all('/(.*).ts/', $stream_list, $matches);
        if (isset($matches[0][0]) == "") {
            return false;
        }
        $stream_playlist = array();
        foreach ($matches[0] as $file) {
            array_push($stream_playlist, preg_replace('/(\w{3,50}).m3u8/', $file, "https://" . $playlist_host . $playlist_path));
        }
        $website_url = json_decode(option("general_settings"), true)["url"];
        $file_id = $broadcast_id;
        $merged_file = __DIR__ . "/../storage/temp/periscope-" . $file_id . ".json";
        $thumbnail_file = __DIR__ . "/../storage/temp/periscope-" . $file_id . ".jpg";
        file_put_contents($merged_file, json_encode($stream_playlist));
        file_put_contents($thumbnail_file, url_get_contents($data["broadcast"]["image_url"], $this->enable_proxies));
        $video["title"] = $data["broadcast"]["status"];
        $video["source"] = "periscope";
        $video["thumbnail"] = $website_url . "/system/storage/temp/periscope-" . $file_id . ".jpg";
        $video["links"] = array();
        $chunk_size = get_file_size($stream_playlist[0], $this->enable_proxies, false);
        $file_size = $chunk_size * count($stream_playlist) * 3333;
        array_push($video["links"], array(
            "url" => $website_url . "/system/storage/temp/periscope-" . $file_id . ".json",
            "type" => "mp4",
            "quality" => "HD",
            "size" => format_size($file_size),
            "mute" => false
        ));
        return $video;
    }

    private function merge_parts($stream_playlist, $merged_file)
    {
        $merged = "";
        $context_options = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            )
        );
        foreach ($stream_playlist as $stream_url) {
            //$merged .= url_get_contents($stream_url, $this->enable_proxies);
            //$merged .= copyfile_chunked();
            file_put_contents($merged_file, url_get_contents($stream_url, $this->enable_proxies), FILE_APPEND);
        }
        //file_put_contents($merged_file, $merged);
    }
}