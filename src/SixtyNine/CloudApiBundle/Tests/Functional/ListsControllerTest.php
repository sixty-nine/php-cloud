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
        $this->client = $this->makeClient(true);
        $this->router = $this->getContainer()->get('router');
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->wordsRepo = $this->em->getRepository('SixtyNineCloudBundle:WordsList');
    }

    protected function setUp()
    {
        $this->fixtures = $this->loadFixtureFiles(array(
            __DIR__ . '/../fixtures/users.yml',
            __DIR__ . '/../fixtures/lists.yml',
        ));

        $this->runCommand('sn:palettes:load-default');

        $this->em->clear();
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
        $this->assertJsonResponse($this->client, 403);
    }

    public function testGetListNotFound()
    {
        $route = $this->router->generate('cloud_api_get_list', array('id' => 99999));
        $this->client->request('GET', $route);
        $this->assertJsonResponse($this->client, 404);
    }

    // POST LIST

    public function testPostListEmptyName()
    {
        $route = $this->router->generate('cloud_api_post_list');
        $this->requestWithContent($this->client, 'POST', $route, array('name' => ''));
        $this->assertJsonResponse($this->client, 400);
    }

    public function testPostList()
    {
        $route = $this->router->generate('cloud_api_post_list');
        $this->requestWithContent($this->client, 'POST', $route, array('name' => 'foobar'));
        $data = $this->assertJsonResponse($this->client);

        $this->assertArrayContains($data, 'id');
        $this->assertArrayContains($data, 'name', 'foobar');
        $this->assertArrayContains($data, 'count', 0);

        $list = $this->wordsRepo->findOneById($data['id']);
        $this->assertEquals('foobar', $list->getName());
        $this->assertEquals($this->fixtures['admin']->getId(), $list->getUser()->getId());
    }

    // PUT LIST

    public function testPutNotExistingList()
    {
        $route = $this->router->generate('cloud_api_put_list', array('id' => 99999));
        $this->requestWithContent($this->client, 'PUT', $route, array('name' => ''));
        $this->assertJsonResponse($this->client, 404);
    }

    public function testPutListEmptyName()
    {
        $route = $this->router->generate('cloud_api_put_list', array('id' => 1));
        $this->requestWithContent($this->client, 'PUT', $route, array('name' => ''));
        $this->assertJsonResponse($this->client, 400);

        $list = $this->wordsRepo->findOneById(1);
        $this->assertEquals('List 0', $list->getName());
    }

    public function testPutUnauthorizedList()
    {
        $route = $this->router->generate('cloud_api_put_list', array('id' => 2));
        $this->requestWithContent($this->client, 'PUT', $route, array());
        $this->assertJsonResponse($this->client, 403);
    }

    public function testPutListMissingName()
    {
        $route = $this->router->generate('cloud_api_put_list', array('id' => 1));
        $this->requestWithContent($this->client, 'PUT', $route, array());
        $this->assertJsonResponse($this->client, 400);
    }

    public function testPutList()
    {
        $route = $this->router->generate('cloud_api_put_list', array('id' => 1));
        $this->requestWithContent($this->client, 'PUT', $route, array('name' => 'foobar'));
        $this->assertJsonResponse($this->client);

        $list = $this->wordsRepo->findOneById(1);
        $this->assertEquals('foobar', $list->getName());
    }

    // ----- DELETE LIST

    public function testDeleteList()
    {
        $route = $this->router->generate('cloud_api_delete_list', array('id' => 1));
        $this->requestWithContent($this->client, 'DELETE', $route);
        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteNotExistingList()
    {
        $route = $this->router->generate('cloud_api_delete_list', array('id' => 99999));
        $this->requestWithContent($this->client, 'DELETE', $route);
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testUnauthorizedList()
    {
        $route = $this->router->generate('cloud_api_delete_list', array('id' => 2));
        $this->requestWithContent($this->client, 'DELETE', $route);
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

}
