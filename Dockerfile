FROM php:8.2-fpm-bookworm

ARG WWWUSER=1000
ARG WWWGROUP=1000

ENV DEBIAN_FRONTEND=noninteractive \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_NO_INTERACTION=1 \
    COMPOSER_MEMORY_LIMIT=-1

# System packages
RUN apt-get update && apt-get install -y --no-install-recommends \
        git curl unzip ca-certificates gnupg \
        libicu-dev libzip-dev libonig-dev libxml2-dev \
        libpng-dev libjpeg62-turbo-dev libfreetype6-dev libwebp-dev \
        libcurl4-openssl-dev libssl-dev pkg-config \
        default-mysql-client \
        supervisor cron \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql mysqli mbstring intl zip gd bcmath opcache exif pcntl sockets \
    && pecl install redis && docker-php-ext-enable redis

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node 20 + npm (Filament asset compilation)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && rm -rf /var/lib/apt/lists/*

# PHP ini tuning — match SPEC.md §13 (50 MB uploads, generous memory for migrations)
RUN { \
        echo "memory_limit = 512M"; \
        echo "upload_max_filesize = 64M"; \
        echo "post_max_size = 64M"; \
        echo "max_execution_time = 300"; \
        echo "date.timezone = UTC"; \
        echo "opcache.enable = 1"; \
        echo "opcache.enable_cli = 0"; \
        echo "opcache.validate_timestamps = 1"; \
        echo "opcache.revalidate_freq = 0"; \
    } > /usr/local/etc/php/conf.d/zz-quizapp.ini

# Match host UID so volume-mounted files stay user-owned
RUN groupadd --force -g ${WWWGROUP} quizapp \
    && useradd -ms /bin/bash --no-user-group -g ${WWWGROUP} -u ${WWWUSER} quizapp \
    || true

WORKDIR /var/www/html

# Default to running php-fpm; docker-compose overrides per service
EXPOSE 9000
CMD ["php-fpm"]
