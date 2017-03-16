<?php

namespace SixtyNine\CloudApiBundle\Tests\Functional;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Nelmio\Alice\Fixtures;

class ListsControllerTest extends WebTestCase
{
    public function __construct()
    {
        $this->loadFixtureFiles(array(__DIR__ . '/../fixtures/users.yml'));
    }

    public function testBla()
    {
        $this->assertTrue(true);
    }
}
 