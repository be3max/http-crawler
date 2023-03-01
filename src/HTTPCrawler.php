<?php
declare(strict_types=1);

namespace HTTPCrawler;

use Exception;

/**
 * HTTPCrawler boot class
 */
class HTTPCrawler
{
    /**
     * @var Url
     */
    private Url $url;

    /**
     * @param Url $url
     */
    public function __construct(Url $url)
    {
        $this->setUrl($url);
    }

    /**
     * Set current processing Url
     *
     * @param Url $url
     * @return void
     */
    private function setUrl(Url $url): void
    {
        $this->url = $url;
    }

    /**
     * Get current processing Url
     *
     * @return Url
     */
    private function getUrl(): Url
    {
        return $this->url;
    }

    /**
     * Loads pages and return pages collection
     *
     * @throws Exception
     */
    public function load(): PagesCollection
    {
        $pagesCollection = new PagesCollection(5);

        while (!$pagesCollection->collectionIsFull()) {
            $domUrl = (
                new DomUrl($this->url->getUrl())
            )->load();

            $page = new Page($domUrl);

            $pagesCollection->addItem($page);

            $nextLink = $pagesCollection->getPageNextLink();

            if (empty($nextLink)) {
                break;
            }
            $this->getUrl()->setUrl($nextLink);

            usleep(500 * 1000);
        }

        return $pagesCollection;
    }
}
