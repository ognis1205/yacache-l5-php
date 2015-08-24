#!/usr/bin/env bash

# Shows usage.
usage () {
    cat <<EOF
${0##*/}
  Provides a wrapper around \`phpunit\` that takes into
  account the current environment settings.
Usage:
  ${0##*/}
EOF
    exit 0
}

if [ "$1" = '-h' ]; then
    usage
fi

# Extracts working current directory.
PWD="$(cd -P "$(dirname "${BASH_SOURCE:-$0}")" >/dev/null 2>&1 && pwd)"

# Extracts test directory.
TEST_DIR="${PWD}/../src/tests/"

# Extracts binary directory.
PHPUNIT="${PWD}/../vendor/bin/phpunit"
if [ ! -x "${PHPUNIT}" ]; then
    echo "could not find '${PHPUNIT}'."
    exit 127
fi

# Load miscelenous modules.
LIB_REDIS="${PWD}/redis/server.sh"
source ${LIB_REDIS} 2> /dev/null
if [ $? -ne 0 ]; then
    echo "could not load '${LIB_REDIS}'."
    exit 127
fi

redis_start &
redis_stat
echo "$(cd -P "${TEST_DIR}" >/dev/null 2>&1 && ${PHPUNIT} .)"
redis_stop
