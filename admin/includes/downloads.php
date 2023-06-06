<?php if (isset($_SESSION["logged"]) === true) { ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <?php
                    $downloads_limit = 30;
                    $downloads_list = database::list_downloads($downloads_limit);
                    ?>
                    <div class="card-header">
                        <h5 class="title">Latest Downloads</h5>
                        <p class="category">Last <?php echo $downloads_limit; ?> downloads listed.</p>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Source</th>
                                <th>Thumbnail</th>
                                <th>Media</th>
                                <th>Client IP</th>
                                <th>Country</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($downloads_list)) {
                                foreach ($downloads_list as $download) {
                                    $meta = json_decode($download["download_meta"], true);
                                    $countries = array();
                                    if (filter_var($meta["thumbnail"] ?? null, FILTER_VALIDATE_URL)) {
                                        echo "<tr>";
                                        echo "<td>" . $download["download_date"] . "</td>";
                                        echo "<td>" . ucfirst($download["download_source"]) . "</td>";
                                        echo "<td><img src='" . $meta["thumbnail"] . "' class='img img-thumbnail rounded h-25' onerror=\"this.src='https://cdn.nicheoffice.web.tr/image/5bE7J6oOjH.jpg';\"></td>";
                                        echo "<td><a target='_blank' href='" . $meta["video_url"] . "'>Media Link <i class='fas fa-external-link-alt'></i></a></td>";
                                        echo "<td>" . ($meta["client_ip"] ?? "Unknown") . "</td>";
                                        if (filter_var($meta["client_ip"] ?? "", FILTER_VALIDATE_IP)) {
                                            if (empty($countries[$meta["client_ip"]] ?? "")) {
                                                $api_url = "https://freegeoip.app/json/" . $meta["client_ip"];
                                                $response = url_get_contents($api_url);
                                                $response = json_decode($response, true);
                                                $countries[$meta["client_ip"]] = $response;
                                            }
                                            $response = $countries[$meta["client_ip"]];
                                            $country_code = strtolower($response["country_code"]);
                                            $flag = __DIR__ . "/../assets/img/flags/" . $country_code . ".svg";
                                            if (file_exists($flag)) {
                                                echo '<td><img src="./assets/img/flags/' . $country_code . '.svg" class="img-thumbnail w-25" alt="' . $response["country_name"] . '">' . $response["country_name"] . '</td>';
                                            } else {
                                                echo '<td>' . $response["country_name"] . '</td>';
                                            }
                                        } else {
                                            echo '<td>Unknown</td>';
                                        }
                                        echo "</tr>";
                                    }
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    http_response_code(403);
} ?>