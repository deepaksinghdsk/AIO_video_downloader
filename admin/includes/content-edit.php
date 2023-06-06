<?php if (isset($_SESSION["logged"]) === true) { ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                        <script src="./assets/js/tinymce/tinymce.min.js"></script>
                        <script>
                            tinymce.init({
                                selector: '#content_text',
                                plugins: [
                                    'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
                                    'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
                                    'table emoticons template paste help'
                                ]
                            });
                        </script>
                        <h5 class="title">Edit Content</h5>
                        <p class="category"><a href="?view=content">Go back</a></p>
                    </div>
                    <div class="card-body">
                        <?php
                        $page_id = filter_var($_GET["id"], FILTER_SANITIZE_NUMBER_INT);
                        $page = database::find_content($page_id);
                        if (empty($page)) {
                            $config = json_decode(option(), true);
                            redirect($config["url"] . "/admin");
                        }
                        if (@$_POST && $_SESSION["logged"] === true) {
                            $content["id"] = $_POST["content_id"];
                            $content["title"] = $_POST["content_title"];
                            $content["slug"] = $_POST["content_slug"];
                            $content["text"] = $_POST["content_text"];
                            $content["description"] = $_POST["content_description"];
                            $content["type"] = $_POST["content_type"];
                            $content["opt"] = "";
                            if (database::slug_exists($content["slug"]) <= 1) {
                                database::update_content($content);
                                echo '<p class="alert alert-success">Changes saved. The page will be refreshed within a second.</p>';
                                echo '<script>setTimeout(function(){ window.location.replace(window.location.href); }, 1000);</script>';
                            } else {
                                echo '<p class="alert alert-warning">Slug exists. Try with different.</p>';
                            }
                        }
                        ?>
                        <form method="post">
                            <div class="row">
                                <div class="form-group col-lg-2 col-md-2 col-sm-12">
                                    <label for="content_description">Content Title</label>
                                    <input class="form-control" type="text" name="content_title"
                                           id="content_title" value="<?php echo $page["content_title"]; ?>" required>
                                    <label for="content_slug">Content Slug</label>
                                    <input class="form-control" type="text" name="content_slug" id="content_slug"
                                           required
                                           value="<?php echo $page["content_slug"]; ?>">
                                    <input type="hidden" name="content_id" value="<?php echo $_GET["id"]; ?>" required>
                                    <label for="content_description">Content Description</label>
                                    <input class="form-control" type="text" name="content_description"
                                           id="content_description" value="<?php echo $page["content_description"]; ?>">
                                    <label for="content_description">Content Type</label>
                                    <select id="content_type" name="content_type" class="form-control">
                                        <option <?php if ($page["content_type"] == 0) echo "selected"; ?> value="0">
                                            Page
                                        </option>
                                        <option <?php if ($page["content_type"] == 1) echo "selected"; ?> value="1">
                                            Downloader
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-10 col-md-10 col-sm-12">
                                    <label for="content_text">Content</label>
                                    <textarea name="content_text"
                                              id="content_text"
                                              rows="30"><?php echo $page["content_text"]; ?></textarea>
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