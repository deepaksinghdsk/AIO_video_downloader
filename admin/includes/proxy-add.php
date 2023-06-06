<?php if (isset($_SESSION["logged"]) === true) { ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">Add Proxy</h5>
                        <p class="category"><a href="?view=proxy">Go back</a></p>
                    </div>
                    <div class="card-body">
                        <?php
                        if (@$_POST && $_SESSION["logged"] === true) {
                            $proxy["ip"] = $_POST["ip"];
                            $proxy["port"] = $_POST["port"];
                            $proxy["type"] = $_POST["type"];
                            $proxy["username"] = $_POST["username"];
                            $proxy["password"] = $_POST["password"];
                            $proxy["usage_count"] = 0;
                            $proxy["banned"] = 0;
                            if (filter_var($proxy["ip"], FILTER_VALIDATE_IP)) {
                                database::create_proxy($proxy);
                                echo '<p class="alert alert-success">Proxy added. The page will be refreshed within a second.</p>';
                                echo '<script>setTimeout(function(){ window.location.replace(window.location.href); }, 1000);</script>';
                            } else {
                                echo '<p class="alert alert-warning">You entered invalid IP.</p>';
                            }
                        }
                        ?>
                        <form method="post">
                            <div class="form-group">
                                <label for="proxy_ip">IP Address</label>
                                <input id="proxy_ip" class="form-control" type="text" name="ip" value="" required>
                                <label for="proxy_port">Port</label>
                                <input id="proxy_port" class="form-control" type="number" name="port" value="" required>
                                <label for="proxy_type">Type</label>
                                <select id="proxy_type" name="type" class="form-control">
                                    <option value="0">HTTP</option>
                                    <option value="1">HTTPs</option>
                                    <option value="2">SOCKS4</option>
                                    <option value="3">SOCKS5</option>
                                </select>
                                <label for="proxy_username">Username</label>
                                <input id="proxy_username" class="form-control" type="text" name="username"
                                       value="">
                                <label for="proxy_password">Password</label>
                                <input id="proxy_password" class="form-control" type="text" name="password" value="">
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