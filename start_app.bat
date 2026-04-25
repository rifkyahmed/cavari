@echo off
echo ==========================================
echo   Laravel App Diagnostic & Start Script
echo ==========================================
echo.

echo 1. Checking Database Connectivity...
php check_db_status.php
if %errorlevel% neq 0 (
    echo.
    echo [ERROR] Could not connect to MySQL database!
    echo.
    echo PLEASE ENSURE:
    echo  - XAMPP Control Panel is open.
    echo  - The 'MySQL' module is STARTED (Green).
    echo.
    echo Once you have started MySQL, press any key to try again...
    pause
    goto :Retry
)

:Retry
php check_db_status.php
if %errorlevel% neq 0 (
    echo Still could not connect. Please start MySQL in XAMPP.
    pause
    goto :Retry
)
echo.
echo 2. Running Migrations...
php artisan migrate:fresh --seed
if %errorlevel% neq 0 (
    echo [ERROR] Migration failed. Please check the errors above.
    pause
    exit /b
)

echo.
echo 3. Clearing Caches...
php artisan optimize:clear

echo.
echo 4. Starting Server...
echo    Access the site at: http://127.0.0.1:8000
echo.
php artisan serve
