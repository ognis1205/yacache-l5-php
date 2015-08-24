#!/usr/bin/env bash

# Extracts working current directory.
PWD="$(cd -P "$(dirname "${BASH_SOURCE:-$0}")" >/dev/null 2>&1 && pwd)"

# Load miscelenous modules.
LIB="${PWD}/../lib/lib.sh"
source ${LIB} 2> /dev/null
if [ $? -ne 0 ]; then
    echo "could not load '${LIB}'."
    exit 127
fi

# Candidates for composer binary file.
CANDIDATES=("${PWD}/../redis.conf" "~/.redis/redis.conf")
# Path to the redis.conf
for CANDIDATE in "${CANDIDATES}"; do
  if [ -r "${CANDIDATE}" ]; then
    CONF="${CANDIDATE}"
    break
  fi
done

# Starts up redis server with assigned configuration.
_redis_start() {
    redis-server ${CONF}
    until running redis; do
	sleep 1
    done
}

# Stops redis server.
_redis_stop() {
    kill $(rpid redis)
    while running redis; do
	sleep 1
    done
}

# Starts up redis server.
redis_start() {
    start redis "Redis daemon" _redis_start
}

# Stops redis server.
redis_stop() {
    stop redis "Redis daemon" _redis_stop
}

# Stops redis server.
redis_stat() {
    stop redis "Redis daemon"
}
