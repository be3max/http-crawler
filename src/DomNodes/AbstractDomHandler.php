<?php

namespace HTTPCrawler\DomNodes;

use DOMNode;
use HTTPCrawler\Page;

/**
 * Abstract class Domain handler
 */
abstract class AbstractDomHandler implements DomHandler
{
    /**
     * @var DomHandler
     */
    private DomHandler $nextHandler;

    /**
     * @param Page $page
     */
    public function __construct(
        protected Page $page
    ) {}

    /**
     * @param DomHandler $handler
     * @return DomHandler
     */
    public function setNext(DomHandler $handler): DomHandler
    {
        $this->nextHandler = $handler;

        return $handler;
    }

    /**
     * @param DOMNode $node
     * @return bool|null
     */
    public function handle(DOMNode $node): ?bool
    {
        if (isset($this->nextHandler)) {
            return $this->nextHandler->handle($node);
        }

        return null;
    }
}
