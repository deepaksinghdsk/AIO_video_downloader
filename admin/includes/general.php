<?php if (isset($_SESSION["logged"]) === true) { ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">General Settings</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $config = json_decode(option(), true);
                        $tracking_code = option("tracking_code");
                        $gdpr_notice = option("gdpr_notice");
                        if (@$_POST && $_SESSION["logged"] === true && hash_equals($config["fingerprint"], create_fingerprint(str_ireplace("www.", "", parse_url($config["url"], PHP_URL_HOST)), $config["purchase_code"]))) {
                            database::update_option("tracking_code", $_POST["tracking_code"]);
                            database::update_option("gdpr_notice", $_POST["gdpr_notice"]);
                            unset($_POST["tracking_code"]);
                            unset($_POST["gdpr_notice"]);
                            if (get_domain($config["url"]) == get_domain($_POST["url"])) {
                                $website_domain = str_ireplace("www.", "", parse_url($_POST["url"], PHP_URL_HOST));
                                $_POST["fingerprint"] = create_fingerprint($website_domain, $config["purchase_code"]);
                            }
                            database::update_option("general_settings", json_encode($_POST));
                            echo '<p class="alert alert-success">Settings saved. The page will be refreshed within a second.</p>';
                            echo '<script>setTimeout(function(){ window.location.replace(window.location.href); }, 1000);</script>';
                        }
                        ?>
                        <form method="post">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="url">Site URL</label><br>
                                        <input id="url" class="form-control" type="url" name="url" required
                                               value="<?php echo $config["url"]; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="title">Site Title</label><br>
                                        <input id="title" class="form-control" type="text" name="title" required
                                               value="<?php echo $config["title"]; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Site Description</label><br>
                                        <textarea id="description" class="form-control"
                                                  name="description"><?php echo $config["description"]; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="author">Site Owner</label><br>
                                        <input id="author" class="form-control" type="text" name="author" required
                                               value="<?php echo $config["author"]; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Site Owner E-Mail</label><br>
                                        <input id="email" class="form-control" type="email" name="email" required
                                               value="<?php echo $config["email"]; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="download_suffix">Suffix for File Names</label>
                                        <input id="download_suffix" class="form-control" type="text"
                                               placeholder="Enter suffix for download files" name="download_suffix"
                                               value="<?php echo $config["download_suffix"]; ?>">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="template">Theme</label><br>
                                        <input id="template" class="form-control" type="text"
                                               placeholder="Enter template folder name"
                                               name="template" required
                                               value="<?php echo $config["template"]; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="language">Default Language</label><br>
                                        <select id="language" class="form-control" name="language">
                                            <?php list_languages(); ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="tracking_code">Tracking code</label><br>
                                        <textarea rows="50" id="tracking_code" class="form-control" type="text"
                                                  placeholder="Paste here your code"
                                                  name="tracking_code"><?php echo $tracking_code; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="gdpr_notice">GDPR Notice</label><br>
                                        <textarea rows="50" id="gdpr_notice" class="form-control"
                                                  placeholder="Paste here your code"
                                                  name="gdpr_notice"><?php echo $gdpr_notice; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                Enable Bandwidth Saving Mode
                                                <input id="bandwidth_saving" name="bandwidth_saving"
                                                       class="form-check-input"
                                                       type="checkbox"
                                                       value="true" <?php check_config($config, "bandwidth_saving"); ?>>
                                                <span class="form-check-sign"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                Show M4A format as MP3
                                                <input id="m4a_mp3" name="m4a_mp3"
                                                       class="form-check-input"
                                                       type="checkbox"
                                                       value="true" <?php check_config($config, "m4a_mp3"); ?>>
                                                <span class="form-check-sign"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="purchase_code" value="<?php echo $config["purchase_code"]; ?>">
                            <input type="hidden" name="fingerprint" value="<?php echo $config["fingerprint"]; ?>">
                            <input type="hidden" name="version" value="<?php echo $config["version"]; ?>">
                            <input type="hidden" name="checksum"
                                   value="<?php echo sha1_file(__DIR__ . "/../../system/action.php"); ?>">
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