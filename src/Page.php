<?php
declare(strict_types=1);

namespace HTTPCrawler;

use HTTPCrawler\DomNodes\{ADomHandler, ImgDomHandler, TitleDomHandler};

/**
 *
 */
class Page
{
    /**
     * @var DomUrl
     */
    private DomUrl $domUrl;

    /**
     * @var array
     */
    private array $uniqueImages = [];

    /**
     * @var array
     */
    private array $uniqueInternalLinks = [];
    /**
     * @var array
     */
    private array $uniqueExternalLinks = [];

    /**
     * @var float|null
     */
    private ?float $loadTime = null;
    /**
     * @var int|null
     */
    private ?int $wordsCount = 0;
    /**
     * @var string|null
     */
    private ?string $metaTitle = null;

    /**
     * @var int|null
     */
    private ?int $statusCode = null;


    /**
     * @param DomUrl $domUrl
     */
    public function __construct(DomUrl $domUrl)
    {
        $this->setDomUrl($domUrl);

        $this->processPage();
    }

    /**
     * @param DomUrl $domUrl
     * @return void
     */
    private function setDomUrl(DomUrl $domUrl): void
    {
        $this->domUrl = $domUrl;
    }

    /**
     * @return DomUrl
     */
    public function getDomUrl(): DomUrl
    {
        return $this->domUrl;
    }

    /**
     * @param int|null $statusCode
     * @return void
     */
    public function setStatusCode(?int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return int|null
     */
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    /**
     * @return int
     */
    public function getWordsCount(): int
    {
        return $this->wordsCount;
    }

    /**
     * @param int $wordsCount
     * @return void
     */
    public function setWordsCount(int $wordsCount): void
    {
        $this->wordsCount = $wordsCount;
    }

    /**
     * @return void
     */
    private function updateWordsCount(): void
    {
        $this->setWordsCount(str_word_count(
            strip_tags(
                $this->getDomUrl()->getHtmlContent()
            )
        ));
    }

    /**
     * @return string|null
     */
    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    /**
     * @param string|null $metaTitle
     * @return void
     */
    public function setMetaTitle(?string $metaTitle): void
    {
        $this->metaTitle = $metaTitle;
    }

    /**
     * @param $link
     * @return bool
     */
    private function isInternalLink($link): bool
    {
        $host = parse_url($link, PHP_URL_HOST);
        if (empty($host)) {
            // This is a relative link, so it must be internal.
            return true;
        } else {
            // This is an absolute link, so we need to check if it points to the same domain (including subdomains) as the current page.
            $link_domain = $host;
            if ($link_domain === $this->getDomUrl()->getDomain() || $this->endsWith($link_domain, '.' . $this->getDomUrl()->getDomain())) {
                // The link domain matches the current domain or is a subdomain of the current domain, so it's internal.
                return true;
            } else {
                // The link domain is external.
                return false;
            }
        }
    }

    /**
     * @param $str
     * @param $suffix
     * @return bool
     */
    function endsWith($str, $suffix): bool
    {
        return substr_compare($str, $suffix, -strlen($suffix)) === 0;
    }

    /**
     * @param string $link
     * @return void
     */
    public function addLink(string $link): void
    {
        $link = filter_var($link, FILTER_SANITIZE_URL);
        if (!empty($link)) {
            if ($this->isInternalLink($link)) {
                $this->addInternalLink($link);
            } else {
                $this->addExternalLink($link);
            }
        }
    }

    /**
     * @return array
     */
    public function getInternalLinks(): array
    {
        return $this->uniqueInternalLinks;
    }

    /**
     * @param string $link
     * @return void
     */
    private function addInternalLink(string $link): void
    {
        $link = filter_var($link, FILTER_SANITIZE_URL);

        if (!empty($link) && !in_array($link, $this->getInternalLinks())) {
            $this->uniqueInternalLinks[] = $link;
        }
    }

    /**
     * @return array
     */
    public function getExternalLinks(): array
    {
        return $this->uniqueExternalLinks;
    }

    /**
     * @param string $link
     * @return void
     */
    private function addExternalLink(string $link): void
    {
        $link = filter_var($link, FILTER_SANITIZE_URL);

        if (!empty($link) && !in_array($link, $this->getExternalLinks())) {
            $this->uniqueExternalLinks[] = $link;
        }
    }

    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->uniqueImages;
    }

    /**
     * @param string $src
     * @return void
     */
    public function addImage(string $src): void
    {
        if (!empty($src) && !in_array($src, $this->getImages())) {
            $this->uniqueImages[] = $src;
        }
    }

    /**
     * @return float
     */
    public function getLoadTime(): float
    {
        return $this->loadTime;
    }

    /**
     * @param float|bool $loadTime
     * @return void
     */
    private function setLoadTime(float $loadTime): void
    {
        $this->loadTime = max($loadTime, 0);
    }

    /**
     * Detect ans save page nodes by type
     *
     * @return void
     */
    private function processPage(): void
    {
        $this->setLoadTime((float)$this->getDomUrl()->getLoadTime());

        $this->updateWordsCount();

        $this->setStatusCode($this->getDomUrl()->getStatusCode());

        $aDomHandler = new ADomHandler($this);
        $imgDomHandler = new ImgDomHandler($this);
        $titleDomHandler = new TitleDomHandler($this);

        $aDomHandler->setNext($imgDomHandler)
            ->setNext($titleDomHandler);

        $nodes = $this->getDomUrl()->getDom()->getElementsByTagName("*");

        foreach ($nodes as $node) {
            $aDomHandler->handle($node);
        }
    }

}
