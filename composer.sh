#!/usr/bin/env bash

# Shows usage.
usage () {
  cat <<EOF
${0##*/}
  Provides a wrapper around \`composer install\` that takes into
  account the current environment setting, as well as the presence
  or absence of a composer.json file and a composer executable itself.
Usage:
  ${0##*/}
EOF
  exit 0
}

if [ "$1" = '-h' ]; then
  usage
fi

# Extracts working current directory.
PWD="$(cd -P "$(dirname "$0")" >/dev/null 2>&1 && pwd)"

# Possible binary files directory.
BIN="${PWD}/bin"

# Refers omposer.json which will be handled.
COMPOSER_JSON="${PWD}/composer.json"

# Candidates for composer binary file.
CANDIDATES=("$(which composer)" "${BIN}/composer" "$(which composer.phar)" "${BIN}/composer.phar")

# Checks if config file exists.
if [ ! -e "${COMPOSER_JSON}" ]; then
  echo "'${COMPOSER_JSON}' not found."
exit 2
fi
echo "${COMPOSER_JSON} found."

# Checks if composer isn't available to us.
for CANDIDATE in "${CANDIDATES}"; do
  if [ -x "${CANDIDATE}" ]; then
    COMPOSER="${CANDIDATE}"
    break
  fi
done

if [ -z "$COMPOSER" ]; then
  echo "'composer' not found."
exit 1
fi
echo "${COMPOSER} found."

# Set options based on APP_ENV.
case "$APP_ENV" in
  prod|production)
    OPTIONS="--no-dev" ;;
  stage|staging)
    OPTIONS="--no-dev" ;;
  dev|development)
    OPTIONS="--dev" ;;
  *)
    OPTIONS="--dev"
esac

# Execute `composer install`
"$COMPOSER" install $OPTIONS --no-interaction --ignore-platform-reqs --optimize-autoloader
