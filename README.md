MemcachedManager
================

MemcachedManager is an example of usage [CacheManager](https://github.com/picamator/CacheManager) over [Memcached](https://memcached.org/).

Requirements
------------
* PHP 7.0.x
* Memcached

Installation
------------
* Run `composer install --no-dev`

Usage
-----

```php
<?php
declare(strict_types = 1);

require_once './vendor/autoload.php';

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

// init DI
$container = new ContainerBuilder();
$loader = new YamlFileLoader($this->container, new FileLocator(__DIR__));
$loader->load('./src/config/services.yml');

// gets search criteria and manager
/** @var \Picamator\CacheManager\CacheManagerSubject $cacheManagerSubject */
$cacheManagerSubject = $this->container->get('cache_manager_subject');
/** @var \Picamator\CacheManager\Data\SearchCriteriaBuilder $searchCriteriaBuilder */
$searchCriteriaBuilder = $this->container->get('search_criteria_builder');

// add observer to manager
/** @var \Picamator\MemcachedManager\Observer\OperationLogger $loggerObserver */
$loggerObserver = $this->container->get('logger_observer');
$cacheManagerSubject->attach('beforeSearch', $loggerObserver);

// search
$searchCriteria = $this->searchCriteriaBuilder
    ->setContextName('cloud')
    ->setEntityName('customer')
    ->setIdList([200, 201])
    ->setFieldList(['id', 'name', 'address'])
    ->setIdName('id')
    ->build();
    
$searchResult = $this->cacheManager->search($searchCriteria);

```

More examples can be found inside [integration tests](dev/test/integration/src).

Developing
----------
To configure developing environment please:

1. Follow [install and run Docker container](dev/docker/README.md)
2. Run inside project root in the Docker container `composer install` 

Contribution
------------
If you find this project worth to use please add a star. Follow changes to see all activities.
And if you see room for improvement, proposals please feel free to create an issue or send pull request.
Here is a great [guide to start contributing](https://guides.github.com/activities/contributing-to-open-source/).

Please note that this project is released with a [Contributor Code of Conduct](http://contributor-covenant.org/version/1/4/).
By participating in this project and its community you agree to abide by those terms.

License
-------
CacheManager is licensed under the MIT License. Please see the [LICENSE](LICENSE.txt) file for details
