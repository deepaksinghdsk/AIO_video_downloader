<?php require_once(__DIR__ . "/../../system/config.php"); ?>
<section class="showcase">
    <div class="container-fluid p-0">
        <div class="row no-gutters">
            <div class="col-lg-12 order-lg-1 my-auto showcase-text">
                <div class="m-auto">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <?php echo $lang["error-alert"] . " " . $lang["try-again"]; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>