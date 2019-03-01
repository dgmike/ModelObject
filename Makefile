PHPBIN := php
COMPOSER := ${PHPBIN} composer.phar
PHPUNIT := "./vendor/bin/phpunit"

.PHONY: test

composer.phar:
	${PHPBIN} -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
	${PHPBIN} -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
	${PHPBIN} composer-setup.php
	${PHPBIN} -r "unlink('composer-setup.php');"

vendor: composer.phar
	${COMPOSER} install

test: vendor
	${PHPUNIT} --bootstrap vendor/autoload.php tests
