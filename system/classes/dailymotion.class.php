<?php

class dailymotion
{
    public $enable_proxies = false;
    public $enable_api = false;

    function find_video_id($url)
    {
        $domain = str_ireplace("www.", "", parse_url($url, PHP_URL_HOST));
        switch (true) {
            case($domain == "dai.ly"):
                $video_id = str_replace('https://dai.ly/', "", $url);
                $video_id = str_replace('/', "", $video_id);
                return $video_id;
                break;
            case($domain == "dailymotion.com"):
                $url_parts = parse_url($url);
                $path_arr = explode("/", rtrim($url_parts['path'], "/"));
                $video_id = $path_arr[2];
                return $video_id;
                break;
            default:
                return "";
                break;
        }
    }

    function media_info($url)
    {
        if ($this->enable_api) {
            return $this->media_info_api($url);
        } else {
            $video_id = $this->find_video_id($url);
            $web_page = url_get_contents("https://www.dailymotion.com/player/metadata/video/" . $video_id, $this->enable_proxies);
            if (!empty($web_page)) {
                $data = json_decode($web_page, true);
                $video["title"] = $data["title"];
                $video["source"] = "dailymotion";
                $video["thumbnail"] = end($data["posters"]);
                $video["duration"] = format_seconds($data["duration"]);
                $streams_m3u8 = url_get_contents($data["qualities"]["auto"][0]["url"], $this->enable_proxies);
                preg_match_all('/#EXT-X-STREAM-INF:(.*)/', $streams_m3u8, $streams_raw);
                $streams_raw = $streams_raw[1];
                $streams = array();
                foreach ($streams_raw as $stream) {
                    $quality = get_string_between($stream, 'NAME="', '"');
                    if (!isset($streams[$quality])) {
                        $streams[$quality]["quality"] = $quality;
                        $streams[$quality]["url"] = get_string_between($stream, 'PROGRESSIVE-URI="', '"');
                    }
                }
                $i = 0;
                foreach ($streams as $stream) {
                    $video["links"][$i]["url"] = $stream["url"];
                    $video["links"][$i]["type"] = "mp4";
                    $video["links"][$i]["size"] = get_file_size($video["links"][$i]["url"], $this->enable_proxies);
                    $video["links"][$i]["quality"] = $stream["quality"] . "p";
                    $video["links"][$i]["mute"] = false;
                    $i++;
                }
                usort($video["links"], 'sort_by_quality');
                return $video;
            } else {
                return false;
            }
        }
    }

    function media_info_api($url)
    {
        $config = json_decode(option(), true);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://dailymotion.aiovideodl.ml/system/action.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "url=" . urlencode($url) . "&purchase_code=" . json_decode(option(), true)["purchase_code"],
            CURLOPT_HTTPHEADER => array(
                "x-requested-with: PHP-cURL",
                "user-agent: " . $config["fingerprint"],
                "content-type: application/x-www-form-urlencoded; charset=UTF-8"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);
        for ($i = 0; $i < count($response["links"]); $i++) {
            $data = array("url" => $response["links"][$i]["url"], "title" => $response["title"], "type" => $response["links"][$i]["type"], "source" => "dailymotion");
            $response["links"][$i]["url"] = "https://dailymotion.aiovideodl.ml/dl.php?" . http_build_query($data);
        }
        return $response;
    }
}