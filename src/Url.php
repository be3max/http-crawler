<?php

namespace HTTPCrawler;

/**
 * Simple url with link and domain
 */
class Url
{
    /**
     * @var string
     */
    protected string $url;
    /**
     * @var string
     */
    protected string $domain;

    /**
     * @param string $url
     * @return string
     */
    private function getDomainFromUrl(string $url): string
    {
        return parse_url($url, PHP_URL_HOST) ?? '';
    }

    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->setUrl($url);

        $this->domain = $this->getDomainFromUrl($this->url);
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param $url
     * @return void
     */
    public function setUrl($url): void
    {
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $scheme = parse_url($url, PHP_URL_SCHEME);

        if (empty($scheme)) {
            $url = 'https://' . ltrim($url, '/');
        }
        $this->url = $url;
    }
}
