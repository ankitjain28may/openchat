# ======================================================================
# OpenChat Docker Commands
# ======================================================================

#
# Starts everything necesary to run the application
# Usage: `make up`
#
up:
	# Start app network, volumes and services
	@docker-compose up -d
	# Generate app .env file
	@docker-compose exec app php -r "file_exists('.env') || copy('.env.example', '.env');"

#
# Stops all services and containers
# Usage: `make down`
#
down:
	@docker-compose down --remove-orphans

#
# Show logs from app container in real time
# Usage: `make logs`
#
logs:
	@docker-compose logs -f app

#
# Ssh in into the app container
# Usage: `make login`
#
login:
	@docker-compose exec app sh
