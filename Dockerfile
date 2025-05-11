# Базовый образ
FROM php:8.2-fpm

# 1. Установка системных зависимостей
RUN apt-get update && \
    apt-get install -y \
        git \
        unzip \
        libpq-dev \
        libzip-dev \
        libpng-dev \
        nodejs \
        npm && \
    docker-php-ext-install pdo pdo_pgsql zip gd opcache && \
    rm -rf /var/lib/apt/lists/* && \
    npm install -g n && \
    n 16.20.2 && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 2. Рабочая директория
WORKDIR /app

# 3. Копируем ТОЛЬКО файлы зависимостей
COPY composer.json composer.lock package.json package-lock.json ./

# 4. Установка PHP зависимостей
RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs

# 5. Установка Node.js зависимостей
RUN npm install --force && \
    npm rebuild node-sass && \
    npm run prod

# 6. Копируем остальной код
COPY . .

# 7. Настройка прав
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache && \
    chmod -R 775 /app/storage /app/bootstrap/cache

# 8. Порт для Render
EXPOSE 10000

# 9. Команда запуска
CMD bash -c "php artisan migrate --force && php artisan optimize && php artisan serve --host=0.0.0.0 --port=$PORT"
