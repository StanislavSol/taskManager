# Используем официальный образ PHP с FPM
FROM php:8.2-fpm

# Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
    nginx \
    supervisor \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# Установка зависимостей Node.js
RUN npm install -g yarn

# Рабочая директория
WORKDIR /var/www/html

# Копирование файлов
COPY . .

# Установка PHP зависимостей
RUN composer install --optimize-autoloader --no-dev

# Установка фронтенд зависимостей и сборка
RUN npm install && npm run build

# Копирование конфигураций
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Настройка прав
RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

# Объявление порта
EXPOSE 80

# Команда запуска
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
