<?php

namespace SixtyNine\CoreBundle\Test;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class SixtyNineTest extends WebTestCase
{
    protected function requestWithContent(Client $client, $method, $route, $content)
    {
        $client->request($method, $route, array(), array(), array(), json_encode($content));
    }

    protected function assertJsonResponse(Client $client, $statusCode = 200)
    {
        $this->assertTrue(
            $client->getResponse()->headers->contains('Content-Type', 'application/json')
        );

        $this->assertEquals($statusCode, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue(false !== $data);

        return $data;
    }

    protected function assertIsArray($array)
    {
        $this->assertTrue(
            is_array($array),
            'The value is not an array'
        );
    }

    protected function assertArrayHasKeys($array, array $keys)
    {
        $this->assertIsArray($array);

        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $array);
        }
    }

    protected function assertArrayValueEquals($array, $key, $value)
    {
        $this->assertEquals($array[$key], $value);
    }

    protected function assertArrayContains($array, $key, $value = null)
    {
        $this->assertIsArray($array);
        $this->assertArrayHasKey($key, $array);

        if (null !== $value) {
            $this->assertArrayValueEquals($array, $key, $value);
        }
    }
}
 