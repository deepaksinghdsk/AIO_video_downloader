<?php if (isset($_SESSION["logged"]) === true) { ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">Install YouTube Plugin</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        if (file_exists(__DIR__ . "/../../system/classes/youtube.class.php")) {
                            echo '<p class="alert alert-info">YouTube plugin already installed.</p>';
                        } else {
                            $config = json_decode(option(), true);
                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                                CURLOPT_URL => "https://aiovideodl.ml/yt-plugin/",
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => "",
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => "POST",
                                CURLOPT_POSTFIELDS => "code=" . $config["purchase_code"] . "&url=" . urlencode($config["url"]),
                                CURLOPT_HTTPHEADER => array(
                                    "Cache-Control: no-cache",
                                    "Content-Type: application/x-www-form-urlencoded",
                                    "NicheOffice-Token: 5e8db209-f926-4b50-a0d9-a156839140d6"
                                ),
                            ));
                            $response = curl_exec($curl);
                            $error = curl_error($curl);
                            curl_close($curl);
                            if ($error) {
                                echo '<p class="alert alert-warning">cURL Error #: ' . $error . '</p>';
                            } else {
                                $file = __DIR__ . "/../../system/classes/youtube.class.php.zip";
                                $plugin_file = fopen($file, "w") or die('<p class="alert alert-warning">Unable to open file! Check file permissions.</p>');
                                fwrite($plugin_file, $response);
                                fclose($plugin_file);
                                $path = pathinfo(realpath($file), PATHINFO_DIRNAME);
                                $zip = new ZipArchive;
                                $res = $zip->open($file);
                                if ($res === TRUE) {
                                    $zip->extractTo($path);
                                    $zip->close();
                                    unlink(realpath($file));
                                    echo '<p class="alert alert-success">YouTube plugin installed successfully.</p>';
                                } else {
                                    echo '<p class="alert alert-warning">Unable to open file! Check file permissions.</p>';
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    http_response_code(403);
} ?>