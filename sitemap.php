<?php
require_once __DIR__ . "/system/config.php";
header('Content-type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    <?php
    $option = json_decode(database::find("SELECT * FROM options WHERE option_name='general_settings' LIMIT 1")[0]["option_value"], true);
    $pages = database::list_contents();
    $exclude = array();
    foreach ($pages as $page) {
        if (!in_array($page["content_slug"], $exclude)) {
            if ($page["content_slug"] == "home") {
                $page["content_slug"] = "";
            }
            $link = $option["url"] . "/" . $page["content_slug"];
        }
        echo "<url>";
        echo "<loc>$link</loc>";
        echo "<priority>1.00</priority>";
        echo "</url>";
    }
    ?>
</urlset>