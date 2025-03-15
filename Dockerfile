# Imagen pública
FROM richarvey/nginx-php-fpm:8.1

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos de Composer primero para aprovechar el cacheo de capas
COPY composer.lock composer.json /var/www/html/

# Instala las dependencias de Composer sin dependencias de desarrollo y optimiza el autoloader
RUN composer install --no-dev --optimize-autoloader

# Copia el resto del código de la aplicación
COPY . /var/www/html

# Ajusta los permisos para los directorios que Laravel requiere (storage y bootstrap/cache)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expone el puerto 80
EXPOSE 80

# Inicia el supervisor (la imagen richarvey usa supervisor para ejecutar tanto NGINX como PHP-FPM)
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
