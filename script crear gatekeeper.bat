@echo off
setlocal

:: Paso 1: Crear el proyecto Laravel
echo Creando proyecto Laravel...
- composer create-project --prefer-dist laravel/laravel JetWarehouse
call :check_error

:: Definir la variable de ruta del proyecto
set PROYECTO_DIR=D:\Catalogo WEEZ\JetWarehouse

:: Cambiar al directorio del proyecto
- cd /d %PROYECTO_DIR%

:: Función para manejar errores
:check_error
if %ERRORLEVEL% neq 0 (
    echo Error ocurrido en el paso anterior. Codigo de error: %ERRORLEVEL%
    set /p CONTINUAR="¿Deseas continuar? (S/N): "
    if /i "%CONTINUAR%"=="N" exit /b
)
:: Paso 2: Instalar Jetstream
echo Instalando Jetstream...
- composer require laravel/jetstream
call :check_error
- php artisan jetstream:install livewire
call :check_error

:: Paso 3: Instalar dependencias de npm y compilar los assets
echo Instalando dependencias npm y compilando...
- npm install
call :check_error
- npm run dev
call :check_error

:: Paso 4: Ejecutar migraciones
echo Ejecutando migraciones...
- php artisan migrate
call :check_error

:: Paso 5: Instalar Spatie Laravel Permission
echo Instalando Spatie Laravel Permission...
- composer require spatie/laravel-permission
- php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
call :check_error
- php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"
call :check_error
- php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="config"
call :check_error
- php artisan migrate
call :check_error

:: Paso 6: Copiar archivos y directorios de "Archivos a instalar" al proyecto
echo Copiando archivos de "Archivos a instalar"...
- xcopy /E /I /H /Y "D:\Catalogo WEEZ\Archivos a instalar\app" "%PROYECTO_DIR%\app"
:: xcopy /E /I /H /Y "D:\Catalogo WEEZ\Archivos a instalar\app" "D:\Catalogo WEEZ\JetWarehouse\app"
call :check_error
xcopy /E /I /H /Y "D:\Catalogo WEEZ\Archivos a instalar\bootstrap" "%PROYECTO_DIR%\bootstrap"
:: xcopy /E /I /H /Y "D:\Catalogo WEEZ\Archivos a instalar\bootstrap" "D:\Catalogo WEEZ\JetWarehouse\bootstrap"
call :check_error
xcopy /E /I /H /Y "D:\Catalogo WEEZ\Archivos a instalar\routes" "%PROYECTO_DIR%\routes"
:: xcopy /E /I /H /Y "D:\Catalogo WEEZ\Archivos a instalar\routes" "D:\Catalogo WEEZ\JetWarehouse\routes"
call :check_error
xcopy /E /I /H /Y "D:\Catalogo WEEZ\Archivos a instalar\seeders" "%PROYECTO_DIR%\database\seeders"
:: xcopy /E /I /H /Y "D:\Catalogo WEEZ\Archivos a instalar\seeders" "D:\Catalogo WEEZ\JetWarehouse\database\seeders"
- php artisan migrate
- php artisan db:seed --class=RoleSeeder

call :check_error
xcopy /E /I /H /Y "D:\Catalogo WEEZ\Archivos a instalar\views" "%PROYECTO_DIR%\resources\views"
:: xcopy /E /I /H /Y "D:\Catalogo WEEZ\Archivos a instalar\views" "D:\Catalogo WEEZ\JetWarehouse\resources\views"
call :check_error

:: Paso 7: Finalizar
echo Proyecto configurado correctamente.
pause
endlocal
