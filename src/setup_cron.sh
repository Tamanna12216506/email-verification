#!/bin/bash

# Absolute path to PHP (you can check it using `which php`)
PHP_PATH=$(which php)

# Absolute path to cron.php
PROJECT_DIR=$(cd "$(dirname "$0")"; pwd)
CRON_FILE="$PROJECT_DIR/cron.php"

# Cron job line to be added
CRON_JOB="*/5 * * * * $PHP_PATH $CRON_FILE"

# Check if the cron job already exists
(crontab -l 2>/dev/null | grep -v -F "$CRON_JOB" ; echo "$CRON_JOB") | crontab -

echo "✅ CRON job added to run cron.php every 5 minutes."
