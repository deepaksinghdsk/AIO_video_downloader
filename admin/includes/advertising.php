<?php if (isset($_SESSION["logged"]) === true) { ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">Advertising</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        if (@$_POST && $_SESSION["logged"] === true) {
                            database::update_option("ads.1", $_POST["1"]);
                            database::update_option("ads.2", $_POST["2"]);
                            database::update_option("ads.3", $_POST["3"]);
                            database::update_option("ads.4", $_POST["4"]);
                            echo '<p class="alert alert-success">Settings saved. The page will be refreshed within a second.</p>';
                            echo '<script>setTimeout(function(){ window.location.replace(window.location.href); }, 1000);</script>';
                        }
                        ?>
                        <form method="post">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label for="ads_1">Ad Area 1</label><br>
                                            <textarea placeholder="Paste your ad code" id="ads_1" class="form-control" rows="10" cols="80"
                                                      name="1"><?php option("ads.1", true); ?></textarea>
                                        </div>
                                        <div class="input-group">
                                            <label for="ads_2">Ad Area 2</label><br>
                                            <textarea placeholder="Paste your ad code" id="ads_2" class="form-control" rows="10" cols="80"
                                                      name="2"><?php option("ads.2", true); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label for="ad_area_3">Ad Area 3</label><br>
                                            <textarea placeholder="Paste your ad code" id="ad_area_3" class="form-control" rows="10" cols="80"
                                                      name="3"><?php option("ads.3", true); ?></textarea>
                                        </div>
                                        <div class="input-group">
                                            <label for="ads_4">Ad Area 4</label><br>
                                            <textarea placeholder="Paste your ad code" id="ads_4" class="form-control" rows="10" cols="80"
                                                      name="4"><?php option("ads.4", true); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-info btn-fill btn-wd">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    http_response_code(403);
} ?>