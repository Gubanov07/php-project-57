FROM php:8.4-cli

RUN apt-get update && apt-get install -y libzip-dev libpq-dev
RUN docker-php-ext-install zip pdo pdo_pgsql bcmath

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"
    
RUN curl -sL https://deb.nodesource.com/setup_24.x | bash -
RUN apt-get install -y nodejs

WORKDIR /app

COPY composer.json composer.lock ./
COPY package.json package-lock.json ./

RUN composer install --no-dev --optimize-autoloader
RUN npm ci --no-audit --no-fund

COPY . .

RUN npm run build

RUN > database/database.sqlite

CMD ["bash", "-c", "php artisan migrate:refresh --seed --force && php artisan serve --host=0.0.0.0 --port=$PORT"]