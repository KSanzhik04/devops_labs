FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    openssh-server \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

WORKDIR /var/www/html

COPY composer.json .

RUN composer install --no-interaction --no-scripts --no-autoloader
RUN composer dump-autoload --optimize

COPY . .

RUN echo 'PermitRootLogin yes' >> /etc/ssh/sshd_config
RUN echo 'PasswordAuthentication yes' >> /etc/ssh/sshd_config
RUN echo 'root:root' | chpasswd
EXPOSE 22
CMD service ssh start && apache2-foreground