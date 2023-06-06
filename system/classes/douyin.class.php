<?php

class douyin
{
    public $enable_proxies = false;

    public function find_video_id($url)
    {
        $url = unshorten($url, $this->enable_proxies);
        $url = strtok($url, '?');
        $last_char = substr($url, -1);
        if ($last_char == "/") {
            $url = substr($url, 0, -1);
        }
        $arr = explode("/", $url);
        return end($arr);
    }

    public function media_info($url)
    {
        $video_id = $this->find_video_id($url);
        if (empty($video_id)) {
            return false;
        }
        $video_info = $this->get_video_info($video_id);
        if (empty($video_info)) {
            return false;
        }
        $video["title"] = $video_info["item_list"][0]["desc"];
        $video["source"] = "douyin";
        $video["thumbnail"] = $video_info["item_list"][0]["video"]["cover"]["url_list"][0];
        $video["duration"] = format_seconds($video_info["item_list"][0]["video"]["duration"] / 1000);
        $video_url = $this->get_video_url($video_info["item_list"][0]["video"]["play_addr"]["url_list"][0]);
        if(!empty($video_url)) {
            $video["links"][0]["url"] = $video_url;
            $video["links"][0]["type"] = "mp4";
            $video["links"][0]["size"] = get_file_size($video_url, $this->enable_proxies);
            $video["links"][0]["quality"] = $video_info["item_list"][0]["video"]["ratio"];
            $video["links"][0]["mute"] = false;
        }
        $music_url = $video_info["item_list"][0]["music"]["play_url"]["uri"];
        if(!empty($music_url) && !empty($video["links"][0])) {
            $video["links"][1]["url"] = $music_url;
            $video["links"][1]["type"] = "mp3";
            $video["links"][1]["size"] = get_file_size($music_url, $this->enable_proxies);
            $video["links"][1]["quality"] = "128kbps";
            $video["links"][1]["mute"] = false;
        }
        return $video;
    }

    function get_video_url($player_url)
    {
        $headers = get_headers($player_url, 1);
        return $headers["location"];
    }

    function get_video_info($video_id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.iesdouyin.com/web/api/v2/aweme/iteminfo/?item_ids=" . $video_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }
}