<?php
declare(strict_types=1);

namespace HTTPCrawler;

use DOMDocument;
use Exception;

/**
 * Url with page html DOMDocument
 */
class DomUrl extends Url
{

    /**
     * @var DOMDocument
     */
    protected DOMDocument $domDocument;

    /**
     * @var string
     */
    protected string $htmlContent;
    /**
     * @var float|bool
     */
    protected float|bool $loadTime = false;
    /**
     * @var int
     */
    private int $statusCode = 0;

    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->setDom(new DOMDocument('1.0', 'UTF-8'));

        parent::__construct($url);
    }

    /**
     * @param DOMDocument $domDocument
     * @return void
     */
    private function setDom(DOMDocument $domDocument): void
    {
        $this->domDocument = $domDocument;
    }

    /**
     * @return DOMDocument
     */
    public function getDom(): DOMDocument
    {
        return $this->domDocument;
    }

    /**
     * @return string
     */
    public function getHtmlContent(): string
    {
        return $this->htmlContent;
    }

    /**
     * @param string $htmlContent
     * @return void
     */
    public function setHtmlContent(string $htmlContent): void
    {
        $this->htmlContent = $htmlContent;
    }

    /**
     * @return int|null
     */
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return void
     */
    private function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return float|bool
     */
    public function getLoadTime(): float|bool
    {
        return $this->loadTime;
    }

    /**
     * @param float $loadTime
     * @return void
     */
    public function setLoadTime(float $loadTime): void
    {
        $this->loadTime = $loadTime;
    }

    /**
     * @return void
     */
    public function fetchHtmlContent(): void
    {
        if (!empty($this->getHtmlContent())) {
            libxml_use_internal_errors(true);

            $this->getDom()->loadHTML($this->getHtmlContent());
        }
    }

    /**
     * @throws Exception
     */
    public function load(): DomUrl
    {
        $this->setLoadTime(microtime(true));

        $request = new Request($this->url);
        $this->setHtmlContent(
            htmlContent: $request->load()
        );

        $this->setLoadTime(microtime(true) - $this->getLoadTime());

        $this->setStatusCode($request->getStatusCode());

        $this->fetchHtmlContent();

        return $this;
    }

}
