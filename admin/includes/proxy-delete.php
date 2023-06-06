<?php
if (isset($_SESSION["logged"]) === true) {
    $config = json_decode(option(), true);
    if (!empty($_GET["id"]) && is_numeric($_GET["id"]) === true) {
        $proxy_id = (int)$_GET["id"];
        database::delete_proxy($proxy_id);
        redirect($config["url"] . "/admin/?view=proxy");
    } else {
        redirect($config["url"] . "/admin/?view=proxy");
    }
} else {
    http_response_code(403);
}