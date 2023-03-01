<?php
declare(strict_types=1);

namespace HTTPCrawler;

/**
 * Used to make request per file_get_contents
 */
class Request
{

    /**
     * @var string
     */
    private string $url;
    /**
     * @var int|null
     */
    private ?int $statusCode;
    /**
     * @var string
     */
    private string $contentData;

    /**
     * @var resource
     */
    private $context;
    /**
     * @var int
     */
    private int $timeout = 10;

    /**
     * Request constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->statusCode = 0;
        $this->url = $url;
        $this->context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => 'Content-type: text/html; charset=utf-8;' . PHP_EOL,
                "timeout" => $this->timeout
            ],
        ]);
    }

    /**
     * @param string|null $contentData
     * @return void
     */
    public function setResponseData(?string $contentData): void
    {
        $this->contentData = $contentData;
    }

    /**
     * @return string
     */
    public function getResponseData(): string
    {
        return $this->contentData;
    }

    /**
     * @param int $statusCode
     * @return void
     */
    public function setStatusCode(int $statusCode): void
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
     * @return string|bool
     */
    public function load(): string|bool
    {
        if (($content = @file_get_contents(
                $this->url,
                false,
                $this->context
            )) === false
        ) {
            $content = '';
        }

        $this->setResponseData($content);

        if (isset($http_response_header) && $http_response_header && is_array($http_response_header)) {
            foreach ($http_response_header as $_header) {

                if (preg_match("#HTTP/[0-9.]+\s+([0-9]+)#", $_header, $out)) {
                    $this->setStatusCode(intval($out[1]));
                }

            }
        }

        return $this->getResponseData();
    }

}
