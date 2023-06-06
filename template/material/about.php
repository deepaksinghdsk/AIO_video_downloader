<?php if (isset($template_config["about"]) == "true") { ?>
    <div class="col-md-12 ml-auto mr-auto">
        <h2 class="title"><?php echo $lang["about-title"] ?></h2>
        <span class="badge badge-secondary mb-3">38 <?php echo $lang["sources-are-supported"]; ?></span>
        <div class="row justify-content-center">
            <?php
            $websites = get_supported_websites();
            foreach ($websites as $site) {
                if (empty($site["slug"])) {
                    $slug = $site["name"] . "-" . $site["type"] . "-downloader";
                } else {
                    $slug = $site["slug"];
                }
                if (empty($site["text"])) {
                    $title = ucwords($site["name"]) . ' ' . $lang[$site["type"]] . ' ' . $lang["downloader"];
                } else {
                    $title = $site["text"];
                }
                echo '<div class="col-sm-12 col-md-6 col-lg-4"><a class="btn btn-lg btn-block" style="background: ' . $site["color"] . ';" href="' . $config["url"] . '/' . $slug . '">';
                echo $title;
                echo '</a></div>';
            }
            ?>
        </div>
    </div>
    <div class="features">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="info">
                        <div class="icon icon-info">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <h4 class="info-title"><?php echo $lang["multiple-sources"]; ?></h4>
                        <p><?php echo $lang["about"]; ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info">
                        <div class="icon icon-success">
                            <i class="fas fa-globe-europe"></i>
                        </div>
                        <h4 class="info-title"><?php echo $lang["supported-sites"]; ?></h4>
                        <p>
                            <?php
                            echo ucwords(implode(" ", array_column($websites, "name")));
                            ?>
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info">
                        <div class="icon icon-danger">
                            <i class="fas fa-music"></i>
                        </div>
                        <h4 class="info-title"><?php echo $lang["download-audios"]; ?></h4>
                        <p><?php echo $lang["download-audios-from"]; ?> YouTube, TED, Soundcloud, Bandcamp, Tiktok,
                            Reddit</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>