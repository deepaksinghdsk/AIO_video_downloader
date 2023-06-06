<?php

class rumble
{
    public $enable_proxies = false;

    public function media_info($url)
    {
        $web_page = url_get_contents($url, $this->enable_proxies);
        $video_id = get_string_between($web_page, '"video":"', '"');
        if (empty($video_id)) {
            return false;
        }
        $video_info = $this->get_video_info($video_id);
        $video["title"] = $video_info["title"];
        $video["source"] = "rumble";
        $video["duration"] = format_seconds($video_info["duration"]);
        $video["thumbnail"] = $video_info["i"];
        $video["links"] = array();
        foreach ($video_info["ua"] as $quality => $info) {
            array_push($video["links"], [
                "url" => $info[0],
                "type" => "mp4",
                "size" => get_file_size($info[0], $this->enable_proxies),
                "quality" => $quality . "p",
                "mute" => false
            ]);
        }
        usort($video["links"], 'sort_by_quality');
        return $video;
    }

    private function get_video_info($video_id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://rumble.com/embedJS/u3/?request=video&v=" . $video_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_USERAGENT => _REQUEST_USER_AGENT
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }
}