<?php

declare(strict_types=1);

namespace HTMLDomParserTests\Base;

use HTTPCrawler\DomUrl;
use HTTPCrawler\Page;

class PageTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test page html code parsing
     */
    public function testPage()
    {

        $domUrl = new DomUrl('https://test.ca');

        $domUrl->setHtmlContent(
            htmlContent: '<html>
                <head>Title test</head>
                <body>
                    <h1>Test</h1>
                    <a href="/contacts">Contacts</a>
                    <a href="/about">About</a>
                    <a href="https://test.ca/test">Test</a>
                    <a href="/feedback">Feedback</a>

                    <img src="https://test.com/images.jpg" alt="Test 1">
                    <img src="/logo.jpg" alt="Test 2">

                    <a href="https://inst.com/">Ext Contacts</a>
                    <a href="https://fb.com/about">Ext About</a>

                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="Test 3">
                </body>
            </html>'
        );
        $domUrl->fetchHtmlContent();

        $page = new Page($domUrl);

        $this->assertInstanceOf('HTTPCrawler\DomUrl', $page->getDomUrl());


        $this->assertEquals([
            'https://test.com/images.jpg',
            '/logo.jpg',
            'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'
        ],  $page->getImages());


        $this->assertEquals([
            'https://inst.com/',
            'https://fb.com/about'
        ],  $page->getExternalLinks());


        $this->assertEquals([
            '/contacts',
            '/about',
            'https://test.ca/test',
            '/feedback',
        ],  $page->getInternalLinks());
    }
}
