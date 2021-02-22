install:
	composer install

validate:
	composer validate

lint:
	composer run-script phpcs -- --standard=PSR12 src bin

test:
	composer exec --verbose phpunit ./src/Tests

test-coverage:
	composer exec --verbose phpunit ./src/Tests -- --coverage-clover build/logs/clover.xml