# Базовый образ PHP с предустановленным Node.js
FROM php:8.2-fpm

# 1. Установка системных зависимостей
RUN apt-get update && \
    apt-get install -y \
        git \
        unzip \
        libpq-dev \
        libzip-dev \
        libpng-dev \
        wget && \
    docker-php-ext-install pdo pdo_pgsql zip gd opcache && \
    rm -rf /var/lib/apt/lists/*

# 2. Установка Node.js 18.x (альтернативный способ)
RUN wget https://nodejs.org/dist/v18.20.2/node-v18.20.2-linux-x64.tar.xz && \
    tar -xJf node-v18.20.2-linux-x64.tar.xz -C /usr/local --strip-components=1 && \
    rm node-v18.20.2-linux-x64.tar.xz && \
    ln -s /usr/local/bin/node /usr/bin/node && \
    ln -s /usr/local/bin/npm /usr/bin/npm

# 3. Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 4. Рабочая директория
WORKDIR /app

# 5. Копируем только файлы зависимостей
COPY composer.json composer.lock package.json package-lock.json ./

# 6. Установка PHP зависимостей
RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs

# 7. Установка фронтенд-зависимостей (с обработкой ошибок)
RUN if [ -f "package.json" ]; then \
        npm install --legacy-peer-deps --force && \
        ([ -f "webpack.mix.js" ] && npm run prod || true); \
    fi

# 8. Копируем остальной код
COPY . .

# 9. Настройка прав
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache && \
    chmod -R 775 /app/storage /app/bootstrap/cache

# 10. Порт для Render
EXPOSE 10000

# 11. Команда запуска
CMD bash -c "php artisan migrate --force && php artisan optimize && php artisan serve --host=0.0.0.0 --port=$PORT"
