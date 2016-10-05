<?php
namespace Picamator\MemcachedManager\Observer;

use Picamator\CacheManager\Spi\ObserverInterface;
use Picamator\CacheManager\Spi\SubjectInterface;
use Monolog\Logger;

/**
 * Observer to log all operation
 */
class OperationLogger implements ObserverInterface
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function update(SubjectInterface $subject, array $data)
    {
        $event = current($data);
        $this->logger->info($event);
    }
}
