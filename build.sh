
composer self-update
composer install --no-interaction --prefer-source --dev
pear upgrade PEAR
pear config-set auto_discover 1
pear install pear.phpunit.de/PHPUnit
vendor/bin/phpunit --coverage-clover=coverage.xml
g++ web/gsqasm.cpp -o web/gsqasm