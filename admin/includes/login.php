<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <link rel="icon" type="image/png" sizes="96x96" href=".././assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>All in One Video Downloader Dashboard</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <meta name="viewport" content="width=device-width"/>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet"/>
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="./assets/css/ui.css" rel="stylesheet"/>
</head>
<body class=" sidebar-mini ">
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
            <a class="navbar-brand"
               href="https://bit.ly/2DoPgsf">All in One
                Video Downloader</a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
                aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="../." class="nav-link">
                        <i class="now-ui-icons business_globe"></i> Go to website
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="wrapper wrapper-full-page ">
    <div class="full-page login-page section-image" filter-color="blue">
        <div class="content">
            <div class="container">
                <div class="col-md-4 ml-auto mr-auto">
                    <?php if (isset($_GET["failed-login"]) == "1") { ?>
                        <p class="alert alert-danger">E-mail or password wrong!</p>
                    <?php } ?>
                    <form method="post" action="index.php">
                        <div class="card card-login card-plain">
                            <div class="card-header ">
                                <div class="logo-container">
                                    <img src="./assets/img/now-logo.png" alt="">
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="input-group no-border form-control-lg">
                    <span class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="now-ui-icons ui-1_email-85"></i>
                      </div>
                    </span>
                                    <input type="email" name="email" placeholder="Enter email" class="form-control"
                                           required>
                                </div>
                                <div class="input-group no-border form-control-lg">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="now-ui-icons ui-1_lock-circle-open"></i>
                                        </div>
                                    </div>
                                    <input name="password" type="password" placeholder="Password" class="form-control"
                                           required>
                                </div>
                            </div>
                            <input type="hidden" name="token" value="<?php echo generate_csrf_token(); ?>">
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-round btn-lg btn-block mb-3">Login
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="full-page-background" style="background-image: url(./assets/img/bg19.jpg) "></div>
        <footer class="footer">
            <div class="container-fluid">
                <div class="copyright" id="copyright">
                    &copy; 2018 <a href="https://nicheoffice.web.tr" target="_blank">Niche Office</a>
                </div>
            </div>
        </footer>
    </div>
</div>
<script src="./assets/js/core/jquery.min.js"></script>
<script src="./assets/js/core/popper.min.js"></script>
<script src="./assets/js/core/bootstrap.min.js"></script>
<script src="./assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
<script src="./assets/js/plugins/moment.min.js"></script>
<script src="./assets/js/dashboard.js" type="text/javascript"></script>
</body>