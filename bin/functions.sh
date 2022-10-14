#!/bin/bash
#
#  This file is part of nodcms.
#
#  (c) Mojtaba Khodakhah <info@nodcms.com>
#  https://nodcms.com
#
# For the full copyright and license information, please view
# the LICENSE file that was distributed with this source code.
#
#

print_error_message () {
  printf "\033[0;31m✕ %s\033[0m\n" "$1"
}

print_success_message () {
   printf "\033[0;32m✓ %s\033[0m\n" "$1"
}

print_info_message () {
   printf "\033[0;34m⁛ %s\033[0m\n" "$1"
}

print_divider () {
  echo "-------------------------------------------------------------------------------------------------------"
}

start_step () {
  print_divider
  echo "$(tput bold)Step $1/$2: $3$(tput sgr0)"
}
