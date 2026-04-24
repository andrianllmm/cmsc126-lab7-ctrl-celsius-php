FROM php:8.2-apache

# Enable rewrite (for routing if needed)
RUN a2enmod rewrite

# Copy files
COPY . /var/www/html/

# Set correct public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf
