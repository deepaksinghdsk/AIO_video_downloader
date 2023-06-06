<footer class="footer footer-default footer-big">
    <div class="container">
        <div class="content">
            <div class="row">
                <div class="col-md-4">
                    <h5><?php echo $lang["about-us"]; ?></h5>
                    <p>You can change this line in /template/material/footer.php</p>
                    <p>You can change this line in /template/material/footer.php</p>
                    <?php social_links(); ?>
                </div>
                <div class="col-md-8">
                    <?php if (isset($template_config["latest-downloads"]) == "true") { ?>
                        <h5><?php echo $lang["latest-downloads"]; ?></h5>
                        <div class="gallery-feed">
                            <?php
                            $downloads_list = database::list_downloads(4);
                            $temp_array = array();
                            $items = "";
                            for ($i = 0; $i < count($downloads_list); $i++) {
                                $meta = json_decode($downloads_list[$i]["download_meta"], true);
                                $items .= '<img class="img img-raised rounded" src="' . $meta['thumbnail'] . '" alt="slide ' . $i . '" onerror="this.src=\'https://cdn.nicheoffice.web.tr/image/5bE7J6oOjH.jpg\';">';
                            }
                            echo $items;
                            ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <hr>
        <ul class="float-left">
            <?php build_menu(true); ?>
            <li>
                <a href="<?php echo $config["url"]; ?>/sitemap.xml">
                    Sitemap
                </a>
            </li>
        </ul>
        <div class="copyright float-right">
            Copyright ©
            <script>
                document.write(new Date().getFullYear())
            </script>
            <a href="<?php echo $config["url"]; ?>"><?php echo $config["title"]; ?></a>
        </div>
    </div>
</footer>
<script src="<?php echo $config["url"]; ?>/template/material/js/compressed.js" type="text/javascript"></script>
<script src="<?php echo $config["url"]; ?>/template/material/js/main.js" type="text/javascript"></script>
<?php
option("tracking_code", true);
option("gdpr_notice", true);
if (!empty($recaptcha_public_key)) {
    printf('<script src="https://www.google.com/recaptcha/api.js?render=%s"></script>', $recaptcha_public_key);
    printf("<script>%s</script>", str_replace('%s', $recaptcha_public_key, file_get_contents(__DIR__ . '/js/captcha.js')));
}
?>
</body>
</html>