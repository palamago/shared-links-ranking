#!/bin/bash
cd ${OPENSHIFT_REPO_DIR} 
php artisan data-checker > ${OPENSHIFT_PHP_LOG_DIR}/"log-`date '+%Y-%m-%d-%H-%M-%S'`.txt"

