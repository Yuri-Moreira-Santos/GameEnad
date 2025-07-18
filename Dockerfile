FROM php:8.2-apache

# Instala extensões necessárias do PHP
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Ativa o módulo de reescrita do Apache
RUN a2enmod rewrite

# Copia os arquivos do projeto
COPY . /var/www/html/

# Define permissões
RUN chown -R www-data:www-data /var/www/html

# Ativa o uso de .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
