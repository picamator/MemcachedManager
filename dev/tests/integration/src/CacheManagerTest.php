<?php
namespace Picamator\MemcachedManager\Tests\Integration;

use Picamator\CacheManager\Data\SearchCriteria;

class CacheManagerTest extends BaseTest
{
    public function testEmptyCacheSearch()
    {
        $searchCriteria = new SearchCriteria(
            'customer',     // $entityName
            [10, 11],       // $idList
            ['id', 'name'], // $fieldList
            'id',           // $idName
            'cloud'         // $contextName
        );

        $searchResult = $this->cacheManager->search($searchCriteria);
        $this->assertFalse($searchResult->hasData());
        $this->assertEquals(0, $searchResult->count());
    }

    public function testHasInCacheSearch()
    {
        // save to cache
        $saveSearchCriteria = new SearchCriteria(
            'customer',     // $entityName
            [],             // $idList
            ['id', 'name'], // $fieldList
            'id',           // $idName
            'cloud'         // $contextName
        );
        $data = [['id' => 1, 'name' => 'Sergii']];

        $actualSave = $this->cacheManager->save($saveSearchCriteria, $data);
        $this->assertTrue($actualSave);

        // search
        $searchCriteria = new SearchCriteria(
            'customer',     // $entityName
            [1, 2, 3],      // $idList
            ['id', 'name'], // $fieldList
            'id',           // $idName
            'cloud'         // $contextName
        );

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
        $saveSearchCriteria = new SearchCriteria(
            'customer',                 // $entityName
            [],                         // $idList
            ['id', 'name', 'address'],  // $fieldList
            'id',                       // $idName
            'cloud'                     // $contextName
        );
        $data = [['id' => 1, 'name' => 'Sergii', 'address' => 'Ukraine, Kyiv']];

        $actualSave = $this->cacheManager->save($saveSearchCriteria, $data);
        $this->assertTrue($actualSave);

        // search
        $searchCriteria = new SearchCriteria(
            'customer',     // $entityName
            [1, 2, 3],      // $idList
            ['id', 'name'], // $fieldList
            'id',           // $idName
            'cloud'         // $contextName
        );

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
        $saveSearchCriteria = new SearchCriteria(
            'customer',      // $entityName
            [],              // $idList
            ['id', 'name'],  // $fieldList
            'id',            // $idName
            'cloud'          // $contextName
        );
        $data = [['id' => 20, 'name' => 'Sergii']];

        $actualSave = $this->cacheManager->save($saveSearchCriteria, $data);
        $this->assertTrue($actualSave);

        // search
        $searchCriteria = new SearchCriteria(
            'customer',                 // $entityName
            [20, 21, 22],                  // $idList
            ['id', 'name', 'address'],  // $fieldList
            'id',                       // $idName
            'cloud'                     // $contextName
        );

        $searchResult = $this->cacheManager->search($searchCriteria);
        $this->assertFalse($searchResult->hasData());
        $this->assertEquals(0, $searchResult->count());
        $this->assertCount(3, $searchResult->getMissedData());

    }
}
