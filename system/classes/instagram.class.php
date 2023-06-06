<?php

class instagram
{
    public $enable_proxies = false;
    public $url;
    public static $COOKIE_FILE = __DIR__ . "/../storage/ig-cookie.txt";
    public static $USER_AGENT = _REQUEST_USER_AGENT;
    private $post_page;

    function media_info($url)
    {
        /*
        if (strpos($url, "https://www.instagram.com/p") === 0 || strpos($url, "https://instagram.com/p") === 0) {
            $url = str_replace($url, "http", "https");
        }
        */
        $this->post_page = $this->url_get_contents($url, $this->enable_proxies);
        $media_info = $this->media_data($this->post_page);
        $video["title"] = $this->get_title($this->post_page);
        $video["source"] = "instagram";
        $video["thumbnail"] = $this->get_thumbnail($this->post_page);
        $i = 0;
        foreach ($media_info["links"] as $link) {
            switch ($link["type"]) {
                case "video":
                    $video["links"][$i]["url"] = $link["url"];
                    $video["links"][$i]["type"] = "mp4";
                    $video["links"][$i]["size"] = get_file_size($video["links"][$i]["url"], $enable_proxies = false);
                    $video["links"][$i]["quality"] = "HD";
                    $video["links"][$i]["mute"] = "no";
                    $i++;
                    break;
                case "image":
                    $video["links"][$i]["url"] = $link["url"];
                    $video["links"][$i]["type"] = "jpg";
                    $video["links"][$i]["size"] = get_file_size($video["links"][$i]["url"], $enable_proxies = false);
                    $video["links"][$i]["quality"] = "HD";
                    $video["links"][$i]["mute"] = "yes";
                    $i++;
                    break;
                default:
                    break;
            }
        }
        return $video;
    }

    function media_info_beta($url)
    {
        $this->post_page = $this->url_get_contents($url, $this->enable_proxies);
        $video["title"] = $this->get_title($this->post_page);
        $video["source"] = "instagram";
        //$video["thumbnail"] = $this->get_thumbnail($this->post_page);
        $video["thumbnail"] = get_string_between($this->post_page, '"display_url":"', '"');
        $video["thumbnail"] = str_replace("\u0026", "&", $video["thumbnail"]);
        $video["links"][0]["url"] = $this->getVideoUrl();
        $video["links"][0]["type"] = "mp4";
        $video["links"][0]["size"] = get_file_size($video["links"]["0"]["url"], $enable_proxies = false);
        $video["links"][0]["quality"] = "HD";
        $video["links"][0]["mute"] = "no";
        return $video;
    }

    function getPostShortcode($url)
    {
        if (substr($url, -1) != '/') {
            $url .= '/';
        }
        preg_match('/\/(p|tv)\/(.*?)\//', $url, $output);
        return ($output['2'] ?? '');
    }

    function getVideoUrl($postShortcode = "")
    {
        //$pageContent = $this->url_get_contents('https://www.instagram.com/p/' . $postShortcode);
        preg_match_all('/"video_url":"(.*?)",/', $this->post_page, $out);
        if (!empty($out[1][0])) {
            return str_replace('\u0026', '&', $out[1][0]);
        } else {
            return null;
        }
    }

    function url_get_contents($url, $enable_proxies = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, self::$USER_AGENT);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        if (file_exists(self::$COOKIE_FILE)) {
            curl_setopt($ch, CURLOPT_COOKIEFILE, self::$COOKIE_FILE);
            //curl_setopt($ch, CURLOPT_COOKIEJAR, self::$COOKIE_FILE);
        }
        if ($enable_proxies) {
            if (!empty($_SESSION["proxy"] ?? null)) {
                $proxy = $_SESSION["proxy"];
            } else {
                $proxy = get_proxy();
                $_SESSION["proxy"] = $proxy;
            }
            curl_setopt($ch, CURLOPT_PROXY, $proxy['ip'] . ":" . $proxy['port']);
            if (!empty($proxy['username']) && !empty($proxy['password'])) {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy['username'] . ":" . $proxy['password']);
            }
            $chunkSize = 1000000;
            curl_setopt($ch, CURLOPT_TIMEOUT, (int)ceil(3 * (round($chunkSize / 1048576, 2) / (1 / 8))));
        }
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    function media_data($post_page)
    {
        preg_match_all("/window.__additionalDataLoaded.'.{5,}',(.*).;/", $post_page, $matches);
        if (!$matches) {
            return false;
        } else {
            $json = $matches[1][0];
            $data = json_decode($json, true);
            if ($data['graphql']['shortcode_media']['__typename'] == "GraphImage") {
                $imagesdata = $data['graphql']['shortcode_media']['display_resources'];
                $length = count($imagesdata);
                $media_info['links'][0]['type'] = 'image';
                $media_info['links'][0]['url'] = $imagesdata[$length - 1]['src'];
                $media_info['links'][0]['status'] = 'success';
            } else {
                if ($data['graphql']['shortcode_media']['__typename'] == "GraphSidecar") {
                    $counter = 0;
                    $multipledata = $data['graphql']['shortcode_media']['edge_sidecar_to_children']['edges'];
                    foreach ($multipledata as &$media) {
                        if ($media['node']['is_video'] == "true") {
                            $media_info['links'][$counter]["url"] = $media['node']['video_url'];
                            $media_info['links'][$counter]["type"] = 'video';
                        } else {
                            $length = count($media['node']['display_resources']);
                            $media_info['links'][$counter]["url"] = $media['node']['display_resources'][$length - 1]['src'];
                            $media_info['links'][$counter]["type"] = 'image';
                        }
                        $counter++;
                        $media_info['type'] = 'media';
                    }
                    $media_info['status'] = 'success';
                } else {
                    if ($data['graphql']['shortcode_media']['__typename'] == "GraphVideo") {
                        $videolink = $data['graphql']['shortcode_media']['video_url'];
                        $media_info['links'][0]['type'] = 'video';
                        $media_info['links'][0]['url'] = $videolink;
                        $media_info['links'][0]['status'] = 'success';
                    } else {
                        $media_info['links']['status'] = 'fail';
                    }
                }
            }
            $owner = $data['graphql']['shortcode_media']['owner'];
            $media_info['username'] = $owner['username'];
            $media_info['full_name'] = $owner['full_name'];
            $media_info['profile_pic_url'] = $owner['profile_pic_url'];
            return $media_info;
        }
    }

    function get_thumbnail($post_page)
    {
        preg_match_all("/window.__additionalDataLoaded.'.{5,}',(.*).;/", $post_page, $matches);
        if (!$matches) {
            return false;
        }
        $json = $matches[1][0];
        $data = json_decode($json, true)["graphql"];
        return $data["shortcode_media"]['display_resources'][0]["src"];
    }

    function get_title($curl_content)
    {
        if (preg_match_all('@<title>(.*?)</title>@si', $curl_content, $match)) {
            return $match[1][0];
        }
    }
}