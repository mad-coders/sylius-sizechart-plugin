phpunit:
	vendor/bin/phpunit

phpspec:
	vendor/bin/phpspec run --ansi --no-interaction -f dot

phpstan:
	vendor/bin/phpstan analyse

psalm:
	vendor/bin/psalm

behat-js:
	APP_ENV=test vendor/bin/behat --colors --strict --no-interaction -vvv -f progress

install:
	composer install --no-interaction --no-scripts

backend:
	tests/Application/bin/console sylius:install --no-interaction
	tests/Application/bin/console sylius:fixtures:load default --no-interaction

frontend:
	(cd tests/Application && yarn install --pure-lockfile)
	(cd tests/Application && GULP_ENV=prod yarn build)

behat:
	APP_ENV=test vendor/bin/behat --colors --strict --no-interaction -vvv -f progress

dropdb:
	tests/Application/bin/console doctrine:database:drop --if-exists --force

createdb:
	tests/Application/bin/console doctrine:database:create --if-not-exists

init: install backend frontend

reinit: dropdb init

ci: init phpstan psalm phpunit phpspec behat

integration: init phpunit behate

static: install phpspec phpstan psalm

serve:
	(cd tests/Application && APP_ENV=dev symfony serve)
