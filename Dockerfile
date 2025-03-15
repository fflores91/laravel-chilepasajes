# Usar la imagen oficial de PHP 8.1
FROM php:8.1-fpm

# Instalar dependencias del sistema y herramientas necesarias
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Rempver la configuración por defecto de NGINX
RUN rm -f /etc/nginx/conf.d/default.conf

# Copiar la configuración personalizada de NGINX desde el directorio docker/
COPY docker/nginx.conf /etc/nginx/conf.d/

# Copiar la configuración de supervisor desde el directorio docker/
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar los archivos de la aplicación al contenedor
COPY . /var/www/html

# Instalar las dependencias de Composer sin dependencias de desarrollo y optimiza el autoloader
RUN composer install --no-dev --optimize-autoloader

# Ajustar los permisos necesarios para Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Exponer el puerto 80 para HTTP
EXPOSE 80

# Iniciar supervisor para gestionar NGINX y PHP-FPM
CMD ["/usr/bin/supervisord", "-n"]
