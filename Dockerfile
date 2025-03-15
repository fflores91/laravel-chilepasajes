# Usar la imagen base de Render con NGINX y PHP-FPM
FROM ghcr.io/render-examples/nginx-php-fpm:latest

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar todos los archivos de la aplicación
COPY . .

# Instalar dependencias de Composer
RUN composer install --no-dev --optimize-autoloader

# Ajustar permisos para storage y bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Exponer el puerto 80
EXPOSE 80

# Copiar el script de despliegue a la raíz de la imagen
COPY start.sh /start.sh
RUN chmod +x /start.sh

# El comando de inicio ejecuta el script de despliegue
CMD ["/start.sh"]
