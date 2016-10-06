<?php
namespace Picamator\MemcachedManager\Tests\Integration;

class CacheManagerSubjectTest extends BaseTest
{
    /**
     * @var \Picamator\MemcachedManager\Observer\OperationLogger
     */
    private $loggerObserver;

    /**
     * @var \Monolog\Handler\TestHandler
     */
    private $loggerTestHandler;

    protected function setUp()
    {
        parent::setUp();

        $this->loggerObserver = $this->container->get('logger_observer');
        $this->loggerTestHandler = $this->container->get('logger_test_handler');
    }

    /**
     * Test all cache manager operation usage with observing all events
     */
    public function testEvent()
    {
        // events over save operation
        $this->cacheManagerSubject->attach('beforeSave', $this->loggerObserver);
        $this->cacheManagerSubject->attach('afterSave', $this->loggerObserver);

        // events over search operation
        $this->cacheManagerSubject->attach('beforeSearch', $this->loggerObserver);
        $this->cacheManagerSubject->attach('afterSearch', $this->loggerObserver);

        // events over delete operation
        $this->cacheManagerSubject->attach('beforeDelete', $this->loggerObserver);
        $this->cacheManagerSubject->attach('afterDelete', $this->loggerObserver);

        // save
        $saveSearchCriteria = $this->searchCriteriaBuilder
            ->setContextName('cloud')
            ->setEntityName('customer')
            ->setFieldList(['id', 'name'])
            ->setIdName('id')
            ->build();

        $data = [['id' => 100, 'name' => 'Sergii']];
        $this->cacheManagerSubject->save($saveSearchCriteria, $data);

        // search
        $searchCriteria = $this->searchCriteriaBuilder
            ->setContextName('cloud')
            ->setEntityName('customer')
            ->setIdList([100])
            ->setFieldList(['id', 'name'])
            ->setIdName('id')
            ->build();
        $this->cacheManagerSubject->search($searchCriteria);

        // delete
        $this->cacheManagerSubject->delete($searchCriteria);

        // asserts
        $this->assertTrue($this->loggerTestHandler->hasInfo('beforeSave'));
        $this->assertTrue($this->loggerTestHandler->hasInfo('afterSave'));

        $this->assertTrue($this->loggerTestHandler->hasInfo('beforeSearch'));
        $this->assertTrue($this->loggerTestHandler->hasInfo('afterSearch'));

        $this->assertTrue($this->loggerTestHandler->hasInfo('beforeDelete'));
        $this->assertTrue($this->loggerTestHandler->hasInfo('afterDelete'));
    }
}
