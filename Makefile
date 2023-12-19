build:
	docker-compose build
run:
	docker-compose up -d

initialize:
	docker-compose exec server chmod -R 777 /var/www/storage
	docker-compose exec server chown -R nginx:nginx /var/www/storage
	docker-compose exec app php artisan migrate:fresh --seed
	docker-compose exec app php artisan storage:link

stop:
	docker-compose down

start:
	docker-compose build
	docker-compose up -d
	docker-compose exec server chmod -R 777 /var/www/storage
    docker-compose exec server chown -R nginx:nginx /var/www/storage
    docker-compose exec app php artisan migrate:fresh --seed
    docker-compose exec app php artisan storage:link
