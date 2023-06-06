<?php if (isset($_SESSION["logged"]) === true) { ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">Contents</h5>
                        <p class="category"><a href="?view=content-create">Create a new content</a></p>
                    </div>
                    <div class="card-body">
                        <?php
                        $config = json_decode(option(), true);
                        $contents_list = database::list_contents();
                        $contents_count = count($contents_list);
                        //$content_per_column = (int)ceil(($contents_count + ($contents_count % 3)) / 3);
                        $content_per_column = (int)ceil($contents_count / 3);
                        ?>
                        <div class="row">
                            <?php
                            $k = 0;
                            for ($i = 0; $i < 3; $i++) {
                                $column_content = "";
                                for ($j = 0; $j < $content_per_column && $k < $contents_count; $j++) {
                                    $content = $contents_list[$k];
                                    $buttons = '<a target="_blank" class="btn btn-simple btn-icon btn-sm btn-info" href="' . $config["url"] . '/' . $content["content_slug"] . '" title="Open in an external tab"><i class="fas fa-link"></i></a>';
                                    $buttons .= ' <a class="btn btn-simple btn-icon btn-sm btn-primary" href="?view=content-edit&id=' . $content["ID"] . '" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
                                    $buttons .= ' <a class="btn btn-simple btn-icon btn-sm btn-danger" href="?view=content-delete&id=' . $content["ID"] . '" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                                    $column_content .= sprintf("<tr><td>%s</td><td class='text-center'>%s</td></tr>", $content["content_title"], $buttons);
                                    $k++;
                                }
                                printf('<div class="col-sm-12 col-md-4 col-lg-4"><table class="table table-striped table-bordered"><thead class="thead-light"><tr><th scope="col">Content Title</th><th scope="col" class="text-center">Actions</th></tr></thead><tbody>%s</tbody></table></div>', $column_content);
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    http_response_code(403);
} ?>