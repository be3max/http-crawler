<?php

namespace HTTPCrawler\DomNodes;

use DOMNode;

/**
 * DOMDocument node check for TITLE tag
 */
class TitleDomHandler extends AbstractDomHandler
{
    /**
     * @param DOMNode $node
     * @return bool|null
     */
    public function handle(DOMNode $node): ?bool
    {
        if ($node->tagName === "title") {
            $this->page->setMetaTitle($node->textContent);
            return false;
        } else {
            return parent::handle($node);
        }
    }
}
