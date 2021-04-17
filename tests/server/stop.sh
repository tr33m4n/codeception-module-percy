#!/usr/bin/env bash
set -e

if pgrep PHPServer; then
    printf "\033[92mKilling running PHP server\n\033[0m";
    pkill -f PHPServer;
fi

printf "\n";

set +e
exit 0;
