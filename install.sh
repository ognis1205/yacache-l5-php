#!/usr/bin/env bash

# For the case of the pipelining fails
set -ef -o pipefail

# Shows usage.
usage () {
  cat <<EOF
${0##*/}
  This scripts requires installing 'pecl', 'git' and 'phpize'. Please be sure
  to implement these commands and/or setting your environment.
Usage:
  ${0##*/}
EOF
  exit 0
}

if [ "$1" = '-h' ]; then
  usage
fi

# Install native Msgpack inplementation for PHP.
echo "installing msgpack..."
pecl install msgpack

# Load PHP module.
phpenv config-add msgpack.ini

composer self-update
