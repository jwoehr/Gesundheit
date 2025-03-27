#!/usr/bin/bash
# Run phpunit tests
# 
# - cd to the top level dir of the checked out code
# - scripts/runtests.sh gesundheit
#   - or
# - scripts/runtests.sh gesundheit_test # if you have created a test database
#

function usage {
	echo "usage: $0 mongodb_name"
	echo " ... assumes it runs from the top-level dir"
	echo " ... assumes you have a valid .env file"
}

if [ "$#" -lt 1 ]; then
	usage
	exit 2
fi

if [ "$1" == "-h" ]; then
	usage
	exit 0
fi

XML_OUTPUT="./test_log/phpunit.junit.$(date +%Y%m%d.%H%M%S).xml"

./vendor/bin/phpunit --display-incomplete --display-warnings \
--display-deprecations --display-phpunit-deprecations \
--bootstrap ./autoload_testsuite.php --log-junit ${XML_OUTPUT} \
test
