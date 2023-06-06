<?php if (isset($_SESSION["logged"]) === true) { ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8"/>
        <link rel="icon" type="image/png" sizes="96x96" href=".././assets/img/favicon.png">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
        <title>All in One Video Downloader Dashboard</title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
              name='viewport'/>
        <link href="./assets/css/bootstrap.min.css" rel="stylesheet"/>
        <link href="./assets/css/ui.css" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet"/>
        <link href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" rel="stylesheet">
        <style>.fr-wrapper > div > a {
                display: none !important;
            }</style>
    </head>
    <body class="sidebar-mini menu-on-left">
    <div class="wrapper">
        <div class="sidebar" data-color="blue">
            <div class="logo">
                <a href="https://nicheoffice.web.tr" class="simple-text logo-mini">
                    NO
                </a>
                <a href="https://nicheoffice.web.tr" class="simple-text logo-normal">
                    Niche Office
                </a>
            </div>
            <div class="sidebar-wrapper ps-container ps-theme-default ps-active-x ps-active-y"
                 data-ps-id="0cbf0d88-d5e8-c455-a506-f61e7c994f33">
                <ul class="nav">
                    <li>
                        <a href="?view=default">
                            <i class="now-ui-icons design_app"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li>
                        <a href="?view=general">
                            <i class="now-ui-icons ui-1_settings-gear-63"></i>
                            <p>General Settings</p>
                        </a>
                    </li>
                    <li>
                        <a href="?view=api">
                            <i class="now-ui-icons objects_key-25"></i>
                            <p>API Keys</p>
                        </a>
                    </li>
                    <li>
                        <a href="?view=proxy">
                            <i class="now-ui-icons location_world"></i>
                            <p>Proxies</p>
                        </a>
                    </li>
                    <li>
                        <a href="?view=cache">
                            <i class="now-ui-icons files_box"></i>
                            <p>Cache</p>
                        </a>
                    </li>
                    <li>
                        <a href="?view=advertising">
                            <i class="now-ui-icons design_image"></i>
                            <p>Ads</p>
                        </a>
                    </li>
                    <li>
                        <a href="?view=theme">
                            <i class="now-ui-icons design-2_ruler-pencil"></i>
                            <p>Appearance</p>
                        </a>
                    </li>
                    <li>
                        <a href="?view=content">
                            <i class="now-ui-icons files_single-copy-04"></i>
                            <p>Contents</p>
                        </a>
                    </li>
                    <li>
                        <a href="?view=plugins">
                            <i class="now-ui-icons shopping_box"></i>
                            <p>Plugins</p>
                        </a>
                    </li>
                    <li>
                        <a href="?view=downloads">
                            <i class="now-ui-icons arrows-1_cloud-download-93"></i>
                            <p>Latest Downloads</p>
                        </a>
                    </li>
                    <li>
                        <a href="?view=about">
                            <i class="now-ui-icons objects_support-17"></i>
                            <p>About</p>
                        </a>
                    </li>
                </ul>
                <div class="ps-scrollbar-x-rail" style="width: 80px; left: 0px; bottom: 0px;">
                    <div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 41px;"></div>
                </div>
                <div class="ps-scrollbar-y-rail" style="top: 0px; height: 620px; right: 0px;">
                    <div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 513px;"></div>
                </div>
            </div>
        </div>


        <div class="main-panel ps-container ps-theme-default ps-active-y"
             data-ps-id="8fbfbfc8-d9c4-05ed-33ec-20483f649ceb">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-transparent  navbar-absolute bg-primary ">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <div class="navbar-toggle">
                            <button type="button" class="navbar-toggler">
                                <span class="navbar-toggler-bar bar1"></span>
                                <span class="navbar-toggler-bar bar2"></span>
                                <span class="navbar-toggler-bar bar3"></span>
                            </button>
                        </div>
                        <a class="navbar-brand" href="">All in One Video Downloader</a>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
                            aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navigation">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#"
                                   id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                   aria-expanded="false">
                                    <i class="now-ui-icons users_single-02"></i>
                                    <p>
                                        <span class="d-lg-none d-md-block">Options</span>
                                    </p>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right"
                                     aria-labelledby="navbarDropdownMenuLink">
                                    <a target="_blank" class="dropdown-item" href="../.">Go to website</a>
                                    <a class="dropdown-item" href="?view=password">Change Password</a>
                                    <a target="_blank" class="dropdown-item" href="https://support.nicheoffice.web.tr">Get
                                        Support</a>
                                    <a class="dropdown-item" href="?view=logout">Logout</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
            <?php
            if (isset($_GET["view"]) != "") {
                $view = filter_var($_GET["view"], FILTER_SANITIZE_STRING);
                if (file_exists(__DIR__ . "/" . $view . ".php")) {
                    include(__DIR__ . "/" . $view . ".php");
                } else {
                    include(__DIR__ . "/default.php");
                }
            } else {
                include(__DIR__ . "/default.php");
            }
            ?>
            <footer class="footer">
                <div class="container-fluid">
                    <div class="copyright" id="copyright">
                        &copy;
                        <script>
                            document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))
                        </script>
                        <a href="https://nicheoffice.web.tr">Niche Office</a>
                    </div>
                </div>
            </footer>
            <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 0px;">
                <div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div>
            </div>
            <div class="ps-scrollbar-y-rail" style="top: 0px; right: 0px; height: 695px;">
                <div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 130px;"></div>
            </div>
        </div>
    </div>
    </body>
    <script src="./assets/js/core/jquery.min.js"></script>
    <script src="./assets/js/core/popper.min.js"></script>
    <script src="./assets/js/core/bootstrap.min.js"></script>
    <script src="./assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    <script src="./assets/js/plugins/moment.min.js"></script>
    <script src="./assets/js/plugins/bootstrap-notify.js"></script>
    <script src="./assets/js/dashboard.min.js"></script>
    </html>
<?php } else {
    http_response_code(403);
} ?>