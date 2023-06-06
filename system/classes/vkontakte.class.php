<?php

class vk
{
    public $enable_proxies = false;

    function url_get_contents($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_USERAGENT => _REQUEST_USER_AGENT
        ));
        $response = curl_exec($curl);
        $error = curl_error($curl);
        if (!empty($error)) {
            die($error);
        }
        curl_close($curl);
        return $response;
    }

    public function get_video_data($video_id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://vk.com/al_video.php?act=show",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "act=show&al=1&autoplay=0&list=&module=videocat&video=" . $video_id,
            CURLOPT_HTTPHEADER => array(
                "user-agent: " . _REQUEST_USER_AGENT,
                "x-requested-with: XMLHttpRequest",
                "referer: https://vk.com",
                "Content-Type: application/x-www-form-urlencoded"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    function media_info($url)
    {
        $url = str_replace("m.vk.com", "vk.com", $url);
        $web_page = $this->url_get_contents($url, $this->enable_proxies);
        $query = html_entity_decode(get_string_between($web_page, 'https://vk.com/video_ext.php?', '"'));
        /*
        if (empty($query)) {
            $web_page = url_get_contents($url, $this->enable_proxies);
        }
        $query = html_entity_decode(get_string_between($web_page, 'https://vk.com/video_ext.php?', '"'));
        */
        parse_str($query, $video_ids);
        $video_id = get_string_between($web_page, '"video_id":"', '",');
        if (empty($video_id) && isset($video_ids["oid"]) != "" && isset($video_ids["id"]) != "") {
            $video_id = $video_ids["oid"] . "_" . $video_ids["id"];
        }
        //$video_title = sanitize_filename(get_string_between($web_page, '"md_title":"', '"'), "");
        $video["title"] = "VK Video";
        $video["source"] = "vkontakte";
        $video["thumbnail"] = get_string_between($web_page, 'data-id="'.$video_id.'" data-add-hash="" data-thumb="', '"');
        /*
        if (!filter_var($video["thumbnail"], FILTER_VALIDATE_URL)) {
            $video["thumbnail"] = str_replace("\\", "", get_string_between($video_data, "background-image:url(", ");"));
        }
        */
        //$duration = get_string_between($video_data, '"duration":', ',');
        if (!empty($duration)) {
            $video["duration"] = format_seconds($duration);
        }
        $video["links"] = array();
        preg_match_all('/"url(\d{3})":"(.*?)"/', $web_page, $output);
        if (empty($output[1]) && empty($output[2])) {
            for ($i = 0; $i < count($output[1]); $i++) {
                $video_url = str_replace("\\", "", $output[2][$i]);
                array_push($video["links"], array(
                    "url" => $video_url,
                    "type" => "mp4",
                    "size" => get_file_size($video_url, $this->enable_proxies),
                    "quality" => $output[1][$i] . "p",
                    "mute" => false
                ));
            }
        } else {
            $video_data = $this->get_video_data($video_id);
            preg_match_all('/"cache(\d{3})":"(.*?)"/', $video_data, $matches);
            if (!empty($matches[1]) && !empty($matches[2])) {
                for ($i = 0; $i < count($matches[1]); $i++) {
                    $video_url = str_replace("\\", "", $matches[2][$i]);
                    array_push($video["links"], array(
                        "url" => $video_url,
                        "type" => "mp4",
                        "size" => get_file_size($video_url, $this->enable_proxies),
                        "quality" => $matches[1][$i] . "p",
                        "mute" => false
                    ));
                }
            } else if (!empty(str_replace("\\", "", get_string_between($video_data, '"postlive_mp4":"', '"')))) {
                $video_url = str_replace("\\", "", get_string_between($video_data, '"postlive_mp4":"', '"'));
                if (!empty($video_url)) {
                    array_push($video["links"], array(
                        "url" => $video_url,
                        "type" => "mp4",
                        "size" => get_file_size($video_url, $this->enable_proxies),
                        "quality" => "hd",
                        "mute" => false
                    ));
                }
            } else if (!empty(get_string_between($web_page, '/><source src="', '" type="video/mp4" />'))) {
                $video["title"] = "VK Video";
                $video["source"] = "vkontakte";
                $video["thumbnail"] = get_string_between($web_page, 'poster="', '"');
                $video["links"] = array();
                $video_url = get_string_between($web_page, '/><source src="', '" type="video/mp4" />');
                if (!empty($video_url)) {
                    array_push($video["links"], array(
                        "url" => $video_url,
                        "type" => "mp4",
                        "size" => get_file_size($video_url, $this->enable_proxies),
                        "quality" => "hd",
                        "mute" => false
                    ));
                    return $video;
                }
            }
        }
        $video["id"] = $video_id;
        return $video;
    }
}