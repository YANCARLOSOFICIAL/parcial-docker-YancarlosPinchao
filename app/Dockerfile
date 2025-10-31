# Usar imagen base de PHP 8.2 con Apache
FROM php:8.2-apache

# Instalar extensiones necesarias para MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite de Apache (opcional, pero útil)
RUN a2enmod rewrite

# Copiar archivos de la aplicación al directorio web de Apache
COPY . /var/www/html/

# Establecer permisos adecuados
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Exponer el puerto 80
EXPOSE 80

# Comando por defecto (Apache en primer plano)
CMD ["apache2-foreground"]
