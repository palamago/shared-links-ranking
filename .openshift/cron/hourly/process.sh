#!/bin/bash
cd ../../../ 
php artisan data-checker
php artisan make-history
# > ${OPENSHIFT_PHP_LOG_DIR}/"log-`date '+%Y-%m-%d-%H-%M-%S'`.txt"

