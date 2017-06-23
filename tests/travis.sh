#!/bin/bash

PHP_BINARY="php"

while getopts "p:" OPTION 2> /dev/null; do
	case ${OPTION} in
		p)
			PHP_BINARY="$OPTARG"
			;;
	esac
done

./tests/lint.sh -p "$PHP_BINARY"

if [ $? -ne 0 ]; then
	echo Lint scan failed!
	exit 1
fi

rm server.log 2> /dev/null
mkdir -p ./plugins

echo -e "version\nmakeserver\nstop\n" | "$PHP_BINARY" src/pocketmine/PocketMine.php --no-wizard --disable-ansi --disable-readline --debug.level=2
if ls plugins/Leveryl/Leveryl*.phar >/dev/null 2>&1; then
    echo Server phar created successfully.
else
    echo No phar created!
    exit 1
fi

cp -r tests/LeverylCITester ./plugins
"$PHP_BINARY" src/pocketmine/PocketMine.php --no-wizard --disable-ansi --disable-readline --debug.level=2
result=$(grep 'LeverylCITester' server.log | grep 'Finished' | grep -v 'PASS')
if [ "$result" != "" ]; then
   echo "$result"
   echo Some tests did not complete successfully, changing build status to failed
   exit 1
else
    echo All tests passed
fi
