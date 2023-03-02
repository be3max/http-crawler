<?php
declare(strict_types=1);

use HTTPCrawler\HTTPCrawler;
use HTTPCrawler\PagesStatistics;
use HTTPCrawler\Templates\SimpleTemplate;
use HTTPCrawler\Url;

require __DIR__.'/../vendor/autoload.php';

$url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
$url = $url ?? '';

$pagesStatistics = new PagesStatistics();

$PagesScoringBoardData = [];

if (!empty($url)) {

    $httpCrawler = new HTTPCrawler((
        new Url($url)
    ));

    try {
        $pagesCollection = $httpCrawler->load();

        $cnt = 0;
        foreach ($pagesCollection->getIterator() as $page) {

            $pagesStatistics->increasePageCount();
            foreach ($page->getImages() as $image) {
                $pagesStatistics->addImage($image);
            }
            foreach ($page->getInternalLinks() as $link) {
                $pagesStatistics->addInternalLink($link);
            }
            foreach ($page->getExternalLinks() as $link) {
                $pagesStatistics->addExternalLink($link);
            }
            $pagesStatistics->addPageLoad($page->getLoadTime());
            $pagesStatistics->addTitleLength(strlen((string)$page->getMetaTitle()));
            $pagesStatistics->addWordsCount($page->getWordsCount());

            $PagesScoringBoardData[] = [
                'id' => ++$cnt,
                'metaTitle' => $page->getMetaTitle(),
                'url' => $page->getDomUrl()->getUrl(),
                'imagesCount' => count($page->getImages()),
                'internalLinksCount' => count($page->getInternalLinks()),
                'externalLinksCount' => count($page->getExternalLinks()),
                'loadTime' => $page->getLoadTime(),
                'wordsCount' => $page->getWordsCount(),
                'statusCode' => $page->getStatusCode(),
            ];
        }
    } catch (Exception $e) {
        return false;
    }

}

SimpleTemplate::view('index.html', [
    'current_url' => $url,
    'title' => 'WebCrawler',
    'PagesScoringBoardData' => $PagesScoringBoardData,
    'pagesStatistics' => [
        'created_at' => $pagesStatistics->getCreatedAt(),
        'page_count' => $pagesStatistics->getPageCount(),
        'unique_images_count' => $pagesStatistics->getUniqueImagesCount(),
        'unique_internal_links_count' => $pagesStatistics->getUniqueInternalLinksCount(),
        'unique_external_links_count' => $pagesStatistics->getUniqueExternalLinksCount(),
        'avg_pages_load' => $pagesStatistics->getAvgPagesLoad(),
        'avg_title_length' => $pagesStatistics->getAvgTitleLength(),
        'avg_words_count' => $pagesStatistics->getAvgWordsCount(),
    ],
]);

