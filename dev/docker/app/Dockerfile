FROM ubuntu:16.04

RUN apt-get update

# git
RUN apt-get -y install git

# php
RUN apt-get -y install php7.0
RUN apt-get -y install php7.0-xml
RUN apt-get -y install php7.0-curl
RUN apt-get -y install php7.0-zip

# memcached
RUN apt-get -y install php7.0-dev
RUN apt-get -y install memcached

RUN apt-get install -y gcc make autoconf libc-dev pkg-config
RUN apt-get install -y zlib1g-dev
RUN apt-get install -y libmemcached-dev
RUN git clone https://github.com/php-memcached-dev/php-memcached
RUN cd php-memcached && git checkout php7
RUN cd php-memcached && phpize
RUN cd php-memcached && PATH=/opt/sp/php7.0/bin:$PATH ./configure --disable-memcached-sasl
RUN cd php-memcached && make
RUN cd php-memcached && make install
RUN bash -c "echo extension=memcached.so > /etc/php/7.0/mods-available/memcached.ini"
RUN bash -c "echo extension=memcached.so > /etc/php/7.0/cli/conf.d/20-memcached.ini"

# composer
RUN php -r "file_put_contents('composer-setup.php', file_get_contents('https://getcomposer.org/installer'));"
RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === 'aa96f26c2b67226a324c27919f1eb05f21c248b987e6195cad9690d5c1ff713d53020a02ac8c217dbf90a7eacc9d141d') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

# ssh, source https://docs.docker.com/engine/examples/running_ssh_service/ with correction https://github.com/docker/docker/issues/23621#issuecomment-226575258
RUN apt-get -y install openssh-server

RUN mkdir /var/run/sshd
RUN echo 'root:screencast' | chpasswd
RUN sed -i 's/PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config

# SSH login fix. Otherwise user is kicked off after login
RUN sed 's@session\s*required\s*pam_loginuid.so@session optional pam_loginuid.so@g' -i /etc/pam.d/sshd

ENV NOTVISIBLE "in users profile"
RUN echo "export VISIBLE=now" >> /etc/profile

# supervisor
RUN apt-get install -y supervisor
RUN mkdir -p  /var/log/supervisor
ADD ./dev/docker/app/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# expose ports
EXPOSE 22
CMD ["supervisord", "--configuration", "/etc/supervisor/conf.d/supervisord.conf", "--logfile", "/var/log/supervisor/supervisord.log", "--logfile_maxbytes", "5000000", "--logfile_backups", "5", "--pidfile", "/var/tmp/supervisord.pid"]

# volume
RUN mkdir /MemcachedManager
VOLUME /MemcachedManager

# workdir
WORKDIR /MemcachedManager
