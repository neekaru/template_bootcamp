@echo off
echo Starting Laravel Livewire Docker Setup...
echo.

REM Check if .env exists
if not exist .env (
    echo Copying .env.docker to .env...
    copy .env.docker .env
    echo.
)

echo Building and starting Docker containers...
docker-compose up --build -d

echo.
echo Waiting for containers to start...
timeout /t 10 /nobreak > nul

echo Generating application key...
docker-compose exec -T app php artisan key:generate

echo.
echo Creating storage link...
docker-compose exec -T app php artisan storage:link

echo.
echo Setting proper permissions...
docker-compose exec -T app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec -T app chmod -R 755 storage bootstrap/cache

echo.
echo Setup complete!
echo.
echo Your application is available at:
echo - Application: http://localhost
echo - Vite Dev Server: http://localhost:5173
echo.
echo To view logs: docker-compose logs -f
echo To stop: docker-compose down
echo.
pause