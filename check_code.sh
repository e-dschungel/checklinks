#!/bin/sh

PHPCS_EXEC="./vendor/bin/phpcs"
PHPCS_IGNORE_PATHS="*/vendor/*,*/.phpdoc/*,*/mydbdumper/*,*/extplorer/,*/awstats/,*/php_version_test/,*/wiki/*,*/mod/*,*/rssgoemail/*"
PHPCOMPATIBILITY_IGNORE_PATHS="*/vendor/squizlabs/*,*/vendor/PHPCompatibility/*"
MIN_PHP_VERSION="8.1"
PHPSTAN_EXEC="./vendor/bin/phpstan"
PHPSTAN_FILES_AND_DIRS="*.php src/*.php"
PHPSTAN_LEVEL="1"

echo "Static Code Analysis"
$PHPSTAN_EXEC analyse -l $PHPSTAN_LEVEL $PHPSTAN_FILES_AND_DIRS

echo "Check PHP Coding Standard PSR12 (modified)"
$PHPCS_EXEC --ignore=$PHPCS_IGNORE_PATHS --standard=psr12 .

echo "Check PHP Coding Standard PEAR (modified)"
$PHPCS_EXEC --ignore=$PHPCS_IGNORE_PATHS -s --standard=mypear.xml .

echo "Check PHP Compatibility >= $MIN_PHP_VERSION"
$PHPCS_EXEC --ignore=$PHPCS_IGNORE_PATHS --standard=PHPCompatibility --runtime-set testVersion ${MIN_PHP_VERSION}- .
#$PHPCS_EXEC --ignore=$PHPCOMPATIBILITY_IGNORE_PATHS --standard=PHPCompatibility --runtime-set testVersion ${MIN_PHP_VERSION}- .
