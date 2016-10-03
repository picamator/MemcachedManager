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
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === 'e115a8dc7871f15d853148a7fbac7da27d6c0030b848d9b3dc09e2a0388afed865e6a3d6b3c0fad45c48e2b5fc1196ae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
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

EXPOSE 22
CMD ["/usr/sbin/sshd", "-D"]

# volume
RUN mkdir /MemcachedManager
VOLUME /MemcachedManager