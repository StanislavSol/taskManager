# Стадия сборки фронтенда (только если есть webpack.mix.js)
FROM node:18 as frontend-builder

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install

# Копируем только если файл существует
COPY resources/js ./resources/js
COPY resources/css ./resources/css

# Проверяем наличие webpack.mix.js перед сборкой
RUN if [ -f "webpack.mix.js" ]; then \
      cp .env.example .env && \
      npm run prod; \
    else \
      echo "webpack.mix.js not found, skipping frontend build"; \
    fi

# Основная стадия
FROM php:8.2-fpm

# Установка зависимостей
RUN apt-get update && \
    apt-get install -y \
        git \
        unzip \
        libpq-dev \
        libzip-dev \
        libpng-dev && \
    docker-php-ext-install pdo pdo_pgsql zip gd opcache && \
    rm -rf /var/lib/apt/lists/*

# Установка Composer
COPY --from=composer:2.6 /usr/local/bin/composer /usr/local/bin/composer

WORKDIR /app
COPY . .

# Установка PHP зависимостей
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Копируем ассеты только если они были собраны
COPY --from=frontend-builder /app/public/js /app/public/js 2>/dev/null || :
COPY --from=frontend-builder /app/public/css /app/public/css 2>/dev/null || :
COPY --from=frontend-builder /app/mix-manifest.json /app/mix-manifest.json 2>/dev/null || :

# Настройка прав
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache && \
    chmod -R 775 /app/storage /app/bootstrap/cache

EXPOSE 10000

CMD bash -c "php artisan migrate --force && php artisan optimize && php artisan serve --host=0.0.0.0 --port=$PORT"
