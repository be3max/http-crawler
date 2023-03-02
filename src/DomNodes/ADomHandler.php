<?php

namespace HTTPCrawler\DomNodes;

use DOMNode;

/**
 * DOMDocument node check abstract class for A tag link
 */
class ADomHandler extends AbstractDomHandler
{
    /**
     * @param DOMNode $node
     * @return bool|null
     */
    public function handle(DOMNode $node): ?bool
    {
        if ($node->tagName === "a") {
            $this->page->addLink($node->getAttribute('href'));
            return false;
        } else {
            return parent::handle($node);
        }
    }
}
