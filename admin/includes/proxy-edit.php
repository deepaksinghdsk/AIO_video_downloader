<?php if (isset($_SESSION["logged"]) === true) { ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">Edit Proxy</h5>
                        <p class="category"><a href="?view=proxy">Go back</a></p>
                    </div>
                    <div class="card-body">
                        <?php
                        $proxy_id = filter_var($_GET["id"], FILTER_SANITIZE_NUMBER_INT);
                        $proxy = database::find_proxy($proxy_id);
                        if (@$_POST && $_SESSION["logged"] === true) {
                            $proxy["id"] = $proxy_id;
                            $proxy["ip"] = $_POST["ip"];
                            $proxy["port"] = $_POST["port"];
                            $proxy["type"] = $_POST["type"];
                            $proxy["username"] = $_POST["username"];
                            $proxy["password"] = $_POST["password"];
                            $proxy["usage_count"] = $_POST["usage_count"];
                            $proxy["banned"] = $_POST["banned"];
                            if (filter_var($proxy["ip"], FILTER_VALIDATE_IP)) {
                                database::update_proxy($proxy);
                                echo '<p class="alert alert-success">Proxy updated. The page will be refreshed within a second.</p>';
                                echo '<script>setTimeout(function(){ window.location.replace(window.location.href); }, 1000);</script>';
                            } else {
                                echo '<p class="alert alert-warning">You entered invalid IP.</p>';
                            }
                        }
                        ?>
                        <form method="post">
                            <div class="form-group">
                                <label for="proxy_ip">IP Address</label>
                                <input id="proxy_ip" class="form-control" type="text" name="ip" value="<?php echo $proxy["ip"]; ?>" required>
                                <label for="proxy_port">Port</label>
                                <input id="proxy_port" class="form-control" type="number" name="port" value="<?php echo $proxy["port"]; ?>" required>
                                <label for="proxy_type">Type</label>
                                <select id="proxy_type" name="type" class="form-control">
                                    <option value="0" <?php echo $proxy["type"] == 0 ? "selected" :  ""; ?>>HTTP</option>
                                    <option value="1" <?php echo $proxy["type"] == 1 ? "selected" :  ""; ?>>HTTPs</option>
                                    <option value="2" <?php echo $proxy["type"] == 2 ? "selected" :  ""; ?>>SOCKS4</option>
                                    <option value="3" <?php echo $proxy["type"] == 3 ? "selected" :  ""; ?>>SOCKS5</option>
                                </select>
                                <label for="proxy_username">Username</label>
                                <input id="proxy_username" class="form-control" type="text" name="username"
                                       value="<?php echo $proxy["username"]; ?>">
                                <label for="proxy_password">Password</label>
                                <input id="proxy_password" class="form-control" type="text" name="password" value="<?php echo $proxy["password"]; ?>">
                                <label for="proxy_usage_count">Usage Count</label>
                                <input id="proxy_usage_count" class="form-control" type="number" name="usage_count" value="<?php echo $proxy["usage_count"]; ?>">
                                <label for="proxy_banned">Banned</label>
                                <select id="proxy_banned" name="banned" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
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