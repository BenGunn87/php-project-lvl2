install:
	composer install

validate:
	composer validate

lint:
	composer run-script phpcs -- --standard=PSR12 src bin

test:
	vendor/bin/phpunit ./src/Tests