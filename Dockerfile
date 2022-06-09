FROM aro.jfrog.io/performance-app/php:8

RUN apt-get update -y && apt -y upgrade && apt-get install -y openssl zip unzip git \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libfreetype6 \
    libc6 \
    libgd3 \
    libjpeg62-turbo \
    libpng16-16 \
    libwebp6 \
    libx11-6 \
    libxpm4 \
    ucf \
    zlib1g \
    sudo \
    wget  \
    vim \
    cron

RUN apt install ca-certificates apt-transport-https wget gnupg -y
RUN wget -q https://packages.sury.org/php/apt.gpg -O- | apt-key add -
RUN echo "deb https://packages.sury.org/php/ buster main" | tee /etc/apt/sources.list.d/php.list

RUN sudo apt-get update
RUN apt list|grep php7.3-gd
#RUN apt-get install php7.3-gd/stable -y
RUN apt-get install php7.3-gd/buster -y
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo pdo_mysql mbstring 
WORKDIR /app
COPY . /app

# #RUN cat /app/crontab.txt >> /etc/crontab
# COPY /crontab.txt /etc/cron.d/laravel-scheduler-cron
# RUN chmod 0644 /etc/cron.d/laravel-scheduler-cron

# RUN crontab -u 1001 /etc/cron.d/laravel-scheduler-cron && \
#     chmod u+s /usr/sbin/cron
# COPY --chown=1001:1001 . .


# RUN apt-get install -y supervisor
# COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf


RUN composer update --ignore-platform-reqs

RUN composer require kalnoy/nestedset --ignore-platform-reqs
RUN composer require doctrine/dbal --ignore-platform-reqs
RUN composer require awobaz/compoships --ignore-platform-reqs

RUN php artisan config:clear

EXPOSE 8000

RUN chgrp -R 0 /app && \
    chmod -R g=u /app
USER 1001




#CMD ["sh","-c","/etc/init.d/cron start && php artisan serve --host=0.0.0.0 --port=8000"]
CMD php artisan serve --host=0.0.0.0 --port=8000

# CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
