#!/bin/sh
PHP_PATH=/usr/bin/php
BASE_PATH=

${PHP_PATH} ${BASE_PATH}/cake/console/cake.php test_create_card candy -app ${BASE_PATH}/app
${PHP_PATH} ${BASE_PATH}/cake/console/cake.php test_create_card duke -app ${BASE_PATH}/app
