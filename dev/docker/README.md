Docker
======
Development environment has:

* /app: application container with PHP7, memcached, composer, OpenSSL, and supervisord

Installation
------------
To prepare developing environment please choose one of the installation way replacing placeholder:

* `{my-docker-account}` Docker Hub account
* `{project-path}` project path

### Pre installation
Before start please be sure that was installed:

1. [Docker](https://docs.docker.com/engine/installation/)
2. Optional, create account in [Docker Hub](https://hub.docker.com/)

### Installation with building own Docker image
1. Build image by running command from MemcachedManager root directory, `sudo docker build -t {my-docker-account}/memcachedmanager -f dev/docker/app/Dockerfile .`
2. Check images `sudo docker images`
3. Run container `sudo docker run -d -p 2223:22 -v ~/{project-path}/MemcachedManager:/MemcachedManager -t {my-docker-account}/memcachedmanager`
4. Check container by executing command `sudo docker ps`
5. Run command to get into container `ssh root@0.0.0.0 -p 2223`

### Installation using prepared Docker image
1. Run command `sudo docker login`
2. Run command `sudo docker pull picamator/memcachedmanager`
3. Check images `sudo docker images`
4. Run container `sudo docker run -d -p 2223:22 -v ~/{project-path}/MemcachedManager:/MemcachedManager -t picamator/memcachedmanager`
5. Check container by executing command `sudo docker ps`
6. Run command to get into container `ssh root@0.0.0.0 -p 2223`

SSH
---
Please use credentials bellow to connect to container via ssh:

1. user: `root`
2. password: `screencast`
3. ip: 0.0.0.0
4. port: 2223

To make connection via console simple run `ssh root@0.0.0.0 -p 2223`.

Configuration IDE (PhpStorm)
---------------------------- 
### Remote interpreter
1. Use ssh connection to set php interpreter
2. Set "Path mappings": <progect root>->/MemcachedManager

More information is [here](https://confluence.jetbrains.com/display/PhpStorm/Working+with+Remote+PHP+Interpreters+in+PhpStorm).

### UnitTests
1. Configure UnitTest using remote interpreter. 
2. Choose "Use Composer autoload"
3. Set "Path to script": /MemcachedManager/vendor/autoload.php
4. Set "Default configuration file": /MemcachedManager/dev/tests/unit/phpunit.xml.dist

More information is [here](https://confluence.jetbrains.com/display/PhpStorm/Running+PHPUnit+tests+over+SSH+on+a+remote+server+with+PhpStorm).
