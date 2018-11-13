FROM debian:jessie

# update the package sources, clamd
RUN apt-get update --fix-missing \
    && apt-get install -y \
    clamdscan \
    libclamav-dev \
    curl wget git htop supervisor vim openssh-server software-properties-common netcat

COPY ../php7.1/linode.list /etc/apt/sources.list.d/
COPY ../docker-utils/nginx/nginx.list /etc/apt/sources.list.d/

# Add Nginx pubkey, update and run --fix-missing for broken repos (just in case)
RUN wget http://nginx.org/keys/nginx_signing.key \
    && apt-key add nginx_signing.key \
    && apt-key update \
    && apt-get update --fix-missing

# Install all the PHP dependencies
RUN apt-get install -y \
    mysql-client \
    php7.1 \
    php7.1-cli \
    php7.1-common \
    php7.1-gd \
    php7.1-mcrypt \
    php7.1-fpm \
    php7.1-curl \
    php7.1-memcached \
    php7.1-xdebug \
    php7.1-xhprof \
    php7.1-mysql \
    php-pear \
    apache2 \
    nginx \
    libapache2-mod-fastcgi \
    php7.1-dev \
    rsyslog

# Install php clamav
#RUN wget http://downloads.sourceforge.net/project/php-clamav/0.15/php-clamav_0.15.8.tar.gz && tar -xzvf php-clamav_0.15.8.tar.gz
#RUN cd php-clamav-0.15.8/ && phpize && ./configure -with-clamav && make && cp modules/clamav.so /usr/lib/php5/20131226/
#RUN freshclam

# Install Blackfire
RUN version=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;") \
    && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s https://blackfire.io/api/v1/releases/probe/php/linux/amd64/$version \
    && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp \
    && mv /tmp/blackfire-*.so $(php -r "echo ini_get('extension_dir');")/blackfire.so

COPY ../php7.1/blackfire.ini /etc/php7.1/fpm/conf.d/
COPY ../docker-utils/php7.1/blackfire.ini /etc/php7.1/cli/conf.d/

# Install NodeJS and npm
RUN curl -sL https://deb.nodesource.com/setup_4.x | bash -
RUN apt-get install -y nodejs

# Enable Apache Modules
RUN a2enmod rewrite headers fastcgi actions

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# Install Gulp
RUN npm install -g gulp
RUN npm link gulp

# Install Drush
RUN php -r "readfile('http://files.drush.org/drush.phar');" > drush \
    && chmod +x drush \
    && mv drush /usr/bin/

# Install PHPUnit
RUN wget https://phar.phpunit.de/phpunit.phar \
    && chmod +x phpunit.phar \
    && mv phpunit.phar /usr/bin/phpunit

# Insall MailHog and MHSendmail (replacement for sendmail)
RUN wget -O mailhog https://github.com/mailhog/MailHog/releases/download/v0.2.0/MailHog_linux_amd64 \
    && chmod +x mailhog \
    && mv mailhog /usr/bin/

RUN wget -O mhsendmail https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64 \
    && chmod +x mhsendmail \
    && mv mhsendmail /usr/bin/

# Install Codeception globally
RUN curl -LsS http://codeception.com/codecept.phar -o /usr/local/bin/codecept
RUN chmod a+x /usr/local/bin/codecept

# package install is finished, clean up
RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# install custom config files
COPY ../php7.1/php.ini /etc/php7.1/fpm/php.ini
COPY ../php7.1/20-xdebug.ini /etc/php7.1/fpm/conf.d/20-xdebug.ini
COPY ../php7.1cli/php.ini /etc/php7.1/cli/php.ini
COPY ../apache2/ports.conf /etc/apache2/
COPY ../apache2/mods-enabled/*.conf /etc/apache2/mods-enabled/
COPY ../supervisord/*.conf /etc/supervisor/conf.d/
COPY ../ /docker-utils/
COPY ../home /var/www/home
COPY ../site-php/creds.json /var/www/site-php/oncorps/creds.json

# Get rid of nginx and apache default site files
RUN rm -rf /etc/nginx/conf.d/default
RUN a2dissite 000-default

# Setup some infra for SSHD
RUN mkdir -p /var/run/sshd
RUN mkdir -p ~/.ssh/

# Copy our Drush Aliases to the container root user
ADD ../drush/* /root/.drush/

# clean up tmp files (we don't need them for the image)
RUN rm -rf /tmp/* /var/tmp/*


# Start our Services
CMD ["/docker-utils/scripts/start-services.sh"]

# Contains the Mac hack to get the permissions to work for development.
# Set user 1000 and group staff to www-data, enables write permission.
# https://github.com/boot2docker/boot2docker/issues/581#issuecomment-114804894
# TODO: Evaluate whether this is working/helping
RUN usermod -u 1000 www-data
RUN usermod -G staff www-data
