<?php if (isset($_SESSION["logged"]) === true) { ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">Test Proxy</h5>
                        <p class="category"><a href="?view=proxy">Go back</a></p>
                    </div>
                    <div class="card-body">
                        <?php
                        $proxy_id = filter_var($_GET["id"], FILTER_SANITIZE_NUMBER_INT);
                        $proxy = database::find_proxy($proxy_id);
                        $isWorking = test_proxy($proxy);
                        if ($isWorking) {
                            echo '<p class="alert alert-success">Proxy working.</p>';
                            $hasCaptcha = test_proxy($proxy, true);
                            if (!$hasCaptcha) {
                                echo '<p class="alert alert-success">Proxy can work for YouTube.</p>';
                            } else {
                                echo '<p class="alert alert-warning">Proxy can not work for YouTube.</p>';
                            }
                        } else {
                            echo '<p class="alert alert-warning">Proxy not working.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    http_response_code(403);
} ?>