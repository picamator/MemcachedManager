<?php
namespace Picamator\MemcachedManager\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Base to share configuration over all tests
 */
abstract class BaseTest extends TestCase 
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    /**
     * @var YamlFileLoader
     */
    protected $loader;

    /**
     * @var \Picamator\CacheManager\CacheManager
     */
    protected $cacheManager;

    /**
     * @var \Picamator\CacheManager\CacheManagerSubject
     */
    protected $cacheManagerSubject;

    /**
     * @var \Picamator\CacheManager\Data\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

	protected function setUp() 
	{
		parent::setUp();

        $this->container = new ContainerBuilder();
        $this->loader = new YamlFileLoader($this->container, new FileLocator(__DIR__));
        $this->loader->load('./../../../../src/config/services.yml');

        $this->cacheManager = $this->container->get('cache_manager');
        $this->cacheManagerSubject = $this->container->get('cache_manager_subject');
        $this->searchCriteriaBuilder = $this->container->get('search_criteria_builder');
    }
}
