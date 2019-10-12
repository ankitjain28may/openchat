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
	# Run the websocket server, `-d` means it will run the command in the background
	@docker-compose exec -d app php cmd.php

#
# Stops all services and containers
# Usage: `make down`
#
down:
	@docker-compose down --remove-orphans

#
# Ssh in into the app container
# Usage: `make login`
#
login:
	@docker-compose exec app sh
