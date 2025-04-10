FROM php:8.0-apache

# Installer les dépendances nécessaires pour PDO et MySQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_mysql

# Activer l'extension pdo_mysql
RUN docker-php-ext-enable pdo_mysql

# Copier le code de l'application
COPY . /var/www/html/

# Exposer le port Apache (par défaut c'est le 80)
EXPOSE 80
