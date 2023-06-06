<?php
session_destroy();
$config = json_decode(option(), true);
printf('<script>setTimeout(function(){ window.location.replace(%s); }, 1000);</script>;', $config["url"] . "/admin");