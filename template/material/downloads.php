<?php require_once(__DIR__ . "/../../system/config.php"); ?>
<div class="col-md-4 text-center">
    <div class="video-details">
        <p class="lead">{{video_title}} <small><span id="video-details">({{video_duration}})</span></small></p>
        <img class="img-thumbnail" src="{{video_thumbnail}}"
             alt="{{video_title}}">
    </div>
    <div id="share-buttons">
        <p class="lead share-text"><?php echo $lang["share"]; ?></p>
        <a title="Facebook" href="{{facebook_share_link}}" class="btn btn-sm btn-social btn-fill btn-facebook"
           target="_blank">
            <i class="fab fa-facebook fa-fw"></i>
        </a>
        <a title="Twitter" href="{{twitter_share_link}}" class="btn btn-sm btn-social btn-fill btn-twitter"
           target="_blank">
            <i class="fab fa-twitter fa-fw"></i>
        </a>
        <a title="Whatsapp" href="{{whatsapp_share_link}}" class="btn btn-sm btn-social btn-fill btn-whatsapp"
           target="_blank">
            <i class="fab fa-whatsapp fa-fw"></i>
        </a>
        <a title="Pinterest" href="{{pinterest_share_link}}" class="btn btn-sm btn-social btn-fill btn-pinterest"
           target="_blank">
            <i class="fab fa-pinterest-p fa-fw"></i>
        </a>
        <a title="Tumblr" href="{{tumblr_share_link}}" class="btn btn-sm btn-social btn-fill btn-tumblr"
           target="_blank">
            <i class="fab fa-tumblr fa-fw"></i>
        </a>
        <a title="Reddit" href="{{reddit_share_link}}" class="btn btn-sm btn-social btn-fill btn-reddit"
           target="_blank">
            <i class="fab fa-reddit fa-fw"></i>
        </a>
        <a title="QR Code" href="{{qr_share_link}}" class="btn btn-sm btn-social btn-fill btn-github" target="_blank">
            <i class="fas fa-qrcode"></i>
        </a>
    </div>
</div>
<div class="col-md-8 video-links">
    <?php if (isset($_GET["video"]) === true) { ?>
        {{video_links}}
    <?php } ?>
    <?php if (isset($_GET["audio"]) === true) { ?>
        {{audio_links}}
    <?php } ?>
</div>
<div class="col-md-12">
    <?php
    if (isset($template_config["ads"]) == "true") {
        printf('<div class="ad text-center">%s</div>', option("ads.1"));
    }
    ?>
</div>