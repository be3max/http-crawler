<?php
declare(strict_types=1);


namespace HTTPCrawler;

use Exception;
use IteratorAggregate;

/**
 *
 */
class PagesCollection implements IteratorAggregate
{
    /**
     * @var array
     */
    private array $items = [];
    /**
     * @var int
     */
    private int $limit = 0;

    /**
     * @param int $limit
     */
    public function __construct(int $limit = 0)
    {
        $this->setPagesLimit($limit);
    }

    /**
     * @param int $limit
     * @return void
     */
    private function setPagesLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    private function getPagesLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param string $link
     * @return string
     * @throws Exception
     */
    private function fixUrlDomain(string $link): string
    {
        $link = preg_replace("(^https?://)", "", $link);
        if (str_starts_with($link, '/')) {
            $link = $this->getIterator()->current()->getDomUrl()->getDomain() . $link;
        }
        return $link;
    }

    /**
     * @throws Exception
     */
    public function getPageByLink(string $link)
    {
        foreach ($this->getItems() as $Page) {
            if ($this->fixUrlDomain($link) == $this->fixUrlDomain($Page->getDomUrl()->getUrl())) {
                return $Page;
            }
        }
        return false;
    }

    /**
     * @return string
     */
    public function getPageNextLink(): string
    {
        try {
            $links = $this->getIterator()->current()->getInternalLinks();

            foreach ($links as $link) {
                if (!empty($link)
                    && $link != '/'
                    && !str_starts_with($link, '#')
                    && !$this->getPageByLink($link)
                ) {

                    if (str_starts_with($link, '/')) {
                        $link = $this->getIterator()->current()->getDomUrl()->getDomain() . $link;
                    }

                    return $link;
                }
            }
        } catch (Exception) {
            return '';
        }
        return '';
    }

    /**
     * @param Page $item
     * @return void
     */
    public function addItem(Page $item): void
    {
        $this->items[] = $item;
    }

    /**
     * @return PageIterator
     */
    public function getIterator(): PageIterator
    {
        return new PageIterator($this);
    }

    /**
     * @return bool
     */
    public function collectionIsFull(): bool
    {
        if (count($this->items) + 1 > $this->getPagesLimit()) {
            return true;
        }
        return false;
    }

}
