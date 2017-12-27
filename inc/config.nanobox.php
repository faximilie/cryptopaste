<?php
define("INSTALLED", true);

define("DB_HOST", $_ENV["DATA_DB_HOST"]);
define("DB_PORT", "");
define("DB_USER", $_ENV["DATA_DB_USER"]);
define("DB_PASS", $_ENV["DATA_DB_PASS"]);
define("DB_CHAR", "utf8mb4");

define("WWW_HOST", $_ENV["APP_ADDRESS"]);

?>
