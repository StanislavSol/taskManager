# Базовый образ PHP
FROM php:8.2-fpm

# Установка системных зависимостей (в ОДНОЙ команде RUN)
RUN apt-get update && \
    apt-get install -y \
        git \
        unzip \
        libpq-dev \
        libzip-dev \
        libpng-dev \
        npm && \
    docker-php-ext-install pdo pdo_pgsql zip gd opcache && \
    rm -rf /var/lib/apt/lists/* && \
    npm install -g n && \
    n stable && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Рабочая директория
WORKDIR /app

# Копируем только файлы, необходимые для установки зависимостей
COPY composer.json composer.lock package.json package-lock.json ./

# Установка зависимостей
RUN composer install --no-dev --optimize-autoloader --no-scripts && \
    npm install && \
    npm run prod

# Копируем весь остальной код
COPY . .

# Настройка прав (важно для Render)
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache && \
    chmod -R 775 /app/storage /app/bootstrap/cache

# Порт для Render
EXPOSE 10000

# Команда запуска
CMD bash -c "php artisan migrate --force && php artisan optimize && php artisan serve --host=0.0.0.0 --port=$PORT"
