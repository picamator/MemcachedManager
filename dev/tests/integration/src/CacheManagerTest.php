<?php
namespace Picamator\MemcachedManager\Tests\Integration;

class CacheManagerTest extends BaseTest
{
    public function testEmptyCacheSearch()
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->setContextName('cloud')
            ->setEntityName('customer')
            ->setIdList([10, 11])
            ->setFieldList(['id', 'name'])
            ->setIdName('id')
            ->build();

        $searchResult = $this->cacheManager->search($searchCriteria);
        $this->assertFalse($searchResult->hasData());
        $this->assertEquals(0, $searchResult->count());
    }

    public function testHasInCacheSearch()
    {
        // save to cache
        $saveSearchCriteria = $this->searchCriteriaBuilder
            ->setContextName('cloud')
            ->setEntityName('customer')
            ->setFieldList(['id', 'name'])
            ->setIdName('id')
            ->build();

        $data = [['id' => 20, 'name' => 'Sergii']];

        $actualSave = $this->cacheManager->save($saveSearchCriteria, $data);
        $this->assertTrue($actualSave);

        // search
        $searchCriteria = $this->searchCriteriaBuilder
            ->setContextName('cloud')
            ->setEntityName('customer')
            ->setIdList([20, 21, 22])
            ->setFieldList(['id', 'name'])
            ->setIdName('id')
            ->build();

        $searchResult = $this->cacheManager->search($searchCriteria);
        $this->assertTrue($searchResult->hasData());
        $this->assertEquals(1, $searchResult->count());
        $this->assertCount(2, $searchResult->getMissedData());

        // validate result
        $resultData = $searchResult->getData();
        $iteratorResultData = new \ArrayIterator($resultData);
        $iteratorData = new \ArrayIterator($data);

        $multipleIterator = new \MultipleIterator(\MultipleIterator::MIT_NEED_ALL|\MultipleIterator::MIT_KEYS_ASSOC);
        $multipleIterator->attachIterator($iteratorResultData, 'actual');
        $multipleIterator->attachIterator($iteratorData, 'expected');

        foreach($multipleIterator as $item) {
            $this->assertEquals($item['expected'], $item['actual']->get());
        }
    }

    public function testHasMoreFieldsInCacheSearch()
    {
        // save to cache
        $saveSearchCriteria = $this->searchCriteriaBuilder
            ->setContextName('cloud')
            ->setEntityName('customer')
            ->setFieldList(['id', 'name', 'address'])
            ->setIdName('id')
            ->build();

        $data = [['id' => 30, 'name' => 'Sergii', 'address' => 'Ukraine, Kyiv']];

        $actualSave = $this->cacheManager->save($saveSearchCriteria, $data);
        $this->assertTrue($actualSave);

        // search
        $searchCriteria = $this->searchCriteriaBuilder
            ->setContextName('cloud')
            ->setEntityName('customer')
            ->setIdList([30, 31, 32])
            ->setFieldList(['id', 'name'])
            ->setIdName('id')
            ->build();

        $searchResult = $this->cacheManager->search($searchCriteria);
        $this->assertTrue($searchResult->hasData());
        $this->assertEquals(1, $searchResult->count());
        $this->assertCount(2, $searchResult->getMissedData());

        // validate result
        $resultData = $searchResult->getData();
        $iteratorResultData = new \ArrayIterator($resultData);
        $iteratorData = new \ArrayIterator($data);

        $multipleIterator = new \MultipleIterator(\MultipleIterator::MIT_NEED_ALL|\MultipleIterator::MIT_KEYS_ASSOC);
        $multipleIterator->attachIterator($iteratorResultData, 'actual');
        $multipleIterator->attachIterator($iteratorData, 'expected');

        foreach($multipleIterator as $item) {
            $this->assertEquals($item['expected'], $item['actual']->get());
        }
    }

    public function testHasNotInCacheSearch()
    {
        // save to cache
        $saveSearchCriteria = $this->searchCriteriaBuilder
            ->setContextName('cloud')
            ->setEntityName('customer')
            ->setFieldList(['id', 'name'])
            ->setIdName('id')
            ->build();

        $data = [['id' => 40, 'name' => 'Sergii']];

        $actualSave = $this->cacheManager->save($saveSearchCriteria, $data);
        $this->assertTrue($actualSave);

        // search
        $searchCriteria = $this->searchCriteriaBuilder
            ->setContextName('cloud')
            ->setEntityName('customer')
            ->setIdList([40, 41, 42])
            ->setFieldList(['id', 'name', 'address'])
            ->setIdName('id')
            ->build();

        $searchResult = $this->cacheManager->search($searchCriteria);
        $this->assertFalse($searchResult->hasData());
        $this->assertEquals(0, $searchResult->count());
        $this->assertCount(3, $searchResult->getMissedData());

    }

    public function testSave()
    {
        // save to cache
        $saveSearchCriteria = $this->searchCriteriaBuilder
            ->setContextName('cloud')
            ->setEntityName('customer')
            ->setFieldList(['id', 'name'])
            ->setIdName('id')
            ->build();

        $data = [['id' => 50, 'name' => 'Sergii']];

        $actualSave = $this->cacheManager->save($saveSearchCriteria, $data);
        $this->assertTrue($actualSave);
    }

    public function testDelete()
    {
        // save to cache
        $saveSearchCriteria = $this->searchCriteriaBuilder
            ->setContextName('cloud')
            ->setEntityName('customer')
            ->setFieldList(['id', 'name'])
            ->setIdName('id')
            ->build();

        $data = [['id' => 60, 'name' => 'Sergii']];

        $actualSave = $this->cacheManager->save($saveSearchCriteria, $data);
        $this->assertTrue($actualSave);

        // delete
        $searchCriteria = $this->searchCriteriaBuilder
            ->setContextName('cloud')
            ->setEntityName('customer')
            ->setIdList([60])
            ->setIdName('id')
            ->build();

        $this->cacheManager->delete($searchCriteria);

        // search
        $searchResult = $this->cacheManager->search($searchCriteria);
        $this->assertFalse($searchResult->hasData());
        $this->assertEquals(0, $searchResult->count());
        $this->assertCount(1, $searchResult->getMissedData());
    }
}
