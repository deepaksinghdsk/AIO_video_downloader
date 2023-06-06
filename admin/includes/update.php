<?php
if (isset($_SESSION["logged"]) === true) {
    $alert = "";
    if (@$_POST["start"] != "") {
        $ch = curl_init();
        $website_domain = str_ireplace("www.", "", parse_url($config["url"], PHP_URL_HOST));
        $fingerprint = create_fingerprint($website_domain, $config["purchase_code"]);
        $source = "http://api.nicheoffice.web.tr/download/update/aio-dl/" . $fingerprint;
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $source);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Niche Office - All in One Video Downloader Update Tool - VERSION:' . $config["version"]);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
            "config" => json_encode($config)
        )));
        $data = curl_exec($ch);
        curl_close($ch);
        $destination = __DIR__ . "/../../system/storage/temp/" . $fingerprint . ".zip";
        $file = fopen($destination, "w+");
        fputs($file, $data);
        fclose($file);
        if (file_exists($destination)) {
            $zip = new ZipArchive;
            $res = $zip->open($destination);
            if ($res === true) {
                $zip->extractTo(__DIR__ . "/../../");
                $zip->close();
                if (file_exists(__DIR__ . "/../../update.php")) {
                    include(__DIR__ . "/../../update.php");
                }
                unlink($destination);
                $alert = true;
            } else {
                $alert = false;
            }
        } else {
            $alert = "You are using the latest version.";
        }
    }
    ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">Software Updates</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        if ($alert === true) {
                            echo '<p class="alert alert-success">Software updated to latest version.</p>';
                        } else if ($alert === false) {
                            echo '<p class="alert alert-warning">Error occurred while updating the software.</p>';
                        } else if (!empty($alert)) {
                            printf('<p class="alert alert-info">%s</p>', $alert);
                        }
                        ?>
                        <div class="text-center">
                            <p class="lead">
                                This feature is experimental and you should backup your data before starting update. We
                                are not responsible to data lost.
                            </p>
                        </div>
                        <form method="post" class="text-center">
                            <button name="start" type="submit" class="btn btn-primary"
                                    value="Check & Install Updates" disabled>Check & Install Updates
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    http_response_code(403);
} ?>