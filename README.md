# PokemonProject
- Composer globally installed;
- Docker Desktop;
- The Symfony CLI tool;

create a new directory named symfony_docker:
- mkdir symfony_docker
- cd symfony_docker

Because the different containers that compose our application need to communicate, we will use Docker Compose to define them. In the root of the symfony_docker directory, 
create a new file called docker-compose.yml using the command below:
touch docker-compose.yml

IN docker-compose.yml:
'''

version: '3.8'

services:

  database:
    container_name: database
    image: mysql:8.0
    command: --default-authentication-plugin=mysql_native_password
    environment:
      MYSQL_ROOT_PASSWORD: secret
      MYSQL_DATABASE: symfony_docker
      MYSQL_USER: symfony
      MYSQL_PASSWORD: symfony
    ports:
      - '4306:3306'
    volumes:
      - ./mysql:/var/lib/mysql
      
  php:
    container_name: php
    build:
      context: ./php
    ports:
      - '9000:9000'
    volumes:
      - ./app:/var/www/symfony_docker
    depends_on:
      - database
      
  nginx:
    container_name: nginx
    image: nginx:stable-alpine
    ports:
      - '8080:80'
    volumes:
      - ./app:/var/www/symfony_docker
      - ./nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - php
      - database
'''
	  
	  

We will build the PHP container from a Dockerfile.
In the root directory, symfony_docker, create a directory called php. Then, in symfony_docker/php, create a file named Dockerfile.
- mkdir php
- touch php/Dockerfile


In symfony_docker/php/Dockerfile, add:


FROM php:8.0-fpm

RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

WORKDIR /var/www/symfony_docker

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony
RUN git config --global user.email "you@example.com" \ 
    && git config --global user.name "Your Name"
    
    



Create the app directory in the root directory of the project, with the following command:
- mkdir app




We build the Nginx container, in the root of the project:
- mkdir -p nginx/default.conf



Add the configuration below to nginx/default.conf:

server {

    listen 80;
    index index.php;
    server_name localhost;
    root /var/www/symfony_docker/public;
    error_log /var/log/nginx/project_error.log;
    access_log /var/log/nginx/project_access.log;
    
    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ ^/index\\.php(/|$) {
        fastcgi_pass php:9000;
        fastcgi_split_path_info ^(.+\\.php)(/.*)$;
        include fastcgi_params;

        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;

        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
        fastcgi_busy_buffers_size 256k;

        internal;
    }
    location ~ \\.php$ {
        return 404;
    }
    
}




Build containers:
- docker-compose up -d --build


In PHP CONTAINER CLI, let's add some development dependencies for the application, 
for example Twig template engine will be required to render the front end:
- composer req doctrine twig



Create a .env.local file from the existing .env file which Symfony generated during the creation of the project:
- cp .env .env.local

Finally, update the database parameters in .env.local to allow the application to connect to database container.
Replace the current DATABASE_URL entry in the file with the version below:
- DATABASE_URL="mysql://root:secret@database:3306/symfony_docker?serverVersion=8.0"
