ARG BUILD_FROM=ghcr.io/hassio-addons/base:15.0.8
FROM ${BUILD_FROM}

# Set shell
SHELL ["/bin/bash", "-o", "pipefail", "-c"]

WORKDIR /laravel
# Install PHP and extensions
RUN apk add --no-cache \
    php82 \
    php82-curl \
    php82-phar \
    php82-fpm \
    php82-gd \
    php82-iconv \
    php82-mbstring \
    php82-sqlite3 \
    php82-opcache \
    php82-openssl \
    php82-session \
    php82-xml \
    php82-zip \
    php82-json \
    php82-fileinfo \
    php82-tokenizer \
    php82-ctype \
    php82-dom \
    php82-xmlwriter \
    php82-xmlreader \
    php82-pdo \
    php82-pdo_sqlite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Copy laravel files (zipped)
# COPY laravel.zip /
COPY . .
# unzip
# RUN unzip /laravel.zip -d /
# Remove the zip file
# RUN rm /laravel.zip
# Copy and replace the .env file
COPY .env.example .env


# Remove temporary files
RUN rm -f -r /tmp/*
# remove storage link
RUN rm -f -r /laravel/public/storage
# Create SQLite database file
RUN touch database.sqlite

# Install Laravel dependencies
# RUN composer install
RUN composer du

# Run Laravel migrations and clear caches
RUN php artisan migrate
RUN php artisan db:seed
RUN php artisan cache:clear 
RUN php artisan config:clear 
RUN php artisan route:clear 
RUN php artisan view:clear 
RUN php artisan storage:link 
RUN php artisan config:cache 
RUN php artisan view:cache 
RUN php artisan route:cache

# Expose port 8000 for the artisan server
EXPOSE 8000

# Start Laravel's built-in server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
# Build arguments
ARG BUILD_ARCH
ARG BUILD_DATE
ARG BUILD_DESCRIPTION
ARG BUILD_NAME
ARG BUILD_REF
ARG BUILD_REPOSITORY
ARG BUILD_VERSION

# Labels
LABEL \
    io.hass.name="${BUILD_NAME}" \
    io.hass.description="${BUILD_DESCRIPTION}" \
    io.hass.arch="${BUILD_ARCH}" \
    io.hass.type="addon" \
    io.hass.version=${BUILD_VERSION} \
    maintainer="Franck Nijhof <frenck@addons.community>" \
    org.opencontainers.image.title="${BUILD_NAME}" \
    org.opencontainers.image.description="${BUILD_DESCRIPTION}" \
    org.opencontainers.image.vendor="Home Assistant Community Add-ons" \
    org.opencontainers.image.authors="Franck Nijhof <frenck@addons.community>" \
    org.opencontainers.image.licenses="MIT" \
    org.opencontainers.image.url="https://addons.community" \
    org.opencontainers.image.source="https://github.com/${BUILD_REPOSITORY}" \
    org.opencontainers.image.documentation="https://github.com/${BUILD_REPOSITORY}/blob/main/README.md" \
    org.opencontainers.image.created=${BUILD_DATE} \
    org.opencontainers.image.revision=${BUILD_REF} \
    org.opencontainers.image.version=${BUILD_VERSION}