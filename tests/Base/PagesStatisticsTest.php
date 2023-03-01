<?php

declare(strict_types=1);

namespace HTMLDomParserTests\Base;

use HTTPCrawler\PagesStatistics;

/**
 *
 */
class PagesStatisticsTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @return void
     */
    public function testPagesStatistics()
    {
        $pagesStatistics = new PagesStatistics();

        foreach(range(1, 3) as $ignored) {
            $pagesStatistics->increasePageCount();
        }
        $this->assertEquals(3, $pagesStatistics->getPageCount());


        foreach ($this->getImages() as $image) {
            $pagesStatistics->addImage($image);
        }
        $this->assertEquals(4, $pagesStatistics->getUniqueImagesCount());


        foreach ($this->getInternalLinks() as $link) {
            $pagesStatistics->addInternalLink($link);
        }
        $this->assertEquals(3, $pagesStatistics->getUniqueInternalLinksCount());


        foreach ($this->getExternalLinks() as $link) {
            $pagesStatistics->addExternalLink($link);
        }
        $this->assertEquals(6, $pagesStatistics->getUniqueExternalLinksCount());


        $pagesStatistics->addPageLoad(0.244);
        $pagesStatistics->addPageLoad(1.166);
        $pagesStatistics->addPageLoad(0.88);

        $this->assertEquals(0.7633333333333333, $pagesStatistics->getAvgPagesLoad());


        foreach ($this->getTitles() as $title_len) {
            $pagesStatistics->addTitleLength($title_len);
        }
        $this->assertEquals(80.6, $pagesStatistics->getAvgTitleLength());


        foreach ($this->getWordCounts() as $word_count) {
            $pagesStatistics->addWordsCount($word_count);
        }
        $this->assertEquals(622.4, $pagesStatistics->getAvgWordsCount());


    }

    /**
     * @return array
     */
    public function getImages(): array
    {
        return [
            'https://testimages.org/img/testimages_screenshot.jpg',
            'https://testimages.org/img/testimages_main_logo_150x30.png',
            'https://testimages.org/img/testimages_main_logo_150x30.png',
            'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7',
            'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7',
            'https://images.ctfassets.net/dfcvkz6j859j/3d5XGnaNF50P66GXpxsNqI/da3def54118bb7e0a0e9d250cb2ff4f5/facebook.svg',
        ];
    }

    /**
     * @return array
     */
    public function getInternalLinks(): array
    {
        return [
            'https://testimages.org/',
            'https://testimages.org/testimages/',
            'https://testimages.org/contacts/',
            'https://testimages.org/contacts/',
        ];
    }

    /**
     * @return array
     */
    public function getExternalLinks(): array
    {
        return [
            'https://testimages0.org/',
            'https://testimages1.org/testimages/',
            'https://testimages2.org/contacts/',
            'https://testimages3.org/contacts/',
            'https://testimages4.org/contacts/',
            'https://testimages4.org/contacts/',
            'https://testimages6.org/contacts/',
        ];
    }

    /**
     * @return array
     */
    public function getTitles(): array
    {
        return [
            10,
            15,
            123,
            255,
            -345,
        ];
    }

    /**
     * @return array
     */
    public function getWordCounts(): array
    {
        return [
            234,
            543,
            2335,
            -5432,
            0,
        ];
    }


}
