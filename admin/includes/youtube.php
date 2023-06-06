<?php if (isset($_SESSION["logged"]) === true) { ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">YouTube Connection Test</h5>
                        <p class="category"><a href="./?view=about">Return About</a></p>
                    </div>
                    <div class="card-body">
                        <?php
                        $hasBlocked = test_youtube_connection();
                        print_r($hasBlocked);
                        if ($hasBlocked) {
                            echo '<p class="alert alert-warning">YouTube blocked your server\'s IP. Add proxies at proxy page then change $enable_proxies to true in /system/classes/youtube.class.php</p>';
                        } else {
                            echo '<p class="alert alert-success">The server can connect to YouTube.</p>';
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