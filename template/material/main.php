<div class="main main-raised" id="download_area">
    <div class="container container-padding">
        <?php if (isset($template_config["ads"]) == "true") { ?>
            <div class="ad text-center">
                <?php option("ads.2", true); ?>
            </div>
        <?php } ?>
        <div id="alert"></div>
        <div class="row" id="links"></div>
        <?php
        $content = content("home");
        echo $content["content_text"];
        ?>
        <div class="section text-center">
            <div class="row">
                <?php include(__DIR__ . "/about.php") ?>
            </div>
        </div>
    </div>
</div>