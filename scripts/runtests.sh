#!/usr/bin/bash
# Run phpunit tests
# 
# - cd to the top level dir of the checked out code
# - scripts/runtests.sh gesundheit
#   - or
# - scripts/runtests.sh gesundheit_test # if you have created a test database
#

function usage {
	echo "usage: $0 mongodb_name [env_filepath]"
	echo " ... assumes it runs from the top-level dir"
	echo " ... env_filepath defaults to './.env'"
}

if [ "$#" -lt 1 ]; then
	usage
	exit 2
fi

if [ "$1" == "-h" ]; then
	usage
	exit 0
fi

GESUNDHEIT_ENV_FILEPATH=${2:-.env}
GESUNDHEIT_ENV_DIR=`dirname $GESUNDHEIT_ENV_FILEPATH`
GESUNDHEIT_ENV_FILE=`basename $GESUNDHEIT_ENV_FILEPATH`
export GESUNDHEIT_ENV_DIR GESUNDHEIT_ENV_FILE

XML_OUTPUT="./test_log/phpunit.junit.$(date +%Y%m%d.%H%M%S).xml"

./vendor/bin/phpunit --display-incomplete --display-warnings \
--display-deprecations --display-phpunit-deprecations \
--bootstrap ./autoload_testsuite.php --log-junit ${XML_OUTPUT} \
test
