docker-up:
	docker-compose up

docker-down:
	docker-compose down

docker-stop:
	docker-compose stop

docker-build:
	docker-compose up --build

test:
	docker-compose exec php-cli vendor/bin/phpunit