FROM php:8.1-apache

# Install system libraries required for PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libzip-dev \
    zlib1g-dev \
    libonig-dev \
    unzip \
    git \
    curl \
    libssl-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli zip mbstring

# Install MongoDB PHP extension
RUN pecl install mongodb && \
    docker-php-ext-enable mongodb

# Install Redis PHP extension
RUN pecl install redis && \
    docker-php-ext-enable redis

# Update CA certificates to fix SSL issues
RUN apt-get update && apt-get install -y ca-certificates && update-ca-certificates

# Install Composer with SSL verification
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    chmod +x /usr/local/bin/composer

# Configure Composer to handle SSL issues
RUN composer config --global disable-tls false && \
    composer config --global secure-http true

# Enable Apache mod_rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copy the PHP application and vendor dependencies
COPY app/ ./

# Copy standalone HTML entry points living at the repo root
COPY *.html ./

# Only run composer install if vendor directory is missing or incomplete
# Since vendor already exists in the app directory, this should be skipped
RUN if [ ! -d "vendor" ] || [ ! -f "vendor/autoload.php" ]; then \
        echo "Installing dependencies with composer..."; \
        composer install --no-interaction --no-scripts --prefer-dist || \
        (echo "Retrying composer install..." && composer clear-cache && composer install --no-interaction --no-scripts --prefer-dist); \
    else \
        echo "Vendor directory exists, skipping composer install"; \
    fi

EXPOSE 80
