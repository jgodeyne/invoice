FROM php:8.2-apache

# Install mysqli
RUN docker-php-ext-install mysqli

# Copy app into webroot
COPY . /var/www/html/

# Add entrypoint script to generate DB config
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint-invoice.sh
RUN chmod +x /usr/local/bin/docker-entrypoint-invoice.sh

ENTRYPOINT ["/usr/local/bin/docker-entrypoint-invoice.sh"]
CMD ["apache2-foreground"]
