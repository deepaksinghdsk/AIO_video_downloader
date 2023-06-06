<?php if (isset($_SESSION["logged"]) === true) { ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <?php
                    $plugins = glob(__DIR__ . "/../../system/classes/*.class.php");
                    ?>
                    <div class="card-header">
                        <h5 class="title">Plugins <small>(<?php echo count($plugins); ?>)</small></h5>
                        <p class="category"><a href="https://aiovideodl.ml/report.php" target="_blank">Report a not working link <i class='fas fa-external-link-alt'></i></a></p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            foreach ($plugins as $file) {
                                $name = explode(".", basename($file));
                                $format = '<div class="col-sm-4 col-md-3 col-lg-3 col-xl-2 d-flex align-items-stretch"><div class="card mb-3" style="max-width: 540px;"> <div class="row no-gutters"> <div class="col-md-4"> <img src="./assets/img/plugins/%s.svg" class="card-img img-thumbnail"> </div> <div class="col-md-8"> <div class="card-body"> <h6 class="card-title">%s</h6> <p class="card-text"></p> <p class="card-text"><small class="text-muted">Last updated %s</small></p> </div> </div> </div> </div></div>';
                                if (!empty($name[0]) && file_exists(__DIR__ . '/../assets/img/plugins/' . $name[0] . '.svg')) {
                                    printf($format, $name[0], $name[0], date("d/m/Y", filemtime($file)));
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    http_response_code(403);
} ?>