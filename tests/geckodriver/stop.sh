#!/usr/bin/env bash
set -e

if pgrep geckodriver; then
    printf "\033[92mKilling running geckodriver\n\033[0m";
    pkill -f geckodriver;
fi

printf "\n";

set +e
