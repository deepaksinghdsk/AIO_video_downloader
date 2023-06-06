<?php if (isset($_SESSION["logged"]) === true) { ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">Proxies</h5>
                        <p class="category"><a href="?view=proxy-add">Add a new proxy</a></p>
                    </div>
                    <div class="card-body">
                        <?php
                        $config = json_decode(option(), true);
                        $proxies_list = database::list_proxies();
                        ?>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>IP</th>
                                <th>Port</th>
                                <th>Type</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Usage</th>
                                <th>Banned</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($proxies_list[0] ?? null)) {
                                foreach ($proxies_list as $proxy) {
                                    echo "<tr>";
                                    echo "<td>" . $proxy["ip"] . "</td>";
                                    echo "<td>" . $proxy["port"] . "</td>";
                                    echo "<td>" . get_proxy_type($proxy["type"]) . "</td>";
                                    echo "<td>" . $proxy["username"] . "</td>";
                                    echo "<td style='-webkit-text-security: disc'>" . $proxy["password"] . "</td>";
                                    echo "<td>" . $proxy["usage_count"] . "</td>";
                                    echo "<td>" . ($proxy["banned"] === 1 ? "Yes" : "No") . "</td>";
                                    echo '<td>';
                                    echo '<a class="btn btn-sm btn-info" title="Check proxy" href="?view=proxy-test&id=' . $proxy["ID"] . '"><i class="fas fa-check"></i></a>';
                                    echo ' <a class="btn btn-sm btn-primary" title="Edit proxy" href="?view=proxy-edit&id=' . $proxy["ID"] . '"><i class="fas fa-pencil-alt"></i></a>';
                                    echo ' <a class="btn btn-sm btn-danger" title="Delete proxy" href="?view=proxy-delete&id=' . $proxy["ID"] . '"><i class="fas fa-trash-alt"></i></a>';
                                    echo '</td>';
                                    echo "</tr>";
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    http_response_code(403);
} ?>