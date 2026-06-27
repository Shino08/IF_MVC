FROM php:8.2-apache

# Habilitar el módulo rewrite de Apache para soportar .htaccess
RUN a2enmod rewrite

# Instalar y habilitar la extensión pdo_mysql para la base de datos
RUN docker-php-ext-install pdo_mysql

# Redireccionar el DocumentRoot de Apache a /var/www/html/public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Configurar permisos AllowOverride All para habilitar el .htaccess
RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf
