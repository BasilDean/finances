# Define a default command for clearing caches
.PHONY: clear-all

# Command to clear all caches and optimize
clear-all:
	@echo "Clearing application cache..."
	php artisan cache:clear

	@echo "Clearing configuration cache..."
	php artisan config:clear
	php artisan config:cache

	@echo "Clearing route cache..."
	php artisan route:clear
	php artisan route:cache

	@echo "Clearing view cache..."
	php artisan view:clear
	php artisan view:cache

	@echo "Clearing event cache..."
	php artisan event:clear
	php artisan event:cache

	@echo "Clearing compiled files..."
	php artisan clear-compiled

	@echo "Restarting queue workers..."
	php artisan queue:restart

	@echo "Optimizing the application..."
	php artisan optimize

	@echo "Caches cleared and application optimized successfully."

dev:
	npm run dev

build:
	npm run build
	make clear-all
