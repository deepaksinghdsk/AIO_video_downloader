<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="generator" content="All in One Video Downloader https://aiovideodl.ml"/>
    <?php
    if ($slug != "" && substr($slug, 0, 1) != "?") {
        $content = content($slug);
        $title = $content["content_title"];
        $description = $content["content_description"];
    } else {
        unset($slug);
        $title = $config["title"];
        $description = $config["description"];
    }
    $recaptcha_public_key = option("api_key.recaptcha_public") ?? "";
    ?>
    <title><?php echo $title; ?></title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
          name='viewport'/>
    <meta itemprop="name" content="<?php echo $title; ?>">
    <meta name="description" content="<?php echo $description; ?>">
    <meta name="author" content="<?php echo $config["author"]; ?>"/>
    <meta itemprop="image" content="<?php echo $config["url"]; ?>/assets/img/social-media-banner.jpg">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="<?php echo $title; ?>">
    <meta name="twitter:description" content="<?php echo $description; ?>">
    <meta name="twitter:image:src" content="<?php echo $config["url"]; ?>/assets/img/social-media-banner.jpg">
    <meta property="og:title" content="<?php echo $title; ?>">
    <meta property="og:type" content="article">
    <meta property="og:image" content="<?php echo $config["url"]; ?>/assets/img/social-media-banner.jpg">
    <meta property="og:description" content="<?php echo $description; ?>">
    <meta property="og:site_name" content="<?php echo $title; ?>">
    <link rel="stylesheet" href="<?php echo $config["url"]; ?>/template/material/css/material.css"/>
    <link rel="stylesheet" href="<?php echo $config["url"]; ?>/template/material/css/custom.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700"/>
    <link rel="shortcut icon" href="<?php echo $config["url"]; ?>/assets/img/favicon.png"/>
    <link rel="canonical"
          href="<?php echo $canonical_url ?>"/>
    <?php
    href_tags($config["url"] . (isset($slug) ? "/" . $slug : ""));
    $bg = array('bg-1.jpg', 'bg-2.jpg', 'bg-3.jpg', 'bg-4.jpg', 'bg-5.jpg');
    $i = rand(0, count($bg) - 1);
    $selected_bg = $config["url"] . "/template/material/img/" . $bg[$i];
    ?>
</head>
<body id="body" class="landing-page sidebar-collapse"
      dir="<?php echo ($_SESSION["current_language"]["rtl"] ?? false) === true ? "rtl" : "auto" ?>">
<nav class="navbar navbar-color-on-scroll navbar-transparent fixed-top  navbar-expand-lg " color-on-scroll="100"
     id="sectionsNav">
    <div class="container">
        <div class="navbar-translate">
            <a class="navbar-brand" href="<?php echo $config["url"]; ?>">
                <?php
                if (isset($template_config["show_logo"]) === true) {
                    printf('<img src="%s" alt="%s" title="%s" class="img-fluid">', $template_config["logo_url"], $config["title"], $config["title"]);
                } else {
                    echo $config["title"];
                }
                ?>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="sr-only">Toggle navigation</span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <?php
                build_menu();
                if (isset($template_config["tos"]) == "true") {
                    echo "<li class='nav-item'><a class='nav-link' href='tos'>" . $lang["terms-of-service"] . "</a></li>";
                }
                if (isset($template_config["contact"]) == "true") {
                    echo "<li class='nav-item'><a class='nav-link' href='contact'>" . $lang["contact"] . "</a></li>";
                }
                ?>
                <li class="dropdown nav-item">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <i class="fas fa-globe"></i> <?php echo $lang["language"]; ?>
                    </a>
                    <div class="dropdown-menu dropdown-with-icons">
                        <?php list_languages(); ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php if ($content["content_type"] == 0)  { ?>
    <div class="page-header header-filter" data-parallax="true"
         style="background-image: url('<?php echo $selected_bg; ?>'); height: 22vh;">
    </div>
<?php } else { ?>
<div class="page-header header-filter" data-parallax="true"
     style="background-image: url('<?php echo $selected_bg; ?>')">
    <div class="container">
        <div class="row">
            <div class="col-md-8 ml-auto mr-auto text-center">
                <h1 class="title page-title">
                    <?php
                    if ($content["content_type"] == 1) {
                        echo $content["content_title"];
                    } else {
                        echo $lang["homepage-slogan"];
                    }
                    ?>
                </h1>
                <div class="form-group" style="z-index: 999">
                    <div class="input-group">
                        <input name="url" type="url" id="url" class="form-control"
                               placeholder="<?php echo $lang["placeholder"]; ?>">
                        <?php
                        printf('<input type="hidden" name="token" id="token"
                               value="%s">', $_SESSION["token"] ?? "");
                        $onclick = !empty($recaptcha_public_key) ? 'onclick="recaptcha_execute()"' : "";
                        printf('<button type="button" class="btn btn-secondary btn-paste" id="paste"><i class="far fa-clipboard fa-lg"></i></button>');
                        printf('<button type="button" class="btn btn-warning btn-download" %s data-toggle="popover" data-placement="bottom" data-trigger="manual" data-content="%s %s" id="send"> <i class="fas fa-download"></i> </button>', $onclick, $lang["error-alert"], $lang["try-again"]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>