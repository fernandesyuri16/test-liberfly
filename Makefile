run:
	cp ./src/.env.example ./src/.env
	docker compose down
	docker compose build
	docker compose up -d
	docker exec php-hp-api /bin/sh -c "composer install && chmod -R 777 storage && php artisan key:generate && php artisan migrate:fresh --seed"

stop:
	docker compose down