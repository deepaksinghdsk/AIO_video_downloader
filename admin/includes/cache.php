<?php if (isset($_SESSION["logged"]) === true) { ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">Cache</h5>
                    </div>
                    <?php
                    if (@$_POST && $_SESSION["logged"] === true) {
                        if (isset($_POST["clear_cache_all"]) == "1") {
                            clear_disk_cache(__DIR__ . "/../../system/storage/temp/", 0);
                        }
                        if (isset($_POST["clear_cache"]) == "1") {
                            clear_disk_cache(__DIR__ . "/../../system/storage/temp/");
                        }
                        if (isset($_POST["clear_sql"]) == "1") {
                            database::delete_stats();
                        }
                        if (isset($_POST["clear_sql_all"]) == "1") {
                            database::delete_stats(0);
                        }
                        echo '<div class="card-body"><p class="alert alert-success">Cache cleared. The page will be refreshed within a second.</p></div>';
                        echo '<script>setTimeout(function(){ window.location.replace(window.location.href); }, 1000);</script>';
                    }
                    ?>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 text-center">
                            <div class="card-contributions">
                                <div class="card-body ">
                                    <h1 class="card-title"><?php echo format_size(get_directory_size(__DIR__ . "/../../system/storage/temp")); ?></h1>
                                    <h3 class="card-category">Total Disk Cache</h3>
                                    <p class="card-description"></p>
                                </div>
                                <hr>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card-stats justify-content-center">
                                                <form method="post">
                                                    <div class="form-group">
                                                        <button type="submit" name="clear_cache_all" value="1"
                                                                class="btn btn-info btn-fill btn-wd">Clear All
                                                        </button>
                                                        <button type="submit" name="clear_cache" value="1"
                                                                class="btn btn-info btn-fill btn-wd">Clear Older Than 1
                                                            Day
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 text-center">
                            <div class="card-contributions">
                                <div class="card-body ">
                                    <?php
                                    $table_status = database::table_status();
                                    $i = array_search("downloads", array_column($table_status, "Name"));
                                    ?>
                                    <h1 class="card-title"><?php echo format_size($table_status[$i]["Data_length"]); ?></h1>
                                    <h3 class="card-category">Size of Stats Table in the Database</h3>
                                    <p class="card-description"></p>
                                </div>
                                <hr>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card-stats justify-content-center">
                                                <form method="post">
                                                    <div class="form-group">
                                                        <button type="submit" name="clear_sql_all" value="1"
                                                                class="btn btn-info btn-fill btn-wd">Clear All
                                                        </button>
                                                        <button type="submit" name="clear_sql" value="1"
                                                                class="btn btn-info btn-fill btn-wd">Clear Older Than 30
                                                            Days
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    http_response_code(403);
} ?>