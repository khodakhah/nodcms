#!/bin/bash
. ./bin/functions.sh

VERBOSE=0
VERBOSE_INFO="Try $(tput bold)'composer checkout -- -v'$(tput sgr0) for more information"
ERROR_OCCURRED=0

if [[ $1 == "-v" ]]; then
  VERBOSE=1
  VERBOSE_INFO=""
fi

start_step 1 3 "Syncing .env from .env development"

if [[ $VERBOSE -ne 0 ]]; then
  cp .env.development .env
else
  cp .env.development .env &> /dev/null
fi

BACK_PID=$!
wait $BACK_PID

if [ $? -ne 0 ]; then
  print_error_message "Syncing .env from .env development was failed"
  ERROR_OCCURRED=1
else
  print_success_message "Syncing .env from .env.local was successful"
fi

start_step 2 3 "Installing composer dependencies"

if [[ $VERBOSE -ne 0 ]]; then
  composer install
else
  composer install &> /dev/null
fi

BACK_PID=$!
wait $BACK_PID

if [ $? -ne 0 ]; then
  print_error_message "Installing composer dependencies was failed"
  ERROR_OCCURRED=1
else
  print_success_message "Installing composer dependencies was successful"
fi

start_step 3 3 "Syncing pre-commit hook"

if [[ $VERBOSE -ne 0 ]]; then
  cp bin/git/pre-commit .git/hooks/pre-commit
  chmod 0755 .git/hooks/pre-commit
else
  cp bin/git/pre-commit .git/hooks/pre-commit &> /dev/null
  chmod 0755 .git/hooks/pre-commit &> /dev/null
fi

BACK_PID=$!
wait $BACK_PID

if [ $? -ne 0 ]; then
  print_error_message "Syncing pre-commit hook failed"
  ERROR_OCCURRED=1
else
  print_success_message "Syncing pre-commit hook was successful"
fi

print_divider

if [ $ERROR_OCCURRED -ne 0 ]; then
  print_error_message "Checkout failed"
  echo $VERBOSE_INFO
else
  print_success_message "Checkout successful"
fi


