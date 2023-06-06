<?php if (isset($_SESSION["logged"]) === true) { ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">Edit Translation</h5>
                        <p class="category"><a href="?view=language">Go back</a></p>
                    </div>
                    <div class="card-body">
                        <?php
                        $config = json_decode(option(), true);
                        if (empty($_GET["name"]) || file_exists(__DIR__ . "/../../language/" . $_GET["name"] . ".php") === true) {
                            redirect($config["url"] . "/admin/?view=language");
                        }
                        $file = __DIR__ . "/../../language/" . $_GET["name"] . ".php";
                        $data = file_get_contents($file);
                        $lines = explode('$lang', $data);
                        if (@$_POST && $_SESSION["logged"] === true) {
                            $new_data = $data;
                            foreach ($_POST as $key => $value) {
                                str_replace('$lang["' . $key . '"] = "' . $value . '"', "", $new_data);
                            }
                            file_put_contents($file, $new_data);
                            echo '<p class="alert alert-success">Settings saved. The page will be refreshed within a second.</p>';
                            echo '<script>setTimeout(function(){ window.location.replace(window.location.href); }, 1000);</script>';
                        }
                        ?>
                        <form method="post">
                            <div class="row">
                                <div class="col">
                                    <?php
                                    if (!empty($lines)) {
                                        foreach ($lines as $line) {
                                            $name = get_string_between($line, '["', '"]');
                                            if (!empty($name)) {
                                                echo '<div class="form-group">';
                                                $value = get_string_between($line, '= "', '";');
                                                printf('<label for="%s">%s</label>', $name, $name);
                                                printf('<input type="text" value="%s" name="%s" class="form-control">', $value, $name);
                                                echo '</div>';
                                            }
                                        }
                                    }
                                    ?>
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