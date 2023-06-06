<?php if (isset($_SESSION["logged"]) === true) { ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">Change Password</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        if (@$_POST && $_SESSION["logged"] === true) {
                            if (hash_equals(sha1($_POST["password"]), sha1($_POST["_password"]))) {
                                database::update_password($_SESSION["user"]["user_email"], sha1($_POST["password"]));
                                echo '<p class="alert alert-success">Password changed.</p>';
                            } else {
                                echo '<p class="alert alert-warning">Passwords did not match.</p>';
                            }
                        }
                        ?>
                        <form method="post">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </div>
                                </div>
                                <input placeholder="New password" class="form-control" type="password" name="password" required>
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </div>
                                </div>
                                <input placeholder="Confirm password" class="form-control" type="password" name="_password" required>
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