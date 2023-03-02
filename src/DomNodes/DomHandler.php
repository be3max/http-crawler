<?php

namespace HTTPCrawler\DomNodes;

use DOMNode;
use HTTPCrawler\Page;

/**
 * DOMDocument node check interface
 */
interface DomHandler
{
    /**
     * @param Page $page
     */
    public function __construct(Page $page);

    /**
     * @param DomHandler $handler
     * @return DomHandler
     */
    public function setNext(DomHandler $handler): DomHandler;

    /**
     * @param DOMNode $node
     * @return bool|null
     */
    public function handle(DOMNode $node): ?bool;
}
