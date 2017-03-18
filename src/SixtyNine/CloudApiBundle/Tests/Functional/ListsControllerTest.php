<?php

namespace SixtyNine\CloudApiBundle\Tests\Functional;

use SixtyNine\CoreBundle\Test\SixtyNineTest;
use Nelmio\Alice\Fixtures;

class ListsControllerTest extends SixtyNineTest
{
    /** @var array */
    protected $fixtures;

    /** @var \Symfony\Bundle\FrameworkBundle\Client */
    protected $client;

    /** @var \Symfony\Bundle\FrameworkBundle\Routing\Router  */
    protected $router;

    /** @var  \Doctrine\ORM\EntityManagerInterface */
    protected $em;

    /** @var \SixtyNine\CloudBundle\Repository\WordsListRepository */
    protected $wordsRepo;

    public function __construct()
    {
        $this->fixtures = $this->loadFixtureFiles(array(
            __DIR__ . '/../fixtures/users.yml',
            __DIR__ . '/../fixtures/lists.yml',
        ));

        $this->runCommand('sn:palettes:load-default');

        $this->client = $this->makeClient(true);
        $this->router = $this->getContainer()->get('router');
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->wordsRepo = $this->em->getRepository('SixtyNineCloudBundle:WordsList');
    }

    // ----- GET LISTS

    public function testGetLists()
    {
        $route = $this->router->generate('cloud_api_get_lists');
        $this->client->request('GET', $route);

        $data = $this->assertJsonResponse($this->client);

        $this->assertTrue(is_array($data));
        $this->assertCount(1, $data);

        $data = reset($data);

        $this->assertArrayContains($data, 'id', 1);
        $this->assertArrayContains($data, 'name', 'List 0');
        $this->assertArrayContains($data, 'count', 10);
    }

    // ----- GET LIST

    public function testGetList()
    {
        $route = $this->router->generate('cloud_api_get_list', array('id' => 1));
        $this->client->request('GET', $route);

        $data = $this->assertJsonResponse($this->client);

        $this->assertArrayContains($data, 'id');
        $this->assertArrayContains($data, 'name', 'List 0');
        $this->assertArrayContains($data, 'count', 10);
    }

    public function testGetListForbidden()
    {
        $route = $this->router->generate('cloud_api_get_list', array('id' => 2));
        $this->client->request('GET', $route);
        $data = $this->assertJsonResponse($this->client, 403);
    }

    public function testGetListNotFound()
    {
        $route = $this->router->generate('cloud_api_get_list', array('id' => 99));
        $this->client->request('GET', $route);
        $data = $this->assertJsonResponse($this->client, 404);
    }

    // PUT LIST

    /** @group current */
    public function testPutList()
    {
        $route = $this->router->generate('cloud_api_put_list', array('id' => 1));
        $this->requestWithContent($this->client, 'PUT', $route, array('name' => 'foobar'));
        $data = $this->assertJsonResponse($this->client);

        $this->em->clear();
        $list = $this->wordsRepo->findOneById(1);
        $this->assertEquals('foobar', $list->getName());
    }
}
