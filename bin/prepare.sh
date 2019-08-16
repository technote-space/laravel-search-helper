#!/usr/bin/env bash

set -e

current=$(
  # shellcheck disable=SC2046
  cd $(dirname "$0")
  pwd
)
# shellcheck disable=SC1090
source "${current}"/variables.sh

if [[ "${TRAVIS_BUILD_STAGE_NAME}" == "Deploy" ]]; then
  echo ""
  echo ">> Build"
  composer install --no-dev --no-interaction --prefer-dist --no-suggest
else
  echo ""
  echo ">> Setup"
  composer install --no-interaction --prefer-dist --no-suggest
fi
