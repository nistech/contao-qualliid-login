:: Run easy-coding-standard (ecs) via this batch file inside your IDE e.g. PhpStorm (Windows only)
:: Install inside PhpStorm the  "Batch Script Support" plugin
cd..
cd..
cd..
cd..
cd..
cd..
php vendor\bin\ecs check vendor/nistech/contao-qualliid-client/src --fix --config vendor/nistech/contao-qualliid-client/tools/ecs/config.php
php vendor\bin\ecs check vendor/nistech/contao-qualliid-client/contao --fix --config vendor/nistech/contao-qualliid-client/tools/ecs/config.php
php vendor\bin\ecs check vendor/nistech/contao-qualliid-client/config --fix --config vendor/nistech/contao-qualliid-client/tools/ecs/config.php
php vendor\bin\ecs check vendor/nistech/contao-qualliid-client/templates --fix --config vendor/nistech/contao-qualliid-client/tools/ecs/config.php
php vendor\bin\ecs check vendor/nistech/contao-qualliid-client/tests --fix --config vendor/nistech/contao-qualliid-client/tools/ecs/config.php
