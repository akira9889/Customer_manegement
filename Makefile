up:
	docker compose up -d
build:
	docker compose build
env:
	bash make_env.sh
init:
	@make env
	@make build
	@make up
remake:
	@make destroy
	@make init
stop:
	docker compose stop
down:
	docker compose down --remove-orphans
restart:
	@make down
	@make up
destroy:
	docker compose down --rmi all --volumes --remove-orphans
destroy-volumes:
	docker compose down --volumes --remove-orphans
ps:
	docker ps -a --no-trunc
logs:
	docker compose logs
logs-watch:
	docker compose logs --follow
log-app:
	docker compose logs app
log-app-watch:
	docker compose logs --follow app
log-db:
	docker compose logs db
log-db-watch:
	docker compose logs --follow db
php:
	docker compose exec app bash
db:
	docker compose exec db bash
sql:
	docker compose exec db bash -c 'mysql -u root -D customer_manegement -p'
