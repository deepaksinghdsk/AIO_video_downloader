<?php

class gaana
{
    public $enable_proxies = false;

    public function media_info($url)
    {
        $song_name = "";
        $explode_url = explode("/", parse_url($url, PHP_URL_PATH));
        if (count($explode_url) >= 3) {
            if ($explode_url[1] == "song") {
                $song_name = $explode_url[2];
            }
        }
        if (empty($song_name)) {
            return false;
        }
        $song_data = $this->gaana_api($song_name);
        if (empty($song_data["tracks"][0] ?? "")) {
            return false;
        }
        $playlist_url = $this->decrypt_url($song_data["tracks"][0]["urls"]["high"]["message"]);
        if (!filter_var($playlist_url, FILTER_VALIDATE_URL)) {
            return false;
        }
        $playlist_url = url_get_contents($playlist_url, $this->enable_proxies);
        $playlist_url = explode("\n", $playlist_url ?? "");
        if (empty($playlist_url[2] ?? "")) {
            return false;
        }
        $playlist_url = $playlist_url[2];
        $stream_playlist = url_get_contents($playlist_url, $this->enable_proxies);
        preg_match_all('/https(.*)/', $stream_playlist, $stream_playlist);
        if (empty($stream_playlist[0])) {
            return false;
        } else {
            $stream_playlist = $stream_playlist[0];
        }
        $merged_file = __DIR__ . "/../storage/temp/gaana-" . $song_data["tracks"][0]["track_id"] . ".json";
        file_put_contents($merged_file, json_encode($stream_playlist));
        $track["title"] = $song_data["tracks"][0]["track_title"];
        $track["source"] = "gaana";
        $track["thumbnail"] = $song_data["tracks"][0]["artwork_web"];
        $track["duration"] = format_seconds($song_data["tracks"][0]["duration"]);
        $track["links"] = array();
        $website_url = json_decode(option("general_settings"), true)["url"];
        $chunk_size = get_file_size($stream_playlist[0], $this->enable_proxies, false);
        $file_size = $chunk_size * count($stream_playlist) * 666;
        array_push($track["links"], array(
            "url" => $website_url . "/system/storage/temp/gaana-" . $song_data["tracks"][0]["track_id"] . ".json",
            "type" => "mp3",
            "quality" => "256 kbps",
            "size" => format_size($file_size),
            "mute" => false
        ));
        return $track;
    }

    private function merge_parts($stream_playlist, $merged_file)
    {
        $merged = "";
        foreach ($stream_playlist as $stream_url) {
            $merged .= url_get_contents($stream_url, $this->enable_proxies);
        }
        file_put_contents($merged_file, $merged);
    }

    private function gaana_api($song_name)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://gaana.com/apiv2?seokey=$song_name&type=songdetails&isChrome=1",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_USERAGENT => "Mozilla/5.0 (Linux; Android 10;TXY567) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/8399.0.9993.96 Mobile Safari/599.36"
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

    private function decrypt_url($url)
    {
        $ciphering = "AES-128-CBC";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $decryption_iv = utf8_encode('asd!@#!@#@!12312');
        $decryption_key = utf8_encode('g@1n!(f1#r.0$)&%');
        return openssl_decrypt($url, $ciphering,
            $decryption_key, $options, $decryption_iv);
    }
}