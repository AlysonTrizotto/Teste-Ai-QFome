FROM php:8.3-cli-alpine

# Crie um usuário e grupo não-root primeiro
RUN addgroup -S appgroup && adduser -S appuser -G appgroup

# Instala dependências de sistema (runtime)
RUN apk add --no-cache \
    libstdc++ \
    brotli-dev \
    bash \
    git \
    linux-headers \
    libzip-dev \
    libxml2-dev \
    icu-dev \
    zlib-dev \
    pcre-dev \
    mariadb-dev \
    mysql-client \
    supervisor \
    nodejs \
    npm \
    netcat-openbsd # Para o entrypoint

# Instala dependências de build temporárias
RUN apk add --no-cache --virtual .php_build_deps $PHPIZE_DEPS autoconf

# Instala extensões PHP
RUN docker-php-ext-configure pcntl --enable-pcntl && \
    docker-php-ext-install \
        bcmath \
        ctype \
        pcntl \
        soap \
        intl \
        sockets \
        zip \
        pdo_mysql

RUN apk add --no-cache \
        libjpeg-turbo-dev \
        libpng-dev \
        freetype-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-enable gd

# Instala Swoole via PECL
RUN pecl install swoole && docker-php-ext-enable swoole

# Instala Redis 
RUN pecl install redis && docker-php-ext-enable redis

# Remove dependências de build
RUN apk del .php_build_deps

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copia o código da aplicação com permissões para o novo usuário
COPY --chown=appuser:appgroup . .

# Garante que o diretório /app em si seja propriedade do appuser para que ele possa
# criar subdiretórios como node_modules.
RUN chown appuser:appgroup /app

# Muda para o usuário não-root para os próximos comandos
USER appuser

# Instala dependências do Laravel como appuser
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Compila assets frontend como appuser
RUN npm install && npm run build

# Volta para root para configurar permissões de sistema e arquivos de log do supervisor
USER root

# Cria diretórios de log e storage e define permissões
# Garanta que /tmp seja gravável pelo usuário do supervisor se o socket estiver lá.
RUN mkdir -p /var/log/supervisor /app/storage/logs \
    /app/storage/framework/sessions /app/storage/framework/views \
    /app/storage/framework/cache && \
    chown -R appuser:appgroup /app/storage /app/bootstrap/cache /var/log/supervisor && \
    chmod -R 775 /app/storage /app/bootstrap/cache

# Copia arquivos de supervisão
COPY scripts/supervisord/supervisord.conf /etc/supervisord.conf
COPY scripts/supervisord/supervisord-laravel.conf /etc/supervisor/conf.d/supervisord-laravel.conf

# Torna o entrypoint executável
COPY --chown=appuser:appgroup scripts/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8030

# O entrypoint.sh executará o supervisord.
# O supervisord deve rodar como root para poder gerenciar processos e mudar de usuário.
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]