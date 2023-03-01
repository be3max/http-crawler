<?php
declare(strict_types=1);

namespace HTTPCrawler;

use Iterator;

/**
 *
 */
class PageIterator implements Iterator
{
    /**
     * @var PagesCollection
     */
    private PagesCollection $collection;

    /**
     * @var int
     */
    private int $position = 0;

    /**
     * @var bool|mixed
     */
    private bool $reverse;

    /**
     * @param PagesCollection $collection
     * @param bool $reverse
     */
    public function __construct(PagesCollection $collection, bool $reverse = false)
    {
        $this->collection = $collection;
        $this->reverse = $reverse;
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        $this->position = $this->reverse ?
            count($this->collection->getItems()) - 1 : 0;
    }

    /**
     * @return mixed
     */
    public function current(): mixed
    {
        return $this->collection->getItems()[$this->position];
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * @return void
     */
    public function next(): void
    {
        $this->position = $this->position + ($this->reverse ? -1 : 1);
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->collection->getItems()[$this->position]);
    }
}
