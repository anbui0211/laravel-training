# Run the application
run: 
	@php artisan serve --port=8001

# Clear content log file
clear-log:
	@echo "" > storage/logs/laravel.log

# Clearthe routing cache
clear-route-cache:
	@php artisan route:cache