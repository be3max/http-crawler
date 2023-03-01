<?php
declare(strict_types=1);


namespace HTTPCrawler;


/**
 * Page statistics main parameters
 */
class PagesStatistics
{
    /**
     * @var int
     */
    private int $pagesCount = 0;
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
     * @var array
     */
    private array $pagesLoads = [];
    /**
     * @var array
     */
    private array $wordsCount = [];
    /**
     * @var array
     */
    private array $titlesLength = [];

    /**
     * @var int
     */
    private int $created_at = 0;

    /**
     *
     */
    public function __construct()
    {
        $this->setCreatedAt(time());
    }

    /**
     * @param int $created_at
     * @return void
     */
    private function setCreatedAt(int $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->created_at;
    }

    /**
     * @return void
     */
    public function increasePageCount(): void
    {
        $this->pagesCount++;
    }

    /**
     * @return int
     */
    public function getPageCount(): int
    {
        return $this->pagesCount;
    }

    /**
     * @param string $image
     * @return bool
     */
    public function addImage(string $image): bool
    {
        if (!in_array($image, $this->uniqueImages)) {
            $this->uniqueImages[] = $image;
            return true;
        }
        return false;
    }

    /**
     * @return int
     */
    public function getUniqueImagesCount(): int
    {
        return count($this->uniqueImages);
    }

    /**
     * @param string $link
     * @return bool
     */
    public function addInternalLink(string $link): bool
    {
        if (!in_array($link, $this->uniqueInternalLinks)) {
            $this->uniqueInternalLinks[] = $link;
            return true;
        }
        return false;
    }

    /**
     * @return int
     */
    public function getUniqueInternalLinksCount(): int
    {
        return count($this->uniqueInternalLinks);
    }

    /**
     * @param string $link
     * @return bool
     */
    public function addExternalLink(string $link): bool
    {
        if (!in_array($link, $this->uniqueExternalLinks)) {
            $this->uniqueExternalLinks[] = $link;
            return true;
        }
        return false;
    }

    /**
     * @return int
     */
    public function getUniqueExternalLinksCount(): int
    {
        return count($this->uniqueExternalLinks);
    }

    /**
     * @param float $page_load
     * @return void
     */
    public function addPageLoad(float $page_load): void
    {
        $this->pagesLoads[] = max($page_load, 0);
    }

    /**
     * @return float
     */
    public function getAvgPagesLoad(): float
    {
        if (count($this->pagesLoads)) {
            return array_sum($this->pagesLoads) / count($this->pagesLoads);
        }
        return 0;
    }

    /**
     * @param int $words_count
     * @return void
     */
    public function addWordsCount(int $words_count): void
    {
        $this->wordsCount[] = max($words_count, 0);
    }

    /**
     * @return float
     */
    public function getAvgWordsCount(): float
    {
        if (count($this->wordsCount)) {
            return array_sum($this->wordsCount) / count($this->wordsCount);
        }
        return 0;
    }

    /**
     * @param float $title_length
     * @return void
     */
    public function addTitleLength(float $title_length): void
    {
        $this->titlesLength[] = max($title_length, 0);
    }

    /**
     * @return float
     */
    public function getAvgTitleLength(): float
    {
        if (count($this->titlesLength)) {
            return array_sum($this->titlesLength) / count($this->titlesLength);
        }
        return 0;
    }

}
