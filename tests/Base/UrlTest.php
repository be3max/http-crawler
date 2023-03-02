<?php

declare(strict_types=1);

namespace HTMLDomParserTests\Base;

use HTTPCrawler\Url;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    /**
     *
     */
    public function testHandleSingleCard()
    {
        $url = new Url('https://stackoverflow.co/teams/customers');

        $this->assertEquals('stackoverflow.co', $url->getDomain());
        $this->assertEquals('https://stackoverflow.co/teams/customers', $url->getUrl());


        $url = new Url('stackoverflow.co/teams/customers');

        $this->assertEquals('stackoverflow.co', $url->getDomain());
        $this->assertEquals('https://stackoverflow.co/teams/customers', $url->getUrl());


        $url = new Url('stackoverflow.co/contacts');

        $this->assertEquals('stackoverflow.co', $url->getDomain());
        $this->assertNotEquals('https://stackoverflow.co/contacts/', $url->getUrl());
    }

}
