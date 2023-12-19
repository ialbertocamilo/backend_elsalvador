# Utiliza la imagen oficial de PHP 8.1 con FPM (FastCGI Process Manager)
FROM php:8.1-fpm

# Instala las extensiones de PHP necesarias
RUN docker-php-ext-install  mbstring zip exif pcntl

# Instala Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copia los archivos de tu proyecto Laravel al directorio del servidor web
COPY . /var/www/html

# Configura el entorno de producción
ENV APP_ENV=production

# Ejecuta las migraciones y seeders
RUN php artisan migrate --seed

# Optimiza la aplicación (opcional)
RUN php artisan optimize

# Expone el puerto 9000 (puerto por defecto para PHP-FPM)
EXPOSE 9000

# Comando para iniciar el servidor PHP-FPM
CMD ["php-fpm"]
