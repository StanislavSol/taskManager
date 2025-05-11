# Стадия сборки
FROM node:18 as frontend-builder

WORKDIR /app
COPY package.json webpack.mix.js ./
COPY resources/js ./resources/js
COPY resources/css ./resources/css

RUN npm install && npm run prod

# Стадия PHP
FROM php:8.2-fpm

# Установка системных зависимостей
RUN apt-get update && \
    apt-get install -y \
        git \
        unzip \
        libpq-dev \
        libzip-dev \
        libpng-dev \
        npm && \
    docker-php-ext-install pdo pdo_pgsql zip gd opcache && \
    rm -rf /var/lib/apt/lists/*

# Установка Composer
COPY --from=composer:2.6 /usr/local/bin/composer /usr/local/bin/composer

# Рабочая директория
WORKDIR /app

# Копируем зависимости отдельно для кэширования
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Копируем весь код
COPY . .

# Копируем собранные фронтенд-ассеты
COPY --from=frontend-builder /app/public/js /app/public/js
COPY --from=frontend-builder /app/public/css /app/public/css
COPY --from=frontend-builder /app/mix-manifest.json /app/mix-manifest.json

# Настройка прав (для Render)
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache && \
    chmod -R 775 /app/storage /app/bootstrap/cache

# Порт для Render
EXPOSE 10000

# Команда запуска (миграции + оптимизация + сервер)
CMD bash -c " \
    php artisan migrate --force && \
    php artisan optimize && \
    php artisan serve --host=0.0.0.0 --port=$PORT"
