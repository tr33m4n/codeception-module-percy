#!/usr/bin/env bash
set -e

DIR_BASE="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

LOGFILE="$DIR_BASE/server.log"
rm -f $LOGFILE
touch $LOGFILE

$DIR_BASE/stop.sh

printf "\033[92mLaunching PHP server\n\033[0m";

exec -a PHPServer php -S localhost:8081 ./ &> $LOGFILE &

printf "\033[92mPHP server running at http://localhost:8081\n\033[0m";

sleep 2;
printf "\n";

set +e
