#!/usr/bin/env bash
set -e

DIR_BASE="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

LOGFILE="$DIR_BASE/geckodriver.log"
rm -f $LOGFILE
touch $LOGFILE

if [[ "$OSTYPE" == "darwin"* ]]; then
    printf "\033[92mLaunching geckodriver OSX\n\033[0m";
    $DIR_BASE/osx/geckodriver --version;
    $DIR_BASE/osx/geckodriver &> $LOGFILE &
else
    printf "\033[92mLaunching geckodriver Linux\n\033[0m";
    $DIR_BASE/linux/geckodriver --version;
    $DIR_BASE/linux/geckodriver &> $LOGFILE &
fi

sleep 2;
printf "\n";

set +e
