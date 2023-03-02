<?php

namespace HTTPCrawler\DomNodes;

use DOMNode;

/**
 * DOMDocument node check for IMG tag
 */
class ImgDomHandler extends AbstractDomHandler
{
    /**
     * @param DOMNode $node
     * @return bool|null
     */
    public function handle(DOMNode $node): ?bool
    {
        if ($node->tagName === "img") {
            $this->page->addImage($node->getAttribute('src'));
            return false;
        } else {
            return parent::handle($node);
        }
    }
}
