<?php
declare(strict_types = 1);

\Dotenv\Dotenv::createImmutable("./")->load();

define("DB", $_ENV["DB"]);
define("DB_USER", $_ENV["DB_USER"]);
define("DB_SENHA", $_ENV["DB_SENHA"]);
define("DB_URL", $_ENV["DB_URL"]);

define("URL", $_ENV["URL"]);
