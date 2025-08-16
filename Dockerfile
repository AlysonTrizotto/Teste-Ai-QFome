FROM php:8.3-cli-alpine

# Instala dependências do sistema (build e runtime)
RUN apk add --no-cache --virtual .php_build_deps $PHPIZE_DEPS autoconf make g++ && \
    apk add --no-cache \
        bash \
        git \
        linux-headers \
        libzip-dev \
        libxml2-dev \
        icu-dev \
        zlib-dev \
        postgresql-dev \
        openssl-dev \
        pcre-dev \
        brotli-dev \
        nodejs \
        npm && \
    docker-php-ext-configure pcntl --enable-pcntl && \
    docker-php-ext-install \
        bcmath \
        ctype \
        pcntl \
        soap \
        intl \
        sockets \
        zip \
        pdo_pgsql && \
    pecl update-channels && \
    pecl install redis && docker-php-ext-enable redis && \
    pecl install swoole && docker-php-ext-enable swoole && \
    apk del .php_build_deps

# Copia o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define diretório de trabalho e copia o projeto
WORKDIR /app
COPY . .
 
# Ajuste de permissões (para ambientes sem bind mount sobrescrevendo permissões)
RUN mkdir -p storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rw storage bootstrap/cache

# Variáveis úteis ao Composer em ambiente root dentro do container
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_NO_INTERACTION=1

# Instala dependências do Laravel (sem scripts que chamam Artisan) e builda assets
RUN composer install --no-dev --prefer-dist --no-scripts && \
    npm install && npm run build

# Octane Swoole escuta na 8000 por padrão quando configurado
EXPOSE 8000