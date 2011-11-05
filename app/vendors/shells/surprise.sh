#!/bin/sh
PHP_PATH=/usr/bin/php
BASE_PATH=

${PHP_PATH} ${BASE_PATH}/cake/console/cake.php surprise candy -app ${BASE_PATH}/app
${PHP_PATH} ${BASE_PATH}/cake/console/cake.php surprise duke -app ${BASE_PATH}/app
